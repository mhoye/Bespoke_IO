BeSDS
=====

This is Bespoke I/O's Bespoke Software Deployment Service (BeSDS), 
a web service that builds customized .MSIs of Firefox and Thunderbird for
internal deployment to Windows machines vial Microsoft's SMS or SCCM
enterprise management tools.

It is available from: 

  http://github.com/mhoye/Bespoke_IO

under the Mozilla Public License.

There's some work to be done on cleaning up the text in the app that's 
ongoing, but the application will generate customized .MSIs as described. 


## Overview

BeSDS is a Firefox & Thunderbird customization and deployment tool, derived
from Mozilla's BYOB project. It is a fairly complex, multistack application,
with a number of moving parts. The following information will guide you 
through a typical installation on a Fedora 16-based machine.

## Installation

Generally speaking:

* Prerequisites are:
    * MySQL 5.0+
    * PHP 5.3, with at least the following modules
        * curl, gd, mcrypt, mysql, mysqli
    * WiX 3.0 or better
    * A recent Wine
    * A recent version of Python   

* Filesystem must have:
    * Ensure the following directories exist and are writable by the web server:
        * `application/cache`
        * `application/logs`
        * `downloads`
        * `workspace`
    * BeSDS expects to be in the root folder of the web server wherever it's running,
      ( http://server/, not http://server/sub/ ). It will not work in a subfolder.

* MySQL requirements are: 
    * Create a new database using the current schema:
        * `application/config/schema-mysql/current.sql`
    * Though `current.sql` should always contain the latest schema, changes to the list of supported
      products are also mirrored in the modify-products.sql and modify-products-thunderbird.sql files,
      for ease of updating.

* Application config is:
    * All under `application/config`
    * Copy `config-local.php-dist` to `config-local.php` and edit to make installation-specific changes.
        * The `database.local` structure should be given the MySQL credentials to access the database created in the previous step.
        * The `database.shadow` structure should be given the same MySQL credentials as `database.local`, or configured to point at a read-only replica of `database.local`.
        * Change the `recaptcha` settings to reflect the domain, public key, and private key data acquired from `recaptcha.net`
        * Change the email.* settings to reflect local email environment.
            * Set `email.driver` to 'native' if PHP itself is setup to send email
            * Set `email.driver` to 'smtp' and update `email.options` if an external SMTP server is to be used.
        * Set `core.display_errors` to `FALSE` to prevent verbose error messages
        * Set `core.log_threshold` to 0 to disable logging to `application/logs`
        * Change `core.site_domain` to the domain name of the web host, deleting the code to guess the domain name for dev servers.
    * Copy `repacks.php-dist` to `repacks.php` and edit to make installation-specific changes.
        * In particular, the locations of the `downloads` and `workspace` directories can be changed.

* Create the admin user as follows:
    * At the command line, execute this command from the application directory:
        * ` php index.php util/createlogin admin someone@somewhere.com admin` 
            * Replace `someaddress@somewhere.com` with a real email address
        * You should see output like the following:
            * `Profile ID 1 created for 'admin' with role 'admin'`
            * `Password: mnm518x`
        * The last line is the temporary password for the admin account - someone should use it and change it immediately.


Specifically, installation on a Fedora box goes as follows:

1.  Using the Fedora 16 DVD image (not the LiveCD iso) on an appropriately-sized
    box. 4GB disk and 1GB RAM is a reasonable minimum size.

2.  Through the Fedora installer, select a "minimal install" and finish the
    installation.

3.  On fedora:  # yum install git /
                  httpd mysql-server /
                  php php-gd php-mcrypt php-mysql php-getext php-xml php-pear 
                  p7* / 
                  wine

    Note that BeSDS currently requires on PHP 5.3. A small amount of code relies 
    on a deprecated behavior that has been dropped in 5.4. This will be fixed 
    shortly.

4.  Install the editor of your choice, emacs, vim or nano.

5.  If you installed nano in step four, hang your head; you have brought 
    shame to your family and dishonor to your clan. Sack up, put your unix
    boots on and learn one of the other two. 

6.  git clone http://github.com/mhoye/Bespoke_IO/
  
7.  In Bespoke_IO/application/config/mysql-schema/ you can use a script
    called database_setup.sh to quickly install and preconfigure the 
    the appropriate user and  permissions. This will also install
    a curtailed list of the available versions of Firefox and Thunderbird,
    the most current mainline and extended support versions of each.

8.  Again in the Bespoke_IO folder execute the following script:

      ./bin/setup_script.sh

    This will set some paths and permissions appropriately. It also 
    installs some RPMs, so if you're not using Fedora or some other 
    RPM-based distribution, you'll need to identify which packages 
    are on that list, and how to obtain and install them yourself.

9.  Move the entire contents of the newly created Bespoke_IO folder 
    to root folder of your web server, usually /var/www/html/ - if 
    you intend to pull directly from the git repo into production, make
    sure to copy over the .git folder as well. Future releases will
    have alternative branches for development and production, but at
    the moment they do not so this approach is not recommended. 

10. Outside of Bespoke I/O's code, you will need to configure PHP 
    (in /etc/php.ini) to use short tags and set the time zone correctly. 

    You will need to modify your Apache configuration (httpd.conf) to
    "AllowOverride All" in the appropriate place. Be advised that the
    risks involved in doing so are your responsibility to understand
    and accept before deployment. Likewise, on some systems your default
     firewall configuration will need to be modified or disabled. 

    Again, the consequences of not knowing what you're doing here are
    your responsibility.

11. In in applications/config, copy the config-local.php-dist file
    to config-local.php and open it up in the editor you picked that
    wasn't nano. You will need to change the line that references the
    core.site_domain (line 3) to be whatever you have named the box,
    or at a minimum whatever its IP address is, for it to work. If you
    decide to activate mail notifications, by setting that option to
    TRUE, you also need to configure the email.options section 
    correctly.

12. Finally, in the root folder of your web server, in a terminal, do this: 

       php index.php util/createlogin admin person@companyname.com admin

    ... replacing person@companyname.com with your own address. 

    This will create an "admin" user on the web service, with the 
    appropriate permissions, and give you that account's password. You
    can log in and change this at your earliest convenience.

At this point, you should be able to log into BeSDS as a web service, using
the username "admin" and the password provided in step 10.


On a personal note, I'd like to thank Mozilla and Seneca/CDOT for the 
opportunity to work with some excellent people. It's been an honour and
a great privilege.

        - Mike Hoye, August 2012.
