<?=View::factory('thunderbird_repacks/elements/confirm', array(
    'repack'     => $repack,
    'head_title' => _('reject'),
    'crumbs'     => _('reject release'),
    'message'    => _('Reject a new release for this mail client'),
    'url'        => url::site(url::current()),
))->render()?>
