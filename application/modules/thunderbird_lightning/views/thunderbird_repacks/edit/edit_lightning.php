<?php
  // Include Lightning in list of addons
  $lightning = form::value('include_lightning');
  if (empty($lightning)) $lightning = array();

  $date_selection = array('long' => 'Long: Monday, June 25, 2012', 'short' => 'Short: 06/25/2012');
  $date = form::value('datedisplay');

  $reminder_settings = form::value('reminder_settings');
  if (empty($reminder_settings)) $reminder_settings = array();

  $reminders = form::value('reminders');
  if (empty($reminders)) $reminders = array();

  $time_choices = array('minutes' => 'minutes', 'hours' => 'hours', 'days' => 'days');

  $week = array('0' => 'Sunday', '1'=>'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday',
          '5' => 'Friday', '6' => 'Saturday');

  $workweek = form::value('workweek');
  if (empty($workweek)) $workweek = array();

  $start_selection = array('0' => 'Midnight', '1' => '1:00 AM', '2' => '2:00 AM', '3' => '3:00 AM', '4' => '4:00 AM',
                 '5' => '5:00 AM', '6' => '6:00 AM', '7' => '7:00 AM', '8' => '8:00 AM', '9' => '9:00 AM',
                 '10' => '10:00 AM', '11' => '11:00 AM', '12' => 'Noon', '13' => '1:00 PM', '14' => '2:00 PM',
                 '15' => '3:00 PM', '16' => '4:00 PM', '17' => '5:00 PM', '18' => '6:00 PM', '19' => '7:00 PM', 
                 '20' => '8:00 PM', '21' => '9:00 PM', '22' => '10:00 PM', '23' => '11:00 PM');
  $start_time = form::value('start_time');
  $end_selection = array('24' => 'Midnight', '1' => '1:00 AM', '2' => '2:00 AM', '3' => '3:00 AM', '4' => '4:00 AM',
                 '5' => '5:00 AM', '6' => '6:00 AM', '7' => '7:00 AM', '8' => '8:00 AM', '9' => '9:00 AM',
                 '10' => '10:00 AM', '11' => '11:00 AM', '12' => 'Noon', '13' => '1:00 PM', '14' => '2:00 PM',
                 '15' => '3:00 PM', '16' => '4:00 PM', '17' => '5:00 PM', '18' => '6:00 PM', '19' => '7:00 PM', 
                 '20' => '8:00 PM', '21' => '9:00 PM', '22' => '10:00 PM', '23' => '11:00 PM');
  $end_time = form::value('start_time');

  $day_length = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', 
                 '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14',
                 '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', 
                 '22' => '22', '23' => '23', '24' => '24'); 

  $multiweek = array('1' => '1 week', '2' => '2 weeks', '3' => '3 weeks', '4' => '4 weeks', '5' => '5 weeks', 
               '6' => '6 weeks');

  $previous_week = array('0' => 'None', '1' => '1 week', '2' => '2 weeks');
?>

<div class="intro">
  <p><?=_('Lightning is the calendar extension that allows you to manage your schedule directly from Thunderbird.')?>
  <p><?=_('You can configure your options for the Lightning extension here.')?></p>
</div>
<div class="pane">
  <fieldset>
    <?= form::checkbox('include_lightning[]', 'lightning', in_array('lightning', $lightning))?>
    <?=_('Lightning will be included in your Thunderbird installer')?>
  </fieldset>
  <fieldset>
    <div class="title">General Settings</div>
    <div>
      <ul>
        <li>
          Date display format: <?= form::dropdown('datedisplay', $date_selection, $date)?>
        </li>
        <li>
          Default event length: <?= form::input(array('name' => 'eventlength', 'size' => 1), form::value('eventlength'))?> minute(s)
        </li>
        <li>
          Default snooze length: <?= form::input(array('name' => 'snoozelength', 'size' => 1), form::value('snoozelength'))?> minute(s)
        </li>
      </ul>
    </div>
  </fieldset>

  <fieldset>
    <div class="title">Reminders</div>
    <div>
      <p><?=_('When a reminder is due:')?></p>
      <ul>
        <li><?= form::checkbox('reminder_settings[]', 'playSound', in_array('playSound', $reminder_settings))?>&nbsp;Play a sound</li>
        <li><?= form::checkbox('reminder_settings[]', 'showReminderDialog', in_array('showReminderDialog', $reminder_settings))?>&nbsp;Show the reminders dialog</li>
        <li><?= form::checkbox('reminder_settings[]', 'showMissedReminders', in_array('showMissedReminders', $reminder_settings))?>&nbsp;Show missed reminders</li>
      </ul>
    </div>
     <div>
      <p><?=_('Reminder defaults:')?></p>
      <ul>
        <li>Turn on default reminder setting for events &nbsp;<?= form::dropdown('reminder_event', array('off' => 'Off', 'on' => 'On'), form::value('reminder_event'))?></li>
        <li>Turn on default reminder setting for tasks &nbsp;<?= form::dropdown('reminder_task', array('off' => 'Off', 'on' => 'On'), form::value('reminder_task'))?></li>
        <li>
          Default time a reminder is set before an event: 
          <?= form::input(array('name' => 'minute_event', 'size' => 1), form::value('minute_event'))?>
          <?= form::dropdown('reminder_event_time', $time_choices, form::value('reminder_event_time'))?>
        </li>
        <li>
          Default time a reminder is set before a task: 
          <?= form::input(array('name' => 'minute_task', 'size' => 1), form::value('minute_task'))?>
          <?= form::dropdown('reminder_task_time', $time_choices, form::value('reminder_task_time'))?>
        </li>
      </ul>
    </div>
  </fieldset>

  <fieldset>
    <div class="title">Views</div>
    <div>
      <ul>
        <li><?=_('Start the week on: ')?> &nbsp; <?= form::dropdown('start_week', $week, form::value('start_week'))?></li>
        <li><?=_('Include these days in the workweek:')?> &nbsp;&nbsp; 
          <?= form::checkbox('workweek[]', 'sun', in_array('sun', $workweek))?> Sun &nbsp;&nbsp;
          <?= form::checkbox('workweek[]', 'mon', in_array('mon', $workweek))?> Mon &nbsp;&nbsp;
          <?= form::checkbox('workweek[]', 'tue', in_array('tue', $workweek))?> Tue &nbsp;&nbsp;
          <?= form::checkbox('workweek[]', 'wed', in_array('wed', $workweek))?> Wed &nbsp;&nbsp;
          <?= form::checkbox('workweek[]', 'thu', in_array('thu', $workweek))?> Thu &nbsp;&nbsp;
          <?= form::checkbox('workweek[]', 'fri', in_array('fri', $workweek))?> Fri &nbsp;&nbsp;
          <?= form::checkbox('workweek[]', 'sat', in_array('sat', $workweek))?> Sat &nbsp;&nbsp;
        </li>
        <li><?=_('Day starts at: ')?> &nbsp; <?= form::dropdown('start_time', $start_selection, form::value('start_time'))?></li> 
        <li><?=_('Day ends at: ')?> &nbsp; <?= form::dropdown('end_time', $end_selection, form::value('end_time'))?></li> 
        <li><?=_('Show:')?> &nbsp; <?= form::dropdown('day_length', $day_length, form::value('day_length'))?> &nbsp; hours at a time</li>
        <li><?=_('Number of weeks to show (including previous weeks):')?> &nbsp; <?= form::dropdown('multiweek', $multiweek, form::value('multiweek'))?></li> 
        <li><?=_('Previous weeks to show:')?>&nbsp;<?= form::dropdown('previous_week', $previous_week, form::value('previous_week'))?> </li>
      </ul>
    </div>
  </fieldset>
</div>
