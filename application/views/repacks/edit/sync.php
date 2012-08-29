<div class="intro">
    <p><?=sprintf(_('The Sync service lets a Firefox user synchronize their browser state across several devices. It relies on Mozilla\'s public encrypted sync service,  or an alternative server of your choice. You may choose to disable the Sync feature here or specify any private server to use; you must select one of these options to continue.'))?></p>
<p><?=sprintf(_('Hosted private sync servers in SAS70- and CICA5970-compliant environments are available from Bespoke I/O. If you\'re interested, please contact us!'))?></p>
</div>
<div class="pane">
    <fieldset>
        <div>
            <?php 
                $syncLock = form::value('lock_sync');
                if (empty($syncLock)) $syncLock = array();

                $syncSetting = form::value('sync');
                if (empty($syncSetting)) $syncSetting = 'disable';
            ?>

            <ul>
                <?php foreach (Repack_Model::$lockablePrefs as $name=>$label): ?> 
                    <?php if ($name == 'sync'): ?>
                        <li>
                            <?= form::checkbox('lock_sync[]', $name, (in_array($name, $syncLock)) ? true : false ) ?>
                            <?=_('<acronym title="End users will not be able to change this preference once the browser is deployed"><b>Lock this setting in the browser</b></acronym>')?>
                        </li>
                    <?php endif?>
                <?php endforeach ?>

                <li>
                    <?= form::radio('sync', 'disable', ($syncSetting == 'disable' ) ? true : false) ?>
                    <?=_('Disable Sync')?>
                </li>
                <li>
                    <?= form::radio('sync', 'default', ($syncSetting == 'default') ? true : false) ?>
                    <?=_('Use the standard sync server, https://sync.mozilla.org') ?>
                </li>
                <li>
                    <?= form::radio('sync', 'alternate', ($syncSetting == 'alternate') ? true : false) ?>
                    <?=_('Use an alternate sync server') ?>
                </li>
                <ul id="urlform">
                    <li>
                        <?= form::input('alt_server', form::value('alt_server')) ?>
                    </li>
                </ul>
            </ul>
        </div>
    </fieldset>
</div>
