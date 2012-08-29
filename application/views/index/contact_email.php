From: <?= $email . "\n" ?>
Subject: [BespokeIO] Contact us (<?=$category?>)

Contact form (<?=$category?>) submission from <?=$name?> <<?=$email?>>

<?php if (isset($referer)): ?>
Referring page: <?=$referer . "\n"?>
<?php endif ?>

<?=$comments?>
