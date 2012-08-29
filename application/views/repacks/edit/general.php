<div class="intro">
    <p><?=sprintf(_('The information in this section is for your internal use, and should describe who and what this browser is tailored for. Include enough information that, should you need to create several different versions of the browser, each one can be easily identified.'), html::specialchars($repack->title))?></p>
    <p><?=_('The title is a label that is used to differentiate one tailored browser from another, and will appear as the program title in the generated .MSI file. Please also include a more detailed description customizations.')?></p>
    <p><?=_('You must give your browser a title to proceed.')?></p>

</div>

<?php
    $version = form::value('ffversion');
    if (empty($version)) $version = '5.0';
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
                <?= form::dropdown('ffversion', array('5.0' => '5.0', '4.0rc2' => '4.0'), ($version == '4.0') ?  '4.0' : '5.0') ?>	 
            </div>
        </fieldset>
    </div>

</div>
