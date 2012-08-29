<?php
/**
 * Configuration for addons available for use in repacks
 */
$config['api_url'] = 
    'https://services.addons.mozilla.org/en-US/firefox/api/1.3/addon/%s';
$config['dir'] = 
    dirname(APPPATH) . "/addons";
$config['collections_api_url'] =
    'https://addons.mozilla.org/en-US/firefox/api/1.3/sharing/';
$config['collections_username'] =
    'USERNAME_NEEDS_LOCAL_CONFIG';
$config['collections_password'] =
    'PASSWORD_NEEDS_LOCAL_CONFIG';

$config['personas_extension_id'] = '10900';

$config['popular_extension_ids'] = array(
    '1865',
    '71',
    '2313',
    '70',
    '5373',
    '4433',
    '611',
    '4631',
    '1117',
    '48971',
);

$config['popular_personas_urls'] = array(
    'https://addons.mozilla.org/en-US/firefox/persona/29974',
    'https://addons.mozilla.org/en-US/firefox/persona/15114',
    'https://addons.mozilla.org/en-US/firefox/persona/15131',
    'https://addons.mozilla.org/en-US/firefox/persona/61916',
    'https://addons.mozilla.org/en-US/firefox/persona/17848',
    'https://addons.mozilla.org/en-US/firefox/persona/94252',
);

$config['popular_theme_ids'] = array(
);
