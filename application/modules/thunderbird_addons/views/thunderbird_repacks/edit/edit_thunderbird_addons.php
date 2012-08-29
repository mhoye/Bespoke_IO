<?php
$default_locale = empty($repack->default_locale) ? 
    'en-US' : $repack->default_locale;
$has_locales = (!empty($repack->locales) && count($repack->locales)>1);

$unlimited_addons = $repack->checkPrivilege('thunderbird_addons_unlimited');

$popular_extensions    = thunderbird_addons::get_popular_extensions();
$popular_personas      = thunderbird_addons::get_popular_personas();
$popular_themes        = thunderbird_addons::get_popular_themes();
$popular_searchplugins = thunderbird_addons::get_popular_searchplugins();

$selected_extension_ids = 
    empty($repack->managed_addons['extension_ids']) ?
        array() : $repack->managed_addons['extension_ids'];

// HACK: Before locale support, there was no locale:filename prefix, so pretend 
// there is one if it's missing (ie. en-US)
$tmp = empty($repack->managed_addons['search_plugin_filenames']) ?
    array() : $repack->managed_addons['search_plugin_filenames'];
$selected_search_plugin_filenames = array();
foreach ($tmp as $fn) {
    if (strpos($fn, ':') === FALSE) $fn = "en-US:{$fn}";
    $selected_search_plugin_filenames[] = $fn;
}

$selected_theme_id = 
    empty($repack->managed_addons['theme_id']) ?
        '' : $repack->managed_addons['theme_id'];

$selected_persona_url = 
    empty($repack->managed_addons['persona_url']) ?
        '' : $repack->managed_addons['persona_url'];

$selected_persona_url_hash = md5($selected_persona_url);
?>

<?php slot::start('head_end') ?>
    <?=html::stylesheet(array('application/modules/thunderbird_addons/public/css/thunderbird_addons.css'))?>
<?php slot::end() ?>

<?php slot::start('body_end') ?>
    <?=html::script(array(
        'application/modules/thunderbird_addons/public/js/thunderbird_addons.js',
    ))?>
    <script type="text/javascript">
        <?php if ($unlimited_addons): ?>
            BYOB_Repacks_Edit_ThunderbirdAddons.max_extensions = false; 
            BYOB_Repacks_Edit_ThunderbirdAddons.max_search_plugins = false; 
        <?php else: ?>
            BYOB_Repacks_Edit_ThunderbirdAddons.max_extensions = 
                <?= Mozilla_BYOB_Editor_ThunderbirdAddons::$max_extensions ?>; 
            BYOB_Repacks_Edit_ThunderbirdAddons.max_search_plugins = 
                <?= Mozilla_BYOB_Editor_ThunderbirdAddons::$max_search_plugins ?>; 
            BYOB_Repacks_Edit_ThunderbirdAddons.default_locale =
            BYOB_Repacks_Edit_ThunderbirdAddons.current_locale =
                "<?= $default_locale ?>";
            BYOB_Repacks_Edit_ThunderbirdAddons.locales = 
                <?= json_encode($repack->locales); ?>
        <?php endif ?>
    </script>
<?php slot::end() ?>

<div class="intro">
    <p><?=_('With BeSDS, you can bundle preexisting add-ons with your browser. We have selected some popular options below. Please bear in mind that these are third-party addons, and are not vetted or endorsed by Bespoke I/O.')?></p>
</div>
<div class="pane pane-thunderbird-addons">
    <div class="selections">
        <fieldset><legend><?=_('Selected add-ons:')?></legend>
            <ul class="thunderbird-addon-selections clearfix">
                <li class="template" data-selection-index="">
                    <a href="#" class="remove_link">
                        <span class="name"></span><span class="remove"><?=_('Remove')?></span>
                    </a>
                </li>
            </ul>
        </fieldset>
    </div>

    <div class="choices">
        <div class="sub-tab-set">
            <ul class="sub-tabs">
                <li class="selected"><a href="#tab-extensions"><?=_('Extensions')?></a></li>
                <li><a href="#tab-searchengines"><?=_('Search Engines')?></a></li>
                <li><a href="#tab-personas"><?=_('Personas')?></a></li>
                <li><a href="#tab-themes"><?=_('Themes')?></a></li>
            </ul>

            <div class="sub-tab-content selected" id="tab-extensions">
                <fieldset><legend><?=_('Choose from these popular add-ons')?>
                    <?= ($unlimited_addons) ? ':' : sprintf(_('(maximum of %1$s)'), Mozilla_BYOB_Editor_ThunderbirdAddons::$max_extensions) ?></legend>
                    <ul class="extensions"><?php foreach ($popular_extensions as $id=>$addon): ?>
                        <?php
                            $e = html::escape_array(array(
                                'id'        => $addon->id,
                                'icon'      => $addon->icon,
                                'version'   => $addon->version,
                                'name'      => $addon->name,
                                'summary'   => $addon->summary,
                                'thumbnail' => $addon->thumbnail,
                                'learnmore' => $addon->learnmore,
                            ));
                            $selected = in_array($addon->id, $selected_extension_ids);
                        ?>
                        <li>
                            <input class="checkbox" type="checkbox" id="extension_ids-<?=$id?>" 
                                name="extension_ids[]" value="<?=$e['id']?>"
                                <?=($selected)?'checked="checked"':''?> />
                            <label class="icon" for="extension_ids-<?=$id?>">
                                <img src="<?=$e['icon']?>" alt="<?=$e['name']?>" 
                                    width="32" height="32" />
                            </label>
                            <div class="meta">
                                <span class="name"><?=$e['name']?></span>
                                <span class="version"><?=$e['version']?></span>
                                <p class="summary"><?=$e['summary']?></p>
                                <a target="new" href="<?=$e['learnmore']?>" class="learn"><?=_('Learn more...')?></a>
                            </div>
                        </li>
                    <?php endforeach ?>
                    </ul>
                </fieldset>
            </div>
            <div class="sub-tab-content" id="tab-searchengines">
                <?php if ($has_locales): ?>
                    <div class="locale-selector">
                        <ul class="locales clearfix">
                            <?php foreach ($repack->locales as $locale): ?>
                                <?php $selected = ( $locale == $default_locale ) ?>
                                <li class="<?= $selected ? 'selected' : '' ?>">
                                    <a href="#" data-locale="<?=$locale?>"><?=$locale?></a>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif ?>
                <fieldset><legend><?=_('Choose from these popular search engines')?>
                    <?= ($unlimited_addons) ? ':' : sprintf(_('(maximum of %1$s)'), Mozilla_BYOB_Editor_ThunderbirdAddons::$max_search_plugins) ?></legend>
                    <ul class="searchplugins available-options">
                    <?php foreach ($repack->locales as $locale): ?>
                        <?php $search_plugins = $popular_searchplugins[$locale]; ?>
                        <?php foreach ($search_plugins as $fn=>$plugin): ?>
                            <?php
                                $choice = "{$locale}:{$fn}";
                                $selected = in_array($choice, $selected_search_plugin_filenames);
                                $e = html::escape_array(array(
                                    'id' => 'search_plugin_filenames-'.md5($choice),
                                    'choice'    => $choice,
                                    'filename'  => $fn,
                                    'icon'      => $plugin->getIconUrl(),
                                    'name'      => $plugin->ShortName,
                                    'summary'   => $plugin->Description,
                                ));
                            ?>
                            <li class="by-locale locale-<?=$locale?>">
                                <input class="checkbox" type="checkbox" id="<?=$e['id']?>" 
                                    name="search_plugin_filenames[]" value="<?=$e['choice']?>" 
                                    <?=($selected)?'checked="checked"':''?> />
                                <label class="icon" for="<?=$e['id']?>">
                                    <img src="<?=$e['icon']?>" alt="<?=$e['name']?> (<?=$locale?>)" 
                                        width="16" height="16" />
                                </label>
                                <label class="meta" for="<?=$e['id']?>">
                                <span class="name"><?=$e['name']?> (<?=$locale?>)</span>
                                </label>
                            </li>
                        <?php endforeach ?>
                    <?php endforeach ?>
                    </ul>
                </fieldset>
            </div>
            <div class="sub-tab-content" id="tab-personas">
                <fieldset class="divider"><legend><?=_('Enter the URL of a Persona to include:')?></legend>
                    <input type="text" name="persona_url" class="persona_url text"
                        value="<?=(empty($popular_personas[md5($selected_persona_url)])) ? $selected_persona_url : '' ?>" />
                </fieldset>

                <fieldset><legend><?=_('Choose from these popular Personas:')?></legend>
                    <ul class="personas">
                        <li class="none">
                            <input type="radio" name="persona_url_hash" value="" 
                                id="persona_id_none" 
                                <?=empty($selected_persona_url)?'checked="checked"':''?> />
                            <label for="persona_id_none" class="none"><?=_('No Persona')?></label>
                        </li>
                        <?php foreach ($popular_personas as $url_hash=>$persona): ?>
                            <?php
                                $e = html::escape_array(array(
                                    'id'          => $persona->id,
                                    'url'         => $persona->url,
                                    'name'        => $persona->name,
                                    'description' => $persona->description,
                                    'iconURL'     => $persona->iconURL,
                                    'previewURL'  => $persona->previewURL,
                                ));
                                $selected = ($url_hash == $selected_persona_url_hash);
                            ?>
                            <li>
                                <input type="radio" name="persona_url_hash" value="<?=$url_hash?>" 
                                    id="persona_id_<?=$url_hash?>" 
                                    <?=($selected)?'checked="checked"':''?> />
                                <label for="persona_id_<?=$url_hash?>">
                                    <img src="<?=$e['previewURL']?>" alt="<?=$e['name']?>" />
                                    <span class="name"><?=$e['name']?></span>
                                </label>
                            </li>
                        <?php endforeach ?></ul>
                </fieldset>
            </div>
            <div class="sub-tab-content" id="tab-themes">
                <fieldset>
                    <ul class="themes">
                        <li class="none">
                            <input type="radio" name="theme_id" id="theme_id_none" value="" 
                                <?= empty($selected_theme_id) ? 'checked="checked"' : '' ?> />
                            <label for="theme_id_none" class="none"><?=_('Use the default theme')?></label>
                        </li>
                        <?php foreach ($popular_themes as $idx => $addon): ?>
                            <?php
                                $e = html::escape_array(array(
                                    'id'        => $addon->id,
                                    'icon'      => $addon->icon,
                                    'name'      => $addon->name,
                                    'summary'   => $addon->summary,
                                    'thumbnail' => $addon->thumbnail,
                                    'learnmore' => $addon->learnmore,
                                ));
                                $selected = ($addon->id == $selected_theme_id);
                            ?>
                            <li>
                                <input type="radio" name="theme_id" value="<?=$e['id']?>" 
                                    id="theme_id_<?=$idx?>" 
                                    <?=($selected)?'checked="checked"':''?> />
                                <label class="meta" for="theme_id_<?=$idx?>">
                                    <span class="name"><?=$e['name']?></span>
                                    <!--<a target="new" href="<?=$e['learnmore']?>">More info</a>-->
                                </label>
                                <label class="icon" for="theme_id_<?=$idx?>">
                                    <img src="<?=$e['thumbnail']?>" alt="<?=$e['name']?>" />
                                    <a target="new" href="<?=$e['learnmore']?>"><?=_('More info')?></a>
                                </label>
                            </li>
                        <?php endforeach ?></ul>
                </fieldset>
            </div>
        </div>
    </div>
</div>
