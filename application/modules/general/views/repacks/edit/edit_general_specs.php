<?php slot::start('head_end') ?>
    <?=html::stylesheet(array('css/repacks-edit.css'))?>
    <?=html::stylesheet(array('application/modules/general/public/css/general_specs.css'))?>
<?php slot::end() ?>

<div class="intro">
    <p><?=sprintf(_('The information in this section is for your internal use, and should describe who and what this browser is tailored for. Include enough information that, should you need to create several different versions of the browser, each one can be easily identified.'), html::specialchars($repack->title))?></p>
    <p><?=_('The title is a label that is used to differentiate one tailored browser from another, and will appear as the program title in the generated .MSI file. (For example, if you enter "Accounting" and select version 7, the name of your file will be "Firefox 7 - Accounting.")')?></p>
    <p><?=_('You must give your browser a title to proceed.')?></p>
</div>

<?php
    $version = form::value('ffversion');
    //$selection = array('4.0rc2' => '4.0', '5.0' => '5.0');
    $selection = general_specs::get_all_versions();
    $selection_int = array();
    foreach ($selection as $s)
      array_push($selection_int, floatval($s));
    $max = max($selection_int);
    if (empty($version)) $version = strval($max);
?>

<div class="pane">
    <div>
        <fieldset><legend><?=_('Browser details')?></legend>
            <div class="user_title">
                <p><?=_('Enter a short identifier for this customization of Firefox.  (required, max length 255 characters):')?></p>
                <?= form::input('user_title', form::value('user_title')) ?>
            </div>
            <div>
                <p><?=_('Choose the Firefox version that you want to build')?></p>
                <?= form::dropdown('ffversion', $selection, $version)?>
            </div>
        </fieldset>
    </div>
</div>

