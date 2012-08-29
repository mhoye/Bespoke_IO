<?php
$r = $repack;
$dist_id = "byob-{$r->profile->screen_name}-{$r->short_name}";
$aus     = $dist_id;
$version = $repack->version;
$locales = $repack->locales;
if (in_array('ja', $locales)) {
    $locales[] = 'ja-JP-mac';
}
$locales = join(' ', $locales);

$os = array(
    'linux' => 'false',
    'win'   => 'false',
    'mac'   => 'false',
);
$oses = $repack->os;
if (!empty($oses)) foreach ($os as $os_name=>$val) {
    $os[$os_name] = in_array($os_name, $oses) ?
        'true' : 'false';
}
$lock_report_off = empty($repack->report_off) ? '1' : '0';

$defaultPrefs = $r->numLockablePrefs > 0 ? 'true' : 'false';
?>
aus="<?=$aus?>"
dist_id="<?=$dist_id?>"
dist_version="<?=$version?>"
locales="<?=$locales?>"
win32=<?=$os['win']."\n"?>
report_off=<?=$lock_report_off."\n"?>
defaultprefs=<?=$defaultPrefs?>
