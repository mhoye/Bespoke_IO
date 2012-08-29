<?php
  $chat_settings = form::value('chat_settings');
  if (empty($chat_settings)) $chat_settings = array();

  $idle_settings = form::value('idle_settings');
  if (empty($idle_settings)) $idle_settings = array();
?>

<div class="intro">
  <p><?=_('You can specify Chat settings here.')?></p>
</div>
<div class="pane">
  <fieldset>
    <div class="title">Chat</div>
    <div class="taller">
      <?= form::checkbox('chat_settings[]', 'enable', in_array('enable', $chat_settings)) ?>
      <?=_('Enable chat on Thunderbird')?>
    </div>
    <div class="taller">
      <?=_('When Thunderbird starts: ')?>
      <?= form::dropdown('startup', array('false' => 'Keep user accounts offline', 'true' => 'Connect user chat accounts automatically'), form::value('startup')) ?>
    </div>
    <br/>
    <div class="textbox_taller">
      <?= form::checkbox('idle_settings[]', 'reportIdle', in_array('reportIdle', $idle_settings)) ?>&nbsp;
      <?=_('Let contacts know that a user is idle after&nbsp;&nbsp;')?>
      <?= form::input(array('name'=>'idleMinutes', 'size'=>'1'), form::value('idleMinutes')) ?> &nbsp;
      <?=_('minutes of inactivity')?>
    </div>
    <div class="next_idle">
      <?= form::checkbox('idle_settings[]', 'awayMessage', in_array('awayMessage', $idle_settings))?>
      <?=_('and set his/her status to Away with this status message: ')?> <br/>
    </div>
    <div class="next_idle_textbox">
      <?= form::input(array('name'=>'idleMessage', 'size'=>'35'), form::value('idleMessage'))?>
    </div>
    <div class="taller">
      <?= form::checkbox('chat_settings[]', 'typing', in_array('typing', $chat_settings)) ?>
      <?=_('Send typing notifications in conversations')?>
    </div>
    <div class="taller">
      <?= form::checkbox('chat_settings[]', 'acceptInvitations', in_array('acceptInvitations', $chat_settings)) ?>
      <?=_('Automatically accept chat invitations')?>
    </div>
    <div class="taller">
      <?= form::checkbox('chat_settings[]', 'private', in_array('private', $chat_settings)) ?>
      <?=_('Log user private conversations')?>
    </div>
    <div class="taller">
      <?= form::checkbox('chat_settings[]', 'public', in_array('public', $chat_settings))?>
      <?=_('Log user IRC conversations')?>
    </div>
    <div>
      &nbsp;
    </div>
  </fieldset>
</div>
