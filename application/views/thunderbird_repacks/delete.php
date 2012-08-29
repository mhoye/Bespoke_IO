<?php slot::start('preamble') ?>
    <div>
        <p><?=_('Abandoning changes will delete the current set of customizations, and cannot be undone. Revised customizations will be reverted; unfinished customizations will be lost. To cancel, choose the "No" button below. To abandon your changes, please enter a reason and click on the "Yes" button.')?></p>
    </div>
<?php slot::end() ?>
<?=View::factory('repacks/elements/confirm', array(
    'repack'     => $repack,
    'head_title' => _('delete'),
    'crumbs'     => _('delete browser'),
    'message'    => ($repack->isRelease()) ?
        _('Delete this mail client?') :
        _('Abandon changes to this mail client?'),
    'url'        => url::site(url::current()),
))->render()?>
