From: info@bespokeio.com 
Subject: New Password Request from BeSDS.

Someone has requested a reset of the password for the BeSDS login (registered to 
this email address) named "<?=$login_name?>”. If you are that someone, and wish to reset 
your password, please follow the link below to complete the password reset 
process:

<?= 
url::full('changepassword', false, array(
    'password_reset_token' => $password_reset_token
)) . "\n"
?>

If you didn’t request a password reset for this account, simply ignore this 
email and your current password will remain in place. If you have any questions 
about this email, or experience difficulties resetting your password, please 
contact us via the BeSDS contact page at <?=$contact_URL?>.

Thank you,

Bespoke I/O.
