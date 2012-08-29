<?php if (false && !empty($invalid_token)): ?>
    <p>Invalid email verification token.</p>
<?php else: ?>
    <?php slot::set('head_title', 'account verification successful'); ?>
    <h2>Account verification successful</h2>
    <p>
        Welcome to Feralspace's Bespoke Software Deployment Server.

        Your account has been successfully activated, and we've set a cookie for you that will keep
	you logged in application. Please log out when you're finished BeSDS by using the "logout" 
	link in the upper-right corner of every page.
    </p>
    <p>
        <br />
        <?php
            $profile_url = url::site("profiles/".authprofiles::get_profile('screen_name'));
        ?>
        <a href="<?=$profile_url?>" class="button yellow large">Get started building your custom browser now.</a>
    </p>
<?php endif ?>
