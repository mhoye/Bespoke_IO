From: service@bespokeio.com
Subject: [Bespoke I/O] Release failed for <?=$repack->display_title . "\n"?>

Greetings,

This is an automatically generated email from Bespoke I/O's BeSDS service.

The generation of a requested release for the browser <?=$repack->display_title?> has failed. 
Administrators have been notified, and are investigating. Our apologies for any inconvenience this may cause. 

Status information and the release history of this browser can be found at:
<?=$repack->url() . "\n" ?>

<?php if (!empty($comments)): ?>

Comments:

<?=$comments?>

<?php endif ?>



Thanks,

The Bespoke I/O team.
