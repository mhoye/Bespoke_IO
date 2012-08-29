<?php slot::set('head_title', 'Register'); ?>
<?php slot::set('crumbs', 'Register a new user'); ?>

<?php slot::start('body_end') ?>
    <?=html::script(array(
        'js/jquery.passwordStrengthMeter.js',
    ))?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.fields li.use_password_meter').passwordStrengthMeter(); 
        });
    </script>
<?php slot::end() ?>

<?php slot::start('login_details_intro') ?>
<div class="intro">
    <p>
        Please fill in <strong>every field</strong> below.
    </p>
</div>
<?php slot::end() ?>

<?php slot::start('password_strength_meter') ?>
    <span class="password_strength_meter default">
        <span class="label default">Password Strength</span>
        <span class="label short">Too short</span>
        <span class="label bad weak">Weak password</span>
        <span class="label good">Good password</span>
        <span class="label better">Strong password</span>
        <span class="label strong">Strong password</span>
        <span class="meter"><span class="indicator">&nbsp;</span></span>
    </span>
<?php slot::end() ?>

<?php
$name_errors = array();
foreach (array('first_name','last_name') as $name) {
    if (!empty($form_errors[$name])) 
        $name_errors[] = $form_errors[$name];
}


echo form::build('register', array('class'=>'register'), array(
    form::field('hidden', 'crumb', '', array('value'=>$crumb)),
    form::fieldset('Register a New User', array('class'=>'login'), array(
        '<li>' . slot::get('login_details_intro') . '</li>',
        array(
            '<li class="input required text two_up ' .
                    (!empty($name_errors) ? 'error' : '').'">',
                '<label for="first_name">User name</label>',
                form::input(array('name'=>'first_name', 'title'=>'First name')),
                form::input(array('name'=>'last_name', 'title'=>'Last name')),
                (empty($name_errors) ? '' : 
                    '<p class="notes"><strong class="error">' . 
                        join('; ', $name_errors) .
                    '</strong></p>' ),
            '</li>',
        ),
        form::field('input', 'email', 'User email', array('class'=>'divider required'), array(
            empty($form_errors['email']) ?
                "Send an email to this address to inform the user of his/her login name and password." :
                "<strong class='error'>".$form_errors['email']."</strong>"
        )),
        form::field('input', 'login_name', 'Login name', array('class'=>'divider login_name required'), array(
            empty($form_errors['login_name']) ?
                "Used for logging in. Use 4 to 12 characters. Letters, numbers, hyphens, and underscores only." :
                "<strong class='error'>".$form_errors['login_name']."</strong>"
        )),
        form::field('password', 'password', 'Password', array('class'=>'password required use_password_meter'), array(
            empty($form_errors['password']) ?
                "Use 6 to 32 characters. Capitalization matters." :
                "<strong class='error'>".$form_errors['password']."</strong>",
            slot::get('password_strength_meter') 
        )),
        form::field('password', 'password_confirm', 'Re-type password', array('class'=>'divider required'), array(
            empty($form_errors['password_confirm']) ? '' :
                "<strong class='error'>".$form_errors['password_confirm']."</strong>",
        )),
       	form::field('input', 'admin', 'Admin name', array('class'=>'required'), array(
		empty($form_errors['admin']) ?
                "Name of the admin registering the new user" :
                "<strong class='error'>".$form_errors['admin']."</strong>"
        )),
	form::field('password', 'admin_pwd', 'Admin password', array('class'=>'divider required') , array(
		empty($form_errors['admin_pwd']) ?
                "Password of the admin registering the new user" :
                "<strong class='error'>".$form_errors['admin_pwd']."</strong>"
        )),
        '<li class="required submit"><label class="hidden" for="register">&nbsp;</label><button id="register" class="submit required button large yellow">Create a New Account</button>',
    ))
));
?>
