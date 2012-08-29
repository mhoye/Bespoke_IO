<?php
$r = $repack;
$default_locale = empty($r->default_locale) ? 'en-US' : $r->default_locale;
$dist_id = "BeSDS-{$r->profile->screen_name}-{$r->short_name}";
$partner_id = 'BeSDS' . $r->profile->screen_name;
?>
; Partner distribution.ini file for "<?= $r->title ?>"
; Author email: <?= @$r->profile->logins[0]->email . "\n" ?>
; UUID: <?= $r->uuid . "\n" ?>

[Global]
id=<?=$dist_id ."\n" ?>
version=<?= $r->version . "\n" ?>
about=<?= $r->title . "\n" ?>

[Preferences]
app.partner.<?= $partner_id ?>=<?= $partner_id . "\n" ?>
<?php 
    $updates = $r->updates;
    if (empty($updates)) $updates = array();
    $updateAddons = (in_array('add-ons', $updates)) ? "true" : "false";
    $updateSearch = (in_array('search', $updates)) ? "true" : "false";
    $updateFirefox = (in_array('firefox', $updates)) ? "true" : "false";
    $autoUpdateFfx = $r->update_firefox;
    if (empty($autoUpdateFfx)) $autoUpdateFfx = "true";
    $updateModeFfx = $r->warning;
    if (empty($updateModeFfx)) $updateModeFfx = 1;
?>
extensions.update.enabled=<?=$updateAddons . "\n" ?>
browser.search.update=<?=$updateSearch . "\n" ?>
app.update.enabled=<?=$updateFirefox . "\n" ?>
app.update.auto=<?=$autoUpdateFfx . "\n" ?>
app.update.mode=<?=$updateModeFfx . "\n" ?>
<?php if ($r->{'sync'} == 'disable'): ?>
services.sync.clusterURL=""<?= "\n" ?>
services.sync.autoconnect=false<?= "\n"?>
<?php elseif ($r->{'sync'} == 'alternate'): ?>
services.sync.clusterURL=<?=$r->alt_server . "\n" ?>
<?php endif ?>
<?php
  $tabs = $r->tab_features;
  if (empty($tabs)) $tabs = array();

  $newtab = (in_array('new_tab', $tabs)) ? 3 : 2;
  $warnclose = (in_array('warn_close_tabs', $tabs)) ? "true" : "false";
  $warnopen = (in_array('warn_open_tabs', $tabs)) ? "true" : "false";
  $hidetabbar = (in_array('hide_tabbar', $tabs)) ? "true" : "false";
  $bkgd = (in_array('load_tab_bkgd', $tabs)) ? "false" : "true";
  $preview = (in_array('show_tab_preview', $tabs)) ? "true" : "false";
  $sysaddon = (in_array('sysaddon', $tabs)) ? 0 : 1;

	$webgl = $r->webgl;
	if (empty($webgl)) $webgl = array();
?>
browser.link.open_newwindow=<?=$newtab . "\n" ?>
browser.tabs.warnOnClose=<?=$warnclose . "\n" ?>
browser.tabs.warnOnOpen=<?=$warnopen . "\n" ?>
browser.tabs.forceHide=<?=$hidetabbar . "\n" ?>
browser.tabs.loadInBackground=<?=$bkgd . "\n" ?>
browser.taskbar.previews.enable=<?=$preview ."\n"?>
extensions.autoDisableScope=<?=$sysaddon."\n"?>
webgl.disabled=<?= (in_array('webgl', $webgl)? "true" : "false") . "\n"?>
 
<?php
$bookmarks = $r->bookmarks;
?>
<?php foreach (array('menu', 'toolbar') as $kind): ?>
<?php if (!empty($bookmarks[$kind]) && !empty($bookmarks[$kind]['items'])): ?>
<?php 
    $r->default_locale = $r->getDefaultLocale();
    foreach ($r->locales as $locale) {
        $locale_suff = ($locale == $r->default_locale) ? '' : '.'.$locale;
        if (empty($bookmarks[$kind]['items'.$locale_suff])) { continue; }
        View::factory('repacks/ini/bookmarks', array(
            'set_id' => ucfirst($kind), 
            'bookmarks' => $bookmarks[$kind]['items'.$locale_suff],
            'repack' => $repack,
            'locale' => $locale,
        ))->render(TRUE); 
    }
?>
<?php endif ?>
<?php endforeach ?>
<?php
$user_proxy = $r->proxy_setting;
?>
<?php if ($user_proxy !== 0): ?>
network.proxy.type=<?= $user_proxy . "\n" ?>
network.proxy.http=<?= $r->http_proxy . "\n" ?>
network.proxy.http_port=<?= $r->http_proxy_port . "\n" ?>
network.proxy.ssl=<?= $r->ssl_proxy . "\n" ?>
network.proxy.ssl_port=<?= $r->ssl_proxy_port . "\n" ?>
network.proxy.ftp=<?= $r->ftp_proxy . "\n" ?>
network.proxy.ftp_port=<?= $r->ftp_proxy_port . "\n" ?>
network.proxy.socks=<?= $r->socks_host . "\n" ?>
network.proxy.socks_port=<?= $r->socks_host_port . "\n" ?>
network.proxy.socks_version=<?= $r->socks_version . "\n" ?>
network.proxy.no_proxies_on=<?= $r->no_proxy_for_address . "\n" ?>
network.proxy.autoconfig_url=<?= $r->proxy_config_url . "\n" ?>
<?php endif ?>
<?php if ($r->ntlm_uris): ?>
network.automatic-ntlm-auth.trusted-uris=<?= $r->ntlm_uris . "\n"?>
<?php endif?>
<?php
    $homepage = $r->home_url;
?>
<?php if ($homepage): ?>
startup.homepage_welcome_url=<?= $homepage . "\n" ?>
startup.homepage_override_url=<?= $homepage . "\n" ?>
browser.startup.homepage=<?= $homepage . "\n" ?>
<?php endif ?>


[LocalizablePreferences]
# <? # do not edit this line, or add newlines after it ?>
