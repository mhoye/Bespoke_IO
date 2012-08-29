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
<?php if (isset($r->junkmail_settings)): ?>
<?php if (in_array('markJunk', $r->junkmail_settings)): ?>
mail.spam.manualMark=true <?= "\n" ?>
<?php $markjunk_delete = $r->markjunk_delete == "1"? '1' : '0' ?>
mail.spam.manualMarkMode=<?=$markjunk_delete?> <?= "\n" ?>
<?php endif ?>
<?php if (in_array('junkAsRead', $r->junkmail_settings)): ?>
mail.spam.markAsReadOnSpam=true <?= "\n" ?>
<?php endif ?>
<?php if (in_array('junkFilter', $r->junkmail_settings)): ?>
mail.spam.logging.enabled=true <?= "\n" ?>
<?php endif ?>
<?php if (in_array('phishing', $r->phishing_detection)): ?>
mail.phishing.detection.enabled=true <?= "\n" ?>
<?php else: ?>
mail.phishing.detection.enabled=false <?= "\n" ?>
<?php endif ?>
<?php if (in_array('cookies', $r->allow_cookies)): ?>
network.cookie.alwaysAcceptSessionCookies=true <?= "\n" ?>
network.cookie.lifetimePolicy=<?=$r->cookies_deathdate?> <?= "\n" ?>
<?php endif ?>
<?php if (in_array('quarantine', $r->antivirus)): ?>
mailnews.downloadToTempFile=true<?="\n"?>
<?php endif ?>
network.automatic-ntlm-auth.trusted-uris="<?=$repack->ntlm_uris?>" <?="\n"?>
<? endif ?>
<?php if ((isset($r->include_lightning)) && (in_array('lightning', $r->include_lightning))): ?>
calendar.date.format=<?= ($r->datedisplay == 'long' ? '0' : '1')?><?="\n"?>
calendar.event.defaultlength=<?= ($r->eventlength != "" ? $r->eventlength : 60) ?><?="\n"?>
calendar.alarms.defaultsnoozelength=<?= ($r->snoozelength != ""? $r->snoozelength: 5) ?><?="\n"?>
calendar.alarms.playsound=<?= in_array('playSound', $r->reminder_settings) ? "true" : "false" ?><?="\n"?>
calendar.alarms.show=<?= in_array('showReminderDialog', $r->reminder_settings) ? "true" : "false" ?> <?="\n"?>
calendar.alarms.showmissed=<?= in_array('showMissedReminders', $r->reminder_settings) ? "true" : "false" ?><?="\n"?>
calendar.alarms.onforevents=<?= ($r->reminder_event == 'off' ? '0' : '1') ?><?="\n"?>
calendar.alarms.onfortodos=<?= ($r->reminder_task == 'off' ? '0' : '1')?><?="\n"?>
calendar.alarms.eventalarmlen=<?= ($r->minute_event == "" ? '15' : $r->minute_event)?><?="\n"?>
calendar.alarms.eventalarmunit="<?= $r->reminder_event_time ?>"<?="\n"?>
calendar.alarms.todoalarmlen=<?= ($r->minute_task == "" ? '15' : $r->minute_task)?><?="\n"?>
calendar.alarms.todoalarmunit="<?= $r->reminder_task_time ?>"<?="\n"?>
calendar.week.start=<?= $r->start_week?><?="\n"?>
calendar.weekd0sundayoff=<?= in_array('sun', $r->workweek) ? 'true' : 'false' ?><?="\n"?>
calendar.weekd1mondayoff=<?= in_array('mon', $r->workweek) ? 'false' : 'true' ?><?="\n"?>
calendar.weekd2tuesdayoff=<?= in_array('tue', $r->workweek) ? 'false' : 'true' ?><?="\n"?>
calendar.weekd3wednesdayoff=<?= in_array('wed', $r->workweek) ? 'false' : 'true' ?><?="\n"?>
calendar.weekd4thursdayoff=<?= in_array('thu', $r->workweek) ? 'false' : 'true' ?><?="\n"?>
calendar.weekd5fridayoff=<?= in_array('fri', $r->workweek) ? 'false' : 'true' ?><?="\n"?>
calendar.weekd6sundayoff=<?= in_array('sat', $r->workweek) ? 'true' : 'false' ?><?="\n"?>
calendar.view.daystarthour=<?= $r->start_time ?><?="\n"?>
calendar.view.dayendhour=<?= $r->end_time ?><?="\n"?>
calendar.view.visiblehours=<?= $r->day_length ?><?="\n"?>
calendar.weeks.inview=<?= $r->multiweek ?><?="\n"?>
calendar.previousweeks.inview=<?= $r->previous_week ?><?="\n"?>
<?php endif ?>

<?php if ((!isset($r->chat_settings)) || (!in_array('enable', $r->chat_settings))): ?>
mail.chat.enabled=<?= 'false' . "\n"?>
<?php else: ?>
mail.chat.enabled=<?= 'true' ."\n" ?>
<?php if (isset($r->startup)): ?>
messenger.startup.action=<?= $r->startup == 'true' ? 1 : 0 ?><?="\n"?>
<?php endif ?>
<?php if (isset($r->idle_settings)) : ?>
messenger.status.reportIdle=<?= (in_array('reportIdle', $r->idle_settings) ? 'true' : 'false') . "\n"?>
messenger.status.timeBeforeIdle=<?= !isset($r->idleMinutes) ? 5 : $r->idleMinutes ?><?="\n"?>
messenger.status.awayWhenIdle=<?= (in_array('awayMessage', $r->idle_settings) ? 'true' : 'false') . "\n"?>
<?php if (isset($r->idleMessage)): ?>
messenger.status.defaultIdleAwayMessage="<?= $r->idleMessage == "" ? "I am currently away from the computer" : $r->idleMessages ?>"<?="\n"?>
<?php endif ?>
<?php endif ?>
<?php if (in_array('typing', $r->chat_settings)): ?>
purple.conversations.im.send_typing=<?= 'true' ?>
<?php endif ?>
<?php if (in_array('acceptInvitations', $r->chat_settings)): ?>
messenger.conversations.autoAcceptChatInvitations=<?= '1' ."\n" ?>
<?php endif ?>
<?php if (in_array('private', $r->chat_settings)): ?>
purple.logging.log_ims=<?= 'true' ."\n"?>
<?php endif ?>
<?php if (in_array('public', $r->chat_settings)): ?>
purple.logging.log_chats=<?= 'true'."\n"?>
<?php endif ?>
<?php endif ?>
[LocalizablePreferences]

# <? # do not edit this line, or add newlines after it ?>
