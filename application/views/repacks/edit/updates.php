<div class="intro">
    <p><?=_('You can select which updates you want your browser to check for; leave these options disabled if your users do not have permission to manage their own computers. Bespoke I/O recommends leaving these options disabled in favor of pushing an updated .MSI as necessary.')?></p>
    <p><?=_('You also have the option of enabling crash reporting.')?></p>
</div>
<?php
/* Lock the ability to update or not update ffx, addons, or plugins */
$updatesLock = form::value('lock_updates');
if (empty($updatesLock)) $updatesLock = array();

/* Firefox, Add-ons, and/or Search Engine */
$updates_chosen = form::value('updates');
if (empty($updates_chosen)) $updates_chosen = array();

/* What to do when updates to Firefox are found */
$automatic_update = form::value('update_firefox');
/* What notices should appear if an update is available */
$updates_notices = form::value('notices');

$crash_report = form::value('report_off');
if (empty($crash_report)) $crash_report = array();

?>

<div class="pane">
	<div>
		<fieldset>
			<div>
        <div class="title">Updates</div>
				  <ul class="lock_pref">
            <?php foreach (Repack_Model::$lockablePrefs as $name=>$label): ?>  
              <?php if ($name == 'updates'): ?>
                <li class="lock">
                  <?= form::checkbox('lock_updates[]', $name, (in_array($name, $updatesLock)) ? true : false) ?>
                  <?=_('<acronym title="Users will not be able to modify these settings after the browser is deployed"><b>Lock these settings in the browser</b></acronym>')?>
                </li>
              <?php endif ?>
            <?php endforeach ?>
          </ul>
          <ul class="update_choices">
            <?php foreach (Repack_Model::$update_choices as $name=>$label): ?>
              <li>
                <?= form::checkbox("updates[]", $name, in_array($name, $updates_chosen)) ?>
                <?= html::specialchars($label) ?>
              </li>
            <?php endforeach ?>
          </ul>
        </div>
      </fieldset>
      <fieldset>
        <div class="notice">
          <p><?=_('When updates to Firefox are found: ')?></p>
        </div>
        <div>
          <ul class="update_choices">
            <li>
              <?= form::radio('update_firefox', "false", ($automatic_update === "false" ? true : false)) ?>
              <?=_('Ask the user what they wants to do')?>
            </li>
            <li>
              <?= form::radio('update_firefox', "true", 
                  (( $automatic_update === "true" || empty($automatic_update)) ? true  : false)) ?>
              <?=_('Automatically download and install the update')?>
            </li>
            <ul>
              <li class="warn">
                <?= form::radio('notices', "0", !($updates_notices === "0") ? true : false) ?>
                <?=_('Download all updates without any notifications')?>
              </li>
              <li class="warn">
                <?= form::radio('notices', "1", ($updates_notices === "1") ? true : false) ?>
                <?=_('Warn the end users if updates will disable any add-ons')?>
              </li>
              <li class="warn">
                <?= form::radio('notices', "2", ($updates_notices === "2") ? true : false) ?>
                <?=_('Download minor updates only; prompt the user for major-version updates')?>
              </li>
            </ul>
          </ul>
        </div>
      </fieldset>
      <div class="divider"><hr/></div>
      <fieldset>
        <div>
          <div class="title">Crash Reporting</div>
            <ul>
              <li class="update_choices">
                <?= form::checkbox('report_off[]', 'report_off', (in_array('report_off', $crash_report)) ? true : false ) ?>
                <?=_('Enable crash reporting')?> 
              </li>
            </ul>
          </div>
      </fieldset>
    </div>
</div>
