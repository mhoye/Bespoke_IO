<?php
$r = $repack;
$dist_id = "byob-{$r->profile->screen_name}-{$r->short_name}";
$aus     = $dist_id;
$version = $repack->version;
//$locales = $repack->locales;
//if (in_array('ja', $locales)) {
//    $locales[] = 'ja-JP-mac';
//}
$locales = array('en-US');
$locales = join(' ', $locales);

$os = array(
    'win'   => 'false',
);
//$oses = $repack->os;
$oses = array('win');
if (!empty($oses)) foreach ($os as $os_name=>$val) {
    $os[$os_name] = in_array($os_name, $oses) ?
        'true' : 'false';
}
?>
aus="<?=$aus?>"
dist_id="<?=$dist_id?>"
dist_version="<?=$version?>"
locales="<?=$locales?>"
win32=<?=$os['win']."\n"?>
