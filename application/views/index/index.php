<?php
    if (authprofiles::is_logged_in()) {
        $create_url = url::site("profiles/" . 
            authprofiles::get_profile('screen_name'));
    } else {
        $create_url = url::site('login');
    }
?>
<?php slot::set('head_title', 'home') ?>
<?php slot::set('crumbs', 'home') ?>

<div class="page_intro clearfix">
	<div class="blurb">
        <h3><?=_('Welcome to Bespoke I/O\'s Browser Deployment Server.')?></h3>
        <p>
            <?=_('We\'re pleased to offer our clients a secure, modern web browser<br> for internal deployment in the enterprise environment.')?>
        </p>
        <p>            
	<?=_('If you\'d like to try BeSDS, or would like <br>any other information, contact us at <a href="mailto:info@bespokeio.com">info@bespokeio.com</a>  ')?>
        </p>

<br/>

   	<p><a class="create_browser yellow button" href="<?=$create_url?>">
            <?=_('<span class="first_line">Click here to begin.</span>')?>
        </a></p>
<br>
        <p class="faq">
            <?=_('Questions?')?>
            <a href="http://bespokeio.com/faq.html"><?=_('Check out our FAQ.')?></a>
        </p>
    </div>
</div>

