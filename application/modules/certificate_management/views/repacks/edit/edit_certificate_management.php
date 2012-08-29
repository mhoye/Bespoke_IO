<?php slot::start('head_end') ?>
    <?=html::stylesheet(array('css/repacks-edit.css'))?>
    <?=html::stylesheet(array('application/modules/certificate_management/public/css/certificate_management.css'))?>
<?php slot::end() ?>

<?php
$certs = form::value('certs'); 
if (empty($certs)) $certs = array();
?>

<div class="intro">
    <p><?=_('If you would like to preload your own SSL certificates into your browser you may do so here.')?></p>
</div>
<div class="pane">
    <fieldset class="upload">
    <iframe id="upload-certificate" 
        src="<?=$repack->url()?>/certificates;upload_cert"
        scrolling="no"></iframe>
    </fieldset>
</div>
