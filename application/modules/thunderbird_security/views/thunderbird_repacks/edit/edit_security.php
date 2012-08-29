<div class="intro">
    <p><?=_('On this page, you can set security settings such as what Thunderbird should do with junk mail, warnings regarding email scams, and which sites to accept cookies from.')?>
</div>

<?php 
    // Ability to lock the following security features offered
    $security = form::value('lock_security');
    if (empty($security)) $security = array();
   
    // General junk mail prefs
    $junkmail_settings = form::value('junkmail_settings');
    if (empty($junkmail_settings)) $junkmail_settings = array();
    
    // What to do with messages marked as junk
    $markjunk_delete = form::value('markjunk_delete');

    // What to do about email scams
    $phishing_detection = form::value('phishing_detection');
    if (empty($phishing_detection)) $phishing_detection = array();
    
    // Quarantine or do not quarantine incoming messages
    $quarantine = form::value('antivirus');
    if (empty($quarantine)) $quarantine = array();
    
	// Aceept cookies from sites
    $cookies = form::value('allow_cookies');
    if (empty($cookies)) $cookies = array();

	//
	$cookies_death = form::value('cookies_deathdate');
?>

<div class="pane">
    <fieldset>
        <div class="title">Security</div>

        <div>
		<ul class="lock_pref">
			<li>
				<?= form::checkbox('lock_security[]', 'security', in_array('security', $security))?>
				<?=_('<acronym title="End users will not be able to change this preference once the browser is deployed"><b>Lock the security settings for Thunderbird</b></acronym>')?> 
			</li>
		</ul>
        </div>
        <div class="title">Junk mail settings</div>
		<ul>
			<li>
				<?= form::checkbox("junkmail_settings[]", 'markJunk', in_array('markJunk', $junkmail_settings)) ?>
				<?= html::specialchars("When I mark messages as junk:") ?>
			</li>
			<ul class="update_choices">
				<li>
					<?= form::radio("markjunk_delete", "0", !($markjunk_delete == "0") ? true : false) ?>
					<?= html::specialchars("Move them to the account's \"Junk\" folder") ?>
				</li>
				<li>
					<?= form::radio("markjunk_delete", "1", ($markjunk_delete == "1") ? true : false) ?>
					<?= html::specialchars("Delete them") ?>
				</li>
			</ul>
			<li>
				<?= form::checkbox("junkmail_settings[]", 'junkAsRead', in_array('junkAsRead', $junkmail_settings)) ?>
				<?= html::specialchars("Mark messages determined to be Junk as read") ?>
			</li>
			<li>
				<?= form::checkbox("junkmail_settings[]", 'junkFilter', in_array('junkFilter', $junkmail_settings)) ?>
				<?= html::specialchars("Enable junk filter logging") ?>
			</li>
		</ul>
		<br/>
        <div class="title">E-mail scams</div>
		<ul>
			<li>
				<?= form::checkbox("phishing_detection[]", 'phishing', in_array('phishing', $phishing_detection)) ?>
				<?= html::specialchars("Tell me if the message I'm reading is a suspected email scam") ?>
			</ul>
		</ul>
		<br/>
        <div class="title">Anti-Virus</div>
		<ul>
			<li>
				<?= form::checkbox("antivirus[]", 'quarantine', in_array('quarantine', $quarantine)) ?>
				<?= html::specialchars("Allow anti-virus clients to quarantine individual incoming messages") ?>
			</li>
		</ul>
		<br/>
        <div class="title">Web Content</div>
		<ul>
			<li>
				<?= form::checkbox("allow_cookies[]", 'cookies', in_array('cookies', $cookies)) ?>
				<?= html::specialchars("Accept cookies from sites. Keep until:") ?>
				<ul class="update_choices">
					<li>
						<?= form::radio("cookies_deathdate", "0", !($cookies_death == "0") ? true : false ) ?>
						<?= html::specialchars("they expire") ?>
					</li>
					<li>
						<?= form::radio("cookies_deathdate", "2", ($cookies_death == "2") ? true : false ) ?>
						<?= html::specialchars("user closes Thunderbird") ?>
					</li>
					<li>
						<?= form::radio("cookies_deathdate", "1", ($cookies_death == "1") ? true : false ) ?>
						<?= html::specialchars("ask user every time") ?>
					</li>
				</ul>
			</li>
		</ul>
    </fieldset>
</div>
