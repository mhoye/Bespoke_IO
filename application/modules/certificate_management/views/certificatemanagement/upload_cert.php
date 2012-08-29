<?php slot::set('is_popup', true); ?>

<?php slot::start('head_end') ?>
  <?=html::stylesheet(array('css/repacks-edit.css'))?>
  <?=html::stylesheet(array('application/modules/certificate_management/public/css/certificate_management.css'))?>
<?php slot::end() ?>

<?php slot::set('body_class', 'repack_upload_iframe')?>

<?php slot::start('body_end') ?>
  <script type="text/javascript">
      /**
       * HACK: Adjust the parent iframe to the size of this page's content, if 
       * framed.
       */
      window.adjustHeight = function () {
          if (top.location.href == window.location.href) return;
          var f_height = $('#content').height();
          if (f_height)
              top.jQuery('iframe#upload-certificate').height(f_height);
      };
      window.adjustHeight();
      if (top.BYOB_Repacks_Edit_CertificateManagement && top.BYOB_Repacks_Edit_CertificateManagement.updateSelectionsPane)
          top.BYOB_Repacks_Edit_CertificateManagement.updateSelectionsPane();
      $(document).ready(window.adjustHeight);
  </script>
 
<?php slot::end() ?>

<div>
  <form method="POST" enctype="multipart/form-data">
  <fieldset class="upload">
    <div>
      <p><?=_('Certificates uploaded through this utility will be preloaded in your customized version of Firefox. Be sure to use only PEM-formatted certificates.')?></p>
    </div>
    <div class="pretty_upload">
      <input type="file" class="upload" id="cert" name="cert" />
      <button name="submit" class="button blue"><?=_('Upload')?></button>
	  </div>
  </fieldset>
  </form>

  <?php if (!empty($errors)):?>
    <ul class="errors">
      <?php foreach($errors as $error): ?>
        <li><?=html::specialchars($error) ?></li>
      <?php endforeach ?>
      </ul>
  <?php endif ?>

  <?php if (!empty($certificates)): ?>
            <fieldset class="selection_title"><legend><?=_('Uploaded certificates')?></legend>
                <ul class="selections">
                    <?php foreach($certificates as $certificate): ?>
                        <li><a href="#" class="remove_link">
                            <span class="name"><?= html::specialchars(basename($certificate)) ?></span>
                            <form class="delete" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="method" value="delete" />
                                <input type="hidden" name="cert_file" 
                                    value="<?= html::specialchars(basename($certificate))?>" />
                                <button name="submit" class="remove"><?=_('Remove')?></button>
                            </form>
                        </a></li>
                    <?php endforeach ?>
                </ul>
            </fieldset>
    <?php endif ?>
</div>
