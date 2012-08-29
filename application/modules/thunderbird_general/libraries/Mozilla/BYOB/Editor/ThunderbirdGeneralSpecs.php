<?php
/**
 * BYOB editor module registry
 *
 * @package    Mozilla_BYOB_Editor_ThunderbirdGeneralSpecs
 * @subpacage  libraries
 * @author     d. oberes
 */

class Mozilla_BYOB_Editor_ThunderbirdGeneralSpecs extends Mozilla_BYOB_Editor {

    /** {{{ Object properties */
    public $id = 'thunderbird_general';
    public $title = 'General';
    public $application = 'thunderbird';
    public $view_name = 'thunderbird_repacks/edit/edit_general_specs';
    //public $review_view_name = 'thunderbird_repacks/edit/review_general_specs';

    public $repack = null;
    /** }}}} */

    public function l10n_ready()
    {
        $this->title = _('General');
    }

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
       return $repack->checkPrivilege('thunderbird_general_specs'); 
    }   

    public function validate(&$data, $repack, $set=true) 
    {
       $this->repack = $repack;

        $data = Validation::factory($data)
            ->pre_filter('trim');

        $data->add_rules('user_title', 'required', 'length[1,255]');
        $data->add_rules('tbversion', 'required');

        $is_valid = $data->validate();

        return $is_valid;
    }
}

