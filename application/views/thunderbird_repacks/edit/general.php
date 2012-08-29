<div class="intro">
    <p><?=sprintf(_('The information in this section is for your internal use, and should describe who and what this mail client is tailored for. Include enough information that, should you need to create several different versions, each one can be easily identified.'), html::specialchars('repack->title'))?></p>
    <p><?=_('The title is a label that is used to differentiate one tailored mail client from another, and will appear as the program title in the generated .MSI file. Please also include a more detailed description customizations.')?></p>
    <p><?=_('You must give your mail client a title to proceed.')?></p>

</div>

<div class="pane">
    <div>
        <fieldset><legend><?=_('Mail client details')?></legend>
            <div class="user_title">
                <p><?=_('Enter a short identifier for this customization of Thunderbird.  (required, max length 255 characters):')?></p>
                <?= form::input('user_title', form::value('user_title')) ?>
            </div>
        </fieldset>
    </div>

</div>

