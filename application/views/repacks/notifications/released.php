From: service@somecompany
Subject: [Bespoke I/O] Release failed for <?=$repack->display_title . "\n"?>

Greetings,

This is an automatically generated email from Bespoke I/O's BeSDS service.

Your customized version of Firefox <?=$repack->display_title?>
has been built, and is now ready for download. <?php /* You can 
review the distribution rules for this browser, which can be found here [link 
to distro rules] as well as the release history <?=$repack->releaseUrl()?>. */ ?>

If you have any questions or feedback, please direct them to the Bespoke I/O 
homepage at https://bespokeio.com/.
 
Status information and the release history of this browser can be found at:

<?=$repack->url() . "\n" ?>

<?php if (!empty($comments)): ?>

Comments:

<?=$comments?>

<?php endif ?>



Thanks,

The Bespoke I/O team.
