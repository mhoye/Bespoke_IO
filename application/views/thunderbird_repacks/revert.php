<?=View::factory('thunderbird_repacks/elements/confirm', array(
    'repack'     => $repack,
    'head_title' => _('revert'),
    'crumbs'     => _('revert release'),
    'message'    => _('Revert the existing release for this mail client?'),
    'url'        => url::site(url::current()),
))->render()?>
