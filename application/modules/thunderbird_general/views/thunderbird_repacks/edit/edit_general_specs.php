<?php slot::start('head_end') ?>
    <?=html::stylesheet(array('css/repacks-edit.css'))?>
    <?=html::stylesheet(array('application/modules/general/public/css/general_specs.css'))?>
<?php slot::end() ?>

<div class="intro">
    <p><?=sprintf(_('The information in this section is for your internal use, and should describe who or what this mail client is tailored for. Include enough information that, should you need to create several different versions of the mail client, each one can be easily identified.'), html::specialchars($repack->title))?></p>
    <p><?=_('The title is a label that is used to differentiate one tailored mail client from another, and will appear as the program title in the generated .MSI file.')?></p>
    <p><?=_('You must give your mail client a title to proceed.')?></p>
</div>

<?php
    $version = form::value('tbversion');
    $selection = thunderbird_generalspecs::get_all_versions();
    $selection_int = array();
    foreach ($selection as $s)
      array_push($selection_int, floatval($s));
    if (empty($version))
      $version = strval(max($selection_int));
?>

<div class="pane">
    <div>
        <fieldset><legend><?=_('Browser details')?></legend>
            <div class="user_title">
                <p><?=_('Enter a short identifier for this customization of Thunderbird.  (required, max length 255 characters):')?></p>
                <?= form::input('user_title', form::value('user_title')) ?>
            </div>
            <div>
                <p><?=_('Choose the Thunderbird version that you want to build')?></p>
                <?= form::dropdown('tbversion', $selection, $version)?>
            </div>
        </fieldset>
    </div>
</div>

