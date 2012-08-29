<?php
/**
 * Repack certificate management controller
 * 
 * @package     BYOB
 * @subpackage  Controllers
 * @author      d. oberes
 */

class Certificatemanagement_Controller extends Local_Controller
{
    protected $auto_render = TRUE;
    /**
     * Constructor
     */
     public function __construct()
     {
         parent::__construct();

     }

    public function upload_cert() {

        $rp = $this->_getRequestedRepack();
        //if (!$rp->checkPrivilege('certificate_management_pem_upload'))
        //    return Event::run('system.403');

        #########
        $certs_dir = Kohana::config('repacks.workspace') . "/customizations/{$rp->profile->screen_name}_{$rp->short_name}";
        if (!is_dir($certs_dir)) mkdir($certs_dir, 0777, true);

        $errors = array();

        if ('delete' == $this->input->post('method')) {

            $delete_cert_file = $this->input->post('cert_file');
            $cert_files = glob("{$certs_dir}/*.pem");
            foreach ($cert_files as $cert_file) {
                if (basename($cert_file) == $delete_cert_file) {
                    unlink($cert_file);
                    break;
                }
            }
        } else if ('post' == request::method()) {
            if (empty($_FILES['cert']['tmp_name'])) { 
                $errors[] = _('No certificate to upload available');
            }

            $cert_file = $_FILES['cert']['tmp_name'];
            $cert_name = basename($_FILES['cert']['name']);

/* 
            // Validate the filename coming in
            $certificate_content = trim(file_get_contents($cert_file));
            if ('pem' != substr($cert_name, -3)
                || substr($certificate_content, 0, 27) != '-----BEGIN' 
                || substr($certificate_content, -25) !== '-----END CERTIFICATE-----') {
                    $errors[] = _('The uploaded file is not a pem formatted certifcate');
            } 
*/
	
	        if ($cert_file) { $cert_content = file($cert_file); } 
	        if ( '.pem' != substr( $cert_name, -4) 
	    	    || substr( $cert_content[0], 0, 10) != '-----BEGIN'                 
		        || substr( $cert_content[count($cert_content) -1], 0, 8) != '-----END' ) { 
                    $errors[] = _('Please upload only valid PEM files.'); 
                }

            if (empty($errors)) {
                move_uploaded_file(
                    $_FILES['cert']['tmp_name'], 
                    "{$certs_dir}/{$cert_name}"
                );

                if (!is_array($rp->changed_sections))
                    $rp->changed_sections = array();
                if (!in_array('certificate_management', $rp->changed_sections)) {
                    $changed = $rp->changed_sections;
                    array_push($changed, 'certificate_management');
                    $rp->changed_sections = $changed;
                }
            }
		    // clear cert name, or it 
        }

        // Build a list of all uploaded certificates
        $cert_files = glob("{$certs_dir}/*.pem");
        $certificates = array();
        foreach ($cert_files as $cert_file) {
            $certificates[] = $cert_file;
        }

        $this->view->set(array(
            'repack'        => $rp,
            'certificates'  => $certificates,
            'errors'        => $errors
        ));
    }

}
