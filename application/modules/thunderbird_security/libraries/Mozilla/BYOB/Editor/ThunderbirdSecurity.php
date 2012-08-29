<?php
/**
 * BYOB editory module registry
 *
 * @package    Mozilla_BYOB_Editor_General
 * @subpacage  libraries
 * @author     d. oberes
 */

class Mozilla_BYOB_Editor_ThunderbirdSecurity extends Mozilla_BYOB_Editor {

    /** {{{ Object properties */
    public $id = 'thunderbird_security';
    public $title = 'Security';
    public $application = 'thunderbird';
    public $view_name = 'thunderbird_repacks/edit/edit_security';
    //public $review_view_name = 'thunderbird_repacks/edit/review_security';

    public $repack = null;
    /** }}}} */

    public function l10n_ready()
    {
        $this->title = _('Security');
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
       return $repack->checkPrivilege('thunderbird_security'); 
    }

    public function validate(&$data, $repack, $set=true) 
    {
		$this->repack = $repack;

		$data = Validation::factory($data)
			->pre_filter('trim');

		$data->add_rules('lock_security', 'is_array');
		$data->add_rules('junkmail_settings', 'is_array');
		$data->add_rules('markjunk_delete', 'required');
		$data->add_rules('phishing_detection', 'is_array');
		$data->add_rules('antivirus', 'is_array');
		$data->add_rules('allow_cookies', 'is_array');
		$data->add_rules('cookies_deathdate', 'required');

		$is_valid = $data->validate();
        return true;
    }
}

