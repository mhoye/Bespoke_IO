<?php slot::set('head_title', 'login'); ?>
<?php slot::set('crumbs', 'account login'); ?>

<?php 
    $is_popup = (isset($_GET['popup']));
    slot::set('is_popup', $is_popup);
?>

<?php if (isset($_GET['gohome'])): ?>
    <script type="text/javascript">
        window.top.location.href="<?=url::site('home')?>";
    </script>
<?php else: ?>

<?php if ($is_popup): ?>
    <div class="header">
        <h2><?=_('Log into your account')?></h2>
    </div>
    <div class="content">
<?php endif ?>

<?php if (!empty($login_inactive)): ?>
    <p><?=_('Sorry, that login has expired.')?></p>
<?php endif ?>

<?php if (!empty($no_verified_email)): ?>
    <script type="text/javascript">
        window.top.location.href="<?=url::site('login')?>?show_reverify=<?=urlencode($_POST['login_name'])?>";
    </script>
<?php endif ?>
<?php if (!empty($_GET['show_reverify'])): ?>
    <div class="warning">
        <p><?=_('In order to use BeSDS, you require an active account. Please contact Bespoke I/O to subscribe to this service.')?></p>
        <form action="<?=url::site('reverifyemail/'.urlencode($_GET['show_reverify']))?>" method="POST">
            <button class="button blue large" type="submit"><?=_('Re-send Account Activation Information')?></button>
        </form>
    </div>
<?php endif ?>

<?php 
/* Munge the errors to obscure what part of the login was invalid. */
$invalid =  (!empty(form::$errors));
form::$errors = array();
?>

<?php slot::start('submit') ?>
<li class="required submit">
    <label class="hidden" for="login"/>

    <button id="login" class="submit required button large yellow"><?=_('Login')?></button>
    <?php if ($is_popup): ?>
        <button id="cancel" class="popup_cancel button large blue"><?=_('Cancel')?></button>
    <?php endif ?>
</li>
<?php slot::end() ?>

<?= 
form::build(url::site('login'), array('class'=>'login'), array(
    form::field('hidden', 'crumb', '', array('value'=>$crumb)),
    form::field('hidden', 'jump', ''),
    form::fieldset(null, array('class'=>'login'), array(
        form::field('input',    'login_name', _('Username'), array(
            'class'=>'required' . ( $invalid ? ' error' : '') 
        )),
        form::field('password', 'password', _('Password'), array(
            'class'=>'required' . ( $invalid ? ' error' : '') 
        )),
        slot::get('submit')
    ))
)) 
?>

<?php if ($is_popup): ?>
    </div>
<?php endif ?>
<?php endif ?>