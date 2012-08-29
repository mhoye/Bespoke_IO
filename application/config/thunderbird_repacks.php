<?php
/** 
 * Configuration for the repack process.
 */

$base_path = dirname(APPPATH);

$config['repack_script'] = "$base_path/bin/thunderbird-partner-repacks.py";

$config['downloads_private'] = "$base_path/downloads/thunderbird_private";
$config['downloads_public'] = "$base_path/downloads/thunderbird_public";
