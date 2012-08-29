<?php
/**
 * BYOB editory module registry
 *
 * @package    Mozilla_BYOB_Editor_General
 * @subpacage  libraries
 * @author     d. oberes
 */

class Mozilla_BYOB_Editor_ThunderbirdNtlm extends Mozilla_BYOB_Editor {

    /** {{{ Object properties */
    public $id = 'thunderbird_ntlm';
    public $title = 'NTLM Authentication';
    public $application = 'thunderbird';
    public $view_name = 'thunderbird_repacks/edit/edit_ntlm';
    //public $review_view_name = 'thunderbird_repacks/edit/review_ntlm';

    public $repack = null;
    /** }}}} */

    public function l10n_ready()
    {
        $this->title = _('NTLM Authentication');
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
       return $repack->checkPrivilege('thunderbird_ntlm'); 
    }   

    public function validate(&$data, $repack, $set=true) 
    {
       $this->repack = $repack;

        $data = Validation::factory($data)
            ->pre_filter('trim');

        $data->add_rules('ntlm_uris', 'length[0,255]');

        $is_valid = $data->validate();

        return $is_valid;
    }
}

