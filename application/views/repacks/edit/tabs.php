<div class="intro">
    <p><?=_('You can preselect tab behaviour such as opening new tabs instead of new windows and warning the user when multiple tabs are about to be closed.')?>
</div>

<?php 
    /* Lock the ability to lock the following tab features offered */
    $tabs = form::value('lock_tabs');
    if (empty($tabs)) $tabs = array();
    
    /* Tab features */
    $features = form::value('tab_features');
    if (empty($features)) $features = array();

	/* WebGL */
	$lock_webgl = form::value('lock_webgl');
	if (empty($lock_webgl)) $lock_webgl = array();

	$webgl = form::value('webgl');
	if (empty($webgl)) $webgl = array();
?>

<div class="pane">
	<div>
		<fieldset>
				<div class="title">Tabs</div>
					<ul class="lock_pref">
						<?php foreach (Repack_Model::$lockablePrefs as $name => $label): ?>
						<?php if ($name == 'tabs'): ?>
						<li>
							<?= form::checkbox('lock_tabs[]', $name, in_array($name, $tabs))?>
							<?=_('<acronym title="End users will not be able to change this preference once the browser is deployed"><b>Lock the tab settings in the browser</b></acronym>')?> 
						</li>
						<?php endif ?>
						<?php endforeach ?>
					</ul>
					<ul>
						<?php foreach (Repack_Model::$tab_features as $name=>$label): ?>
						<li>
							<?= form::checkbox('tab_features[]', $name, in_array($name, $features)) ?>
							<?= html::specialchars($label) ?>
						</li>
						<?php endforeach ?>
					</ul>
		</fieldset>
		<div class="divider"><hr/></div>
		<fieldset>
        <div class="title">WebGL</div>
        <ul class="lock_pref">
          <li>
            <?= form::checkbox('lock_webgl[]', 'lock_webgl', in_array('lock_webgl', $lock_webgl)) ?>
            <?=_('<acronym title="End users will not be able to change this preference once the browser is deployed"><b>Lock the WebGL settings in the browser</b></acronym>')?>
          </li>
        </ul>
        <ul>
          <li class="update_choices">
          <?= form::checkbox('webgl[]', 'webgl', in_array('webgl', $webgl) ? true : false )?>
          <?=_('Disable WebGL')?>
          </li>
        </ul>
		</fieldset>
	</div>
</div>
