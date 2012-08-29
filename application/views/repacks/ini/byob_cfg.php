<?php
$r = $repack;
?>
//
try {
<?php if ($r->{'sync'} == 'disable'): ?>
lockPref("services.sync.clusterURL", "");
lockPref("services.sync.autoconnect",false);
lockPref("services.sync.account", "");
lockPref("services.sync.username", "");
<?php elseif ($r->{'sync'} == 'alternate'): ?>
lockPref("services.sync.clusterURL", "<?= $r->alt_server ?>");
<?php endif ?>
<?php if ($r->{'lock_proxy'}): ?>
lockPref("network.proxy.type", <?= $r->proxy_setting ?>);
<?php if ($r->{'http_proxy'}): ?>
lockPref("network.proxy.http", "<?= $r->http_proxy ?>");
lockPref("network.proxy.http_port", <?= $r->http_proxy_port ?>);
<?php endif ?>
<?php if ($r->{'ssl_proxy'}): ?>
lockPref("network.proxy.ssl", "<?= $r->ssl_proxy ?>");
lockPref("network.proxy.ssl_port", <?= $r->ssl_proxy_port ?>);
<?php endif ?>
<?php if ($r->{'ftp_proxy'}): ?>
lockPref("network.proxy.ftp", "<?= $r->ftp_proxy ?>");
lockPref("network.proxy.ftp_port", <?= $r->ftp_proxy_port ?>);
<?php endif ?>
<?php if ($r->{'sock_host'}): ?>
lockPref("network.proxy.socks", "<?= $r->socks_host ?>");
lockPref("network.proxy.socks_port", <?= $r->socks_host_port ?>);
lockPref("network.proxy.socks_version", <?= $r->socks_version ?>);
<?php endif ?>
lockPref("network.proxy.no_proxies_on", "<?= $r->no_proxy_for_address ?>");
lockPref("network.proxy.autoconfig_url", "<?= $r->proxy_config_url ?>");
<?php endif ?>
<?php if ($r->{'lock_homepage'}): ?>
lockPref("browser.startup.homepage", "<?= $r->home_url ?>");
<?php endif ?>


<?php if ($r->{'lock_updates'}): ?>
<?php $updateAddons = (in_array('add-ons', $r->{'updates'})) ? "true" : "false"; ?>
lockPref("extensions.update.enabled", <?= $updateAddons ?>);
<?php $updateSearch = (in_array('search', $r->{'updates'})) ? "true" : "false"; ?>
lockPref("browser.search.update", <?= $updateSearch ?>);
<?php $updateFirefox = (in_array('firefox', $r->{'updates'})) ? "true" : "false"; ?>
lockPref("app.update.enabled", <?= $updateFirefox ?>);
<?php if ($r->{'update_firefox'}): ?>
lockPref("app.update.auto", <?= $r->{'update_firefox'} ?>);
lockPref("app.update.mode", <?= $r->{'notices'} ?>);
<?php endif ?> 
<?php endif ?>
<?php if ($r->{'lock_tabs'}): ?>
<?php $newtab = (in_array('new_tab', $r->{'tab_features'})) ? 3 : 2; ?>
lockPref("browser.link.open_newwindow", <?= $newtab ?>);
<?php $warnclose = (in_array('warn_close_tabs', $r->{'tab_features'})) ? "true" : "false"; ?>
lockPref("browser.tabs.warnOnClose", <?= $warnclose ?>);
<?php $warnopen = (in_array('warn_open_tabs', $r->{'tab_features'})) ? "true" : "false"; ?>
lockPref("browser.tabs.warnOnopen", <?= $warnopen ?>);
<?php $hidetabbar = (in_array('hide_tabbar', $r->{'tab_features'})) ? "true" : "false"; ?>
lockPref("browser.tabs.forceHide", <?= $hidetabbar ?>);
<?php $bkgd = (in_array('load_tab_bkgd', $r->{'tab_features'})) ? "false" : "true"; ?>
lockPref("browser.tabs.loadInBackground", <?= $bkgd ?>);
<?php $preview = (in_array('show_tab_preview', $r->{'tab_features'})) ? "true" : "false"; ?>
lockPref("browser.taskbar.previews.enable", <?= $preview ?>);
<?php $sysaddon = (in_array('sysaddon', $r->{'tab_features'})) ? 0 : 1; ?>
lockPref("extensions.autoDisableScope", <?= $sysaddon ?>);
<?php endif ?>
<?php if ($r->{'lock_webgl'}): ?>
<?php $webgl = (in_array('webgl', $r->{'webgl'})) ? "true" : "false"; ?>
lockPref("webgl.disabled", <?= $webgl ?>);
<?php endif ?>
<?php if ($r->{'lock_bookmarks'}): ?>
lockPref("browser.bookmarks.restore_default_bookmarks",true);
<?php endif ?>
<?php if ($r->{'lock_addons'}): ?>
lockPref("extensions.enabledAddons", <?= $r->{'enabledAddons'} ?>);
<?php endif ?>
} catch (e) {
    displayError("lockedPref", e);
}
