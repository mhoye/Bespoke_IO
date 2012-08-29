<?php slot::set('head_title', 'registration successful'); ?>
<h2>Registration successful</h2>

<?php if (!isset($email_sent)): ?>
<p>
    The account has been created. 
</p>

<p>
    You can use the button below to send the account activation email:
</p>
<?php else: ?>
<p>
    The account activation email has been sent.
</p>
<?php endif ?>

<form action="<?=url::site('reverifyemail/'.urlencode($login_name))?>" method="POST">
    <input type="submit" value="Re-send Account Activation Information" />
</form>
