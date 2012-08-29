#!/bin/sh

echo 'Doing some housekeeping, setting up some directories installing some RPMs.'
echo 'Control-C to abort, any other key to continue.'

read

mkdir repack_assets && chmod 775 repack_assets
mkdir application/cache && chmod 775 application/cache
mkdir application/logs && chmod 775 application/logs

rpm -i bin/wixPackages/*.rpm

mkdir /.wixwine
chgrp apache /.wixwine
chown apache /.wixwine

mv htaccess-dist .htaccess


