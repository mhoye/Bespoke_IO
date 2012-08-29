<?php
/**
 * Configuration for the repack process.
 */
$base_path = dirname(APPPATH);

$config['enable_builds'] = TRUE;
$config['enable_notifications'] = FALSE;;

$config['assets'] = "$base_path/repack_assets";

$config['workspace'] = "$base_path/workspace";

$config['repack_script'] = "$base_path/bin/partner-repacks.py";

$config['cfg_prefs_script'] = "$base_path/bin/moz-byteshift.pl";

$config['downloads_private'] = "$base_path/downloads/private";
$config['downloads_public']  = "$base_path/downloads/public";
