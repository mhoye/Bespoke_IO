<?php defined('SYSPATH') OR die('No direct access allowed.');

$config['profiles/([^/]+)/browsers/([^;/]+)/?([^/]*)/thunderbird_daddons;(.*)'] =
    'thunderbirdaddons/$4/screen_name/$1/short_name/$2/status/$3';

