#!/usr/bin/env php
<?php

define('JSONDIR', dirname(__FILE__).'/json/');

/**
 * write a JSON file
 */
function writefile($filename, $data) {
    file_put_contents(JSONDIR.$filename, json_encode($data));
}

/** Locale Details */
require_once('localeDetails.class.php');
$ld = new localeDetails();
writefile('languages.json', $ld->languages);

/** Product Details */
// Firefox Versions
require_once('firefoxDetails.class.php');
$fx_constants = array('LATEST_FIREFOX_VERSION',
                      'LATEST_FIREFOX_DEVEL_VERSION',
                      'LATEST_FIREFOX_RELEASED_DEVEL_VERSION',
                      'LATEST_FIREFOX_OLDER_VERSION',
                      );
$versiondata = array();
foreach ($fx_constants as $fx_c) {
    $versiondata[$fx_c] = constant($fx_c);
}
writefile('firefox_versions.json', $versiondata);

// Firefox Primary Builds
$fxd = new firefoxDetails();
$builds = array('primary_builds', 'beta_builds');
foreach ($builds as $build) {
    writefile("firefox_{$build}.json", $fxd->$build);
}

// Firefox History
require_once('history/firefoxHistory.class.php');
$fxh = new firefoxHistory();
$releases = array('major_releases', 'stability_releases', 'development_releases');
foreach ($releases as $release) {
    writefile("firefox_history_{$release}.json", $fxh->$release);
}

// Thunderbird Versions
require_once('thunderbirdDetails.class.php');
$tb_constants = array('LATEST_THUNDERBIRD_VERSION',
                      'LATEST_THUNDERBIRD__OLDER_VERSION',
                      );
$versiondata = array();
foreach ($tb_constants as $tb_c) {
    $versiondata[$tb_c] = constant($tb_c);
}
writefile('thunderbird_versions.json', $versiondata);

// Firefox Primary Builds
$tbd = new thunderbirdDetails();
$builds = array('primary_builds', 'beta_builds');
foreach ($builds as $build) {
    writefile("thunderbird_{$build}.json", $tbd->$build);
}

// Thunderbird History
require_once('history/thunderbirdHistory.class.php');
$tbh = new thunderbirdHistory();
$releases = array('major_releases', 'stability_releases', 'development_releases');
foreach ($releases as $release) {
    writefile("thunderbird_history_{$release}.json", $tbh->$release);
}

// Mobile Details
require_once 'mobileDetails.class.php';
$mobile = array(
    'version' => mobileDetails::latest_version,
    'beta_version' => mobileDetails::beta_version,
    'builds'  => mobileDetails::primary_builds(false),
    'beta_builds' => mobileDetails::beta_builds(false),
);
writefile('mobile_details.json', $mobile);

// Mobile History
require_once('history/mobileHistory.class.php');
$mobh = new mobileHistory();
$releases = array('major_releases', 'stability_releases', 'development_releases');
foreach ($releases as $release) {
    writefile("mobile_history_{$release}.json", $mobh->$release);
}
