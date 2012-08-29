<?php
/**
 * BYOB editor module registry
 *
 * @package     Mozilla_BYOB_Editor_CertificateManagement
 * @subpackage  libraries
 * @author      d. oberes
 */

class Mozilla_BYOB_Editor_CertificateManagement extends Mozilla_BYOB_Editor {
    
    /** {{{ Object properties */
    public $id = 'certificate_management';
    public $title = 'Certificates';
    public $view_name = 'repacks/edit/edit_certificate_management';
    public $review_view_name = 'repacks/edit/review_certificate_management';
    
    public $repack = null;
    /** }}} */


    public function l10n_ready()
    {
        $this->title = _('Certificates');
    }

    /**
     * Register and initialize this app module.
     */
    public static function register ()
    {
        return parent::register(get_class());
    }

    /**
     * Determine whether the current user has permission to access this
     * editor.
     */
    public function isAllowed($repack) 
    {
        return $repack->checkPrivilege('certificate_management_pem_upload');
    }

    /** 
     * Function will grab all .pem's from their storage, put them all in 
     * in an array that the repack will remember. 
     */
    public function validate (&$data, $repack, $set=true) {

        /* A hack that doesn't really validate $data. This is created 
            to pass through repack::validateRepack without much fuss */

        $this->repack = $repack;
        $certs_dir = Kohana::config('repacks.workspace') . "/customizations/{$repack->profile->screen_name}_{$repack->short_name}";

        $cert_files = glob("{$certs_dir}/*.pem");
        $certificates = array();
        foreach ($cert_files as $cert_file) {
            $certificates[] = $cert_file;
        }

        $data = Validation::factory(&$data);
        $data->certificates = $certificates;
        $repack->certificates = $data->certificates;

        return $is_valid = true;
    }

    public function renderReviewSection()
    {
        $repack = Event::$data['repack'];
        $certificates = array();

        if (!empty($repack->certificates)) {
            $certificates = $repack->certificates;
        
            slot::append(
                'BYOB.repack.edit.review.sections',
                View::factory($this->review_view_name, 
                    array_merge(Event::$data, array('certificates' => $certificates))
                )
            );
        }
    }
}
