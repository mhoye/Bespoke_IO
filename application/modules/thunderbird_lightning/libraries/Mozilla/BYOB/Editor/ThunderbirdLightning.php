<?php
/**
 * BYOB editor module registry
 *
 * @package    Mozilla_BYOB_Editor_ThunderbirdAddons
 * @subpackage Libraries
 * @author     daoberes <donna.oberes@gmail.com>
 */
class Mozilla_BYOB_Editor_ThunderbirdLightning extends Mozilla_BYOB_Editor {

    /** {{{ Object properties */
    public $id        = 'thunderbird_lightning';
    public $title     = 'Lightning';
    public $application = 'thunderbird';
    public $view_name = 
        'thunderbird_repacks/edit/edit_lightning';
    public $review_view_name = 
        'thunderbird_repacks/edit/review_lightning';
    public $repack = null;
    /** }}}} */

    /**
     * Locale should be worked out by this time, so localize the tab title.
     */
    public function l10n_ready()
    {
        $this->title = _('Addons');
    }

    /**
     * Register and initialize this app module.
     */
    public static function register() 
    { 
        return parent::register(get_class()); 
    }

    /**
     * Determine whether the current user has permission to access 
     * this editor.
     */
    public function isAllowed($repack)
    {
       return $repack->checkPrivilege('thunderbird_lightning'); 
    }

    public function validate(&$data, $repack, $set=true) 
    {
       $this->repack = $repack;

        $data = Validation::factory($data)
            ->pre_filter('trim');

        $data->add_rules('include_lightning', 'is_array');
        $data->add_rules('datedisplay', 'required');
        $data->add_rules('eventlength', 'digit');
        $data->add_rules('snoozelength', 'digit');
        $data->add_rules('reminder_settings', 'is_array');
        $data->add_rules('reminder_event', 'required');
        $data->add_rules('reminder_task', 'required');
        $data->add_rules('minute_event', 'digit');
        $data->add_rules('reminder_event_time', 'required');
        $data->add_rules('minute_task', 'digit');
        $data->add_rules('reminder_task_time', 'required');
        $data->add_rules('start_week', 'required');
        $data->add_rules('workweek', 'is_array');
        $data->add_rules('start_time', 'required');
        $data->add_rules('end_time', 'required');
        $data->add_rules('day_length', 'required');
        $data->add_rules('multiweek', 'required');
        $data->add_rules('previous_week', 'required');
        $is_valid = $data->validate();

        return $is_valid;
    }
}
