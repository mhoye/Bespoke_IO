<?php
/**
 * BYOB editor module registry
 *
 * @package    Mozilla_BYOB_Editor_ThunderbirdChat
 * @subpacage  libraries
 * @author     d. oberes
 */

class Mozilla_BYOB_Editor_ThunderbirdChat extends Mozilla_BYOB_Editor {

    /** {{{ Object properties */
    public $id = 'thunderbird_chat';
    public $title = 'Chat';
    public $application = 'thunderbird';
    public $view_name = 'thunderbird_repacks/edit/edit_thunderbird_chat';
    //public $review_view_name = 'thunderbird_repacks/edit/review_thunderbird_chat';

    public $repack = null;
    /** }}}} */

    /** 
     * Register and initialize this app module
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
       return $repack->checkPrivilege('thunderbird_chat'); 
    }  

    public function validate(&$data, $repack, $set=true) 
    {
       $this->repack = $repack;

        $data = Validation::factory($data)
            ->pre_filter('trim');

        $data->add_rules('chat_settings', 'is_array');
        $data->add_rules('startup', 'required');
        $data->add_rules('idle_settings', 'is_array');
        $data->add_rules('idleMinutes', 'digit');
        $data->add_rules('idleMessage', 'length[0,255]');
        $data->add_rules('ircQuitMessage', 'length[0,255]');

        $is_valid = $data->validate();

        return $is_valid;
    }

}
