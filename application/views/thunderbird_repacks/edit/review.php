<?php slot::set ('is_popup', 'true') ?>
<?php
$edit_base = $repack->url() . ';edit?section=';
$default_locale = empty($repack->default_locale) ? 
    'en-US' : $repack->default_locale;
?>

<div class="part" id="part1">
    <div class="header">
        <h2>Review and Confirm: </h2>
    </div>
    <div class="content">
        <?php if (!$repack->isCustomized()): ?>
            <p class="warning"><?=_('You haven\'t performed any customizations to this mail client beyond the default settings.  Please do so before submitting a request to build this mail client.')?></p>
        <?php else: ?>
            <p><?=_('Please review your customizations detailed below before submitting your mail client for build and approval:')?></p>
        <?php endif ?>

        <ul class="sections">
            <!-- GENERAL -->
            <li class="section general">
                <h3>General <a target="_top" href="<?=$repack->url()?>;edit?section=thunderbird_general"><?=_('edit')?></a></h3>
                <h4><?=html::specialchars($repack->title." ".$repack->tbversion)?></h4>
                <p></p>
            </li>
            <!-- SECURITY -->
            <li class="section">
                <h3>Security <a target="_top" href="<?=$repack->url()?>;edit?section=thunderbird_security"><?=_('edit')?></a></h3> 
                <b>Junk Mail Settings</b>
                <ul>
                    <?php if (isset($repack->junkmail_settings)): ?>
                        <?php if ((in_array('markJunk', $repack->junkmail_settings)) 
                              || (in_array('junkAsRead', $repack->junkmail_settings))
                              || (in_array('junkFilter', $repack->junkmail_settings))): ?>
                            <?php if (in_array('markJunk', $repack->junkmail_settings)): ?>
                                <li>When I mark messages as junk, 
                                <?php if ($repack->markjunk_delete == 0): ?>
                                    <?="move them to the account's Junk folder" ?>
                                <?php else: ?>
                                    <?="delete them" ?>
                                <?php endif ?>
                                </li>
                            <?php endif ?>
                            <?php if (in_array('junkAsRead', $repack->junkmail_settings)): ?>
                                <li>Mark messages determined to be Junk as read.</li>
                            <?php endif ?>
                            <?php if (in_array('junkFilter', $repack->junkmail_settings)): ?>
                                <li>Enable junk filter logging.</li>
                            <?php endif ?>

                        <?php else: ?>
                            <li>None.</li>
                        <?php endif ?>
                    <?php endif ?>
                </ul>

                <b>E-mail scams</b>
                <ul>
                    <?php if (isset($repack->phishing_detection)): ?>
                        <?php if (in_array('phishing', $repack->phishing_detection)): ?>
                            <li>Tell me if the message I'm reading is a suspected email scam.</li>
                        <?php else: ?>
                            <li>None.</li>
                        <?php endif ?>
                    <?php endif ?>
                </ul>

                <b>Anti-Virus</b>
                <ul>
                    <?php if (isset($repack->antivirus)): ?>
                        <?php if (in_array('quarantine', $repack->antivirus)): ?>
                            <li>Allow anti-virus clients to quarantine individual incoming messages</li>
                        <?php else: ?>
                            <li>None.</li>
                        <?php endif ?>
                    <?php endif ?>
                </ul>

                <b>Web Content</b>
                <ul>
                    <?php if (isset($repack->allow_cookies)): ?>
                        <?php if (in_array('cookies', $repack->allow_cookies)): ?>
                            <li>Accept cookies from sites. Keep until
                                <?php 
                                    $deathdate = "";
                                    if ($repack->cookies_deathdate == 0)
                                        $deathdate = "they expire.";
                                    else if ($repack->cookies_deathdate == 2)
                                        $deathdate = "user closes Thunderbird.";
                                    else
                                        $deathdate = "ask user every time.";
                                ?>
                                <?= $deathdate ?>
                            </li>
                        <?php else: ?>
                            <li>None.</li>
                        <?php endif ?>
                    <?php endif ?>
                </ul>
            </li>
            <!-- NTLM -->
            <li class="section">
                <h3>NTLM Authentication <a target="_top" href="<?=$repack->url()?>;edit?section=thunderbird_ntlm"><?=_('edit')?></a></h3>
                <?php if (!empty($repack->ntlm_uris)): ?>
                    <p><?=html::specialchars($repack->ntlm_uris)?></p>
                <?php else: ?>
                    <p>None.</p>
                <?php endif ?>
                <p></p>
            </li>

            <!-- Addons -->
            <?php
             $ev_data = array(
                    'repack' => $repack
                );
                Event::run('BYOB.thunderbird_repack.edit.review.renderSections', $ev_data);
                slot::output('BYOB.thunderbird_repack.edit.review.sections');
            ?>

            <?php 
                $sample = ($repack->datedisplay == 'long') ? "Long (Monday, June 25, 2012)" : "Short (06/25/2012)";
                $eventlength = empty($repack->eventlength) ? '60 (default)' : $repack->eventlength; 
                $snoozelength = empty($repack->snoozelength) ? '5 (default)' : $repack->snoozelength;
                $minute_event = empty($repack->minute_event) ? '60 (default)' : $repack->minute_event;
                $minute_task = empty($repack->minute_task) ? '5 (default)' : $repack->minute_task;
                $week = array('0' => 'Sunday', '1'=>'Monday', '2' => 'Tuesday', '3' => 'Wednesday', 
                              '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday');
                $workweek = array('sun' => 'Sunday', 'mon'=>'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 
                              'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday');
            ?>
            <!-- Lightning settings -->
            <li class="section">
                <h3>Lightning Settings <a target="_top" href="<?=$repack->url()?>;edit?section=thunderbird_lightning"><?=_('edit')?></a></h3>
                <h5>General Settings:</h5>
                <?php if (isset($repack->include_lightning)): ?>
		            <ul>
                    <?php if (in_array('lightning', $repack->include_lightning)): ?>
                        <li>Date format: <?= $sample?></li>
                        <li>Default event length: <?= $eventlength?></li>
                        <li>Default snooze length: <?= $snoozelength?></li>
                    <?php else: ?>
                        <li>None.</li>
                    <?php endif ?>
                </ul>
                <h5>Reminders:</h5>
                <ul>
                    <?php if (in_array('playSound', $repack->reminder_settings)): ?>
                        <li>Play a sound when a reminder is due</li>
                    <?php endif ?>
                    <?Php if (in_array('showReminderDialog', $repack->reminder_settings)): ?>
                        <li>Show the reminders dialog when a reminder is due</li>
                    <?php endif ?>
                    <?php if (in_array('showMissedReminders', $repack->reminder_settings)): ?>
                        <li>Show missed reminders</li>
                    <?php endif ?>
                    <li>Turn <?= $repack->reminder_event ?> default reminder setting for events</li>
                    <li>Turn <?= $repack->reminder_task ?> default reminder setting for tasks</li>
                    <li>Default time a reminder is set before an event: 
                        <?= $minute_event ?> <?= $repack->reminder_event_time ?>
                    </li>
                    <li>Default time a reminder is set before a task:
                        <?= $minute_task ?> <?= $repack->reminder_task_time ?>
                    </li>
                </ul>
                <h5>Views</h5>
                <ul>
                    <li>Start the week on: <?= $week[$repack->start_week] ?></li>
                    <li>Include these days in the work week: </li>
                    <ul>
                        <?php foreach ($repack->workweek as $day): ?>
                            <li><?= $workweek[$day] ?></li>
                        <?php endforeach ?>
                    </ul>
                    <li>Day starts at: <?= $repack->start_time ?> </li>
                    <li>Day ends at: <?= $repack->end_time ?></li>
                    <li>Show <?= $repack->day_length ?> hours at a time</li>
                    <li>Number of weeks to show (including previous weeks): <?= $repack->multiweek ?></li>
                    <li>Previous weeks to show: <?= $repack->previous_week ?></li>
                </ul>
                <?php endif ?>
            </li>

            <?php
                if (isset($repack->chat_settings))
                  $chat = $repack->chat_settings;
                if (isset($repack->startup))
                    $on_startup = ($repack->startup == 'true') ? 
                                  "connect user chat accounts automatically" : "keep user accounts offline";
                $idleMessage = $repack->idleMessage == "" ? 'I am currently away from the computer (default)' : $repack->idleMessage;
            ?>

            <!-- Chat settings -->
            <li class="section">
                <h3>Chat <a target="_top" href="<?=$repack->url()?>;edit?section=thunderbird_chat"><?=_('edit')?></a></h3>
                <ul> 
                    <?php if (empty($chat)): ?>
                        <li>Use the default Chat settings on Thunderbird</li>
                    <?php else: ?>
                        <?php if (in_array('enable', $chat)): ?>
                            <li>Enable Chat on Thunderbird</li>
                            <li>When Thunderbird starts, <?= $on_startup?></li>
                            <?php if (in_array('reportIdle', $repack->idle_settings)): ?>
                                <li>Let my contacts know that I am idle after <?= empty($repack->idleMinutes) ? '5 (default)' : $repack->idleMinutes ?> minutes</li>
                                <?php if (in_array('awayMessage', $repack->idle_settings)): ?>
                                  <li>When idle, set my status to Away</li>
                                  <ul>
                                      <li>Use this message: <?= $idleMessage ?></li>
                                  </ul>
                                <?php endif ?>
                            <?php endif ?>
                            <?php if (in_array('typing', $chat)): ?>
                                <li>Send typing notifications in conversations </li>
                            <?php endif ?>
                            <?php if (in_array('acceptInvitations', $chat)):?>
                                <li>Automatically accept chat invitations</li>
                            <?php endif ?>
                            <?php if (in_array('private', $chat)):?>
                                <li>Log my private conversations</li>
                            <?php endif ?>
                            <?php if (in_array('public', $chat)):?>
                                <li>Log my public conversations</li>
                            <?php endif ?>
                        <?php endif ?>
                    <?php endif ?>

                </ul>
            </li>
        </ul>

    </div>
    <div class="nav">
        <div class="prev button blue"><a class="popup_cancel" href="#"><?=_('&laquo;&nbsp; Cancel')?></a></div>
        <div class="build button yellow"><a target="_top" href="<?=$repack->url()?>;release"><?=_('Build this mail client')?></a></div>
    </div>
</div>
