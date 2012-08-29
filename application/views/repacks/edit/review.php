<?php slot::set('is_popup', 'true') ?>
<?php
$edit_base = $repack->url() . ';edit?section=';
$default_locale = empty($repack->default_locale) ? 
    'en-US' : $repack->default_locale;
?>
<div class="part" id="part1">
    <div class="header">
        <h2>Review and Confirm:</h2>
    </div>
    <div class="content">
        <?php if (!$repack->isCustomized()): ?>
            <p class="warning"><?=_('You haven\'t performed any customizations to this browser beyond the default settings.  Please do so before submitting a request to build this browser.')?></p>
        <?php else: ?>
            <p><?=_('Please review your customizations detailed below before submitting your browser for build and approval:')?></p>
        <?php endif ?>

        <ul class="sections">
            <!--LOCALES AND ADDONS -->
            <?php
                /** Allow all modules to perform section rendering into a slot */
                $ev_data = array(
                    'repack' => $repack
                );
                Event::run('BYOB.repack.edit.review.renderSections', $ev_data);
                slot::output('BYOB.repack.edit.review.sections');
            ?>
            
            <!-- BOOKMARKS -->
            <li class="section bookmarks clearfix">
                <h3><?=_('Bookmarks')?> <a target="_top" href="<?=$edit_base?>bookmarks"><?=_('edit')?></a></h3>
                <?php
                    $bookmarks = $repack->bookmarks; 
                    $none = true;
                ?>
                <ul>
                    <?php foreach ($repack->getLocalesWithLabels() as $locale=>$locale_name): ?>
                        <?php 
                            $items_name = ($default_locale == $locale) ?
                                'items' : "items.{$locale}";
                            if (empty($bookmarks['toolbar'][$items_name]) &&
                                empty($bookmarks['menu'][$items_name])) continue;
                            $none = false;
                        ?>
                        <li><span class="locale_name"><?=html::specialchars($locale_name)?></span>
                            <ul>
                                <?php foreach (array('toolbar', 'menu') as $kind): ?>
                                    <?php if (!empty($bookmarks[$kind])): ?>
                                        <?php 
                                            $bookmarks[$kind]['type'] = 'folder';
                                            View::factory('repacks/edit/review_bookmarks', array(
                                                'repack' => $repack,
                                                'bookmark' => $bookmarks[$kind],
                                                'locale' => $locale,
                                                'default_locale' => $default_locale,
                                            ))->render(TRUE); 
                                        ?>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </ul>
                        </li>
                    <?php endforeach ?>
                    <?php if ($none): ?>
                        <li class="empty"><?=_('None.')?></li>
                    <?php endif ?>
                </ul>
            </li>

            <!-- HOMEPAGE -->
            <li class="section homepage">
            	<h3><?=_('Homepage')?><a target="_top" href="<?=$edit_base?>homepage"><?=_('edit')?></a></h3>
            		<?php if (empty($repack->home_url)): ?>
            		<?=_('None.')?>
            		<?php else: ?>
            			<a href="<?=html::specialchars($repack->home_url)?>" 
                            target="_new"><?=html::specialchars($repack->home_url)?></a>
                    <?php endif ?>
            </li>

            <!-- TABS -->
            <li class="section tabs">
            	<h3><?=_('Tabs')?><a target="_top" href="<?=$edit_base?>tabs"><?=_('edit')?></a></h3>
                <?php
                    $tab_options = array();
                    $tab_options = $repack->tab_features;
                ?>
                <ul>
                    <?php if (empty($tab_options)): ?>
                        <li><?=_('No tab features were selected')?></li>
                    <?php else: ?>
                        <?php foreach ($tab_options as $t): ?>
                            <?php if ($t == "new_tab") 
                                $t = "Open new windows in a new tab instead"?>
                            <?php if ($t == "warn_close_tabs") 
                                $t = "Warn me when closing multiple tabs"?>
                            <?php if ($t == "warn_open_tabs") 
                                $t = "Warn me when opening multiple tabs might slow down Firefox"?>
                            <?php if ($t == "hide_tabbar") 
                                $t = "Always hide the tab bar"?>
                            <?php if ($t == "load_tab_bkgd") 
                                $t = "When i open a link in a new tab, switch to it immediately"?>
                            <?php if ($t == "show_tab_preview") 
                                $t = "Show tab previews in the Windows taskbar"?>
                            <?php if ($t == "sysaddon")
                                $t = "Disable addon notifications"?>
                            <li><?= html::specialchars($t) ?></li> 
                        <?php endforeach ?>
                    <?php endif ?>
                </ul>
            </li>

            <!-- PROXY SETTINGS-->
            <li class="section proxy">
                <h3><?=_('Proxy Settings')?><a target="_top" href="<?=$edit_base?>proxy"><?=_('edit')?></a></h3>
                <?php $selected_proxy = $repack->proxy_setting;

                $proxy_text = "";
                switch ($selected_proxy) {
                    case 0: $proxy_text = 'No proxy'; break;
                    case 1: $proxy_text = 'Manual proxy configuration'; break;
                    case 2: $proxy_text = 'Automatic proxy configuration URL'; break;
                    case 4: $proxy_text = 'Auto-detect proxy settings for this network'; break;
                    case 5: $proxy_text = 'Use system proxy settings';
                }	
                ?>
                <?=_($proxy_text)?>
            </li>

            <!-- UPDATES -->
            <li class="section updates">
                <h3><?=_('Updates')?><a target="_top" href="<?=$edit_base?>updates"><?=_('edit')?></a></h3>
                <?php
                    $updates_chosen = array();
                    $updates_chosen = $repack->updates;
                ?>
                <ul>
                    <?php if (empty($updates_chosen)): ?>
                        <li><?=_('No updates to check')?></li>
                    <?php else: ?>
                        <?php foreach ($updates_chosen as $u): ?>
                            <?php if ($u == "add-ons") $u = "Add-ons"; ?>
                            <?php if ($u == "search") $u = "Search Engines"; ?>
                            <?php if ($u == "firefox") $u = "Firefox"; ?>
                                <li><?= html::specialchars($u) ?></li>        
                        <?php endforeach ?>
                        <?php if (in_array('firefox', $updates_chosen)): ?>
                            <ul>
                                <?php if ($repack->update_firefox === "false"): ?>
                                <li><?=_('Ask user what to do when there are Firefox updates') ?></li>
                                <?php else: ?>
                                    <?php if ($repack->warning === '0'): ?>
                                        <li><?=_('Download all Firefox updates without any prompting')?>
                                    <?php elseif ($repack->warning === '1'): ?>
                                        <li><?=_('Warn user if Firefox update is incompatible with enabled add-ons')?></li>
                                    <?php elseif ($repack->warning === '2'): ?>
                                        <li><?=_('Download minor updates only; ask user about major updates')?></li>
                                    <?php endif ?>
                                <?php endif ?> 
                            </ul>
                        <?php endif ?>
                    <?php endif ?>
                </ul>
            </li>

            <!-- NTLM AUTHENTICATION -->
            <li class="section ntlm">
                <h3><?=_('NTLM Authentication')?><a target="_top" href="<?=$edit_base?>ntlm"><?=_('edit')?></a></h3>
                <p><?= html::specialchars($repack->ntlm_uris)?></p>
            </li>

            <!-- SYNC -->
            <li class="section sync">
                <h3><?=_('Sync')?><a target="_top" href="<?=$edit_base?>updates"><?=_('edit')?></a></h3>
                <ul>
                    <?php 
                        if ($repack->sync == 'disable')
                            $choice = "Disable the sync server";
                        else if ($repack->sync == 'alternate')
                            $choice = "Use custom sync server: ". $repack->alt_server;
                        else
                            $choice = "Use default Mozilla server";
                    ?>
                    <li><?= html::specialchars($choice) ?></li>
                </ul>
            </li>
        <ul>
    </div>
    <div class="nav">
        <div class="prev button blue"><a class="popup_cancel" href="#"><?=_('&laquo;&nbsp; Cancel')?></a></div>
        <?php if ($repack->isCustomized()): ?>
        <div class="build button yellow"><a target="_top" href="<?=$repack->url()?>;release"><?=_('Build this browser')?></a></div>
        <?php endif ?>
    </div>
</div>
