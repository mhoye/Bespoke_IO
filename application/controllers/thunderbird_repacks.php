<?php 
/**
 * Thunderbird creation / editing controller
 *
 * @package    BYOB
 * @subpackage Controllers
 * @author     d.oberes
 */
class Thunderbird_repacks_Controller extends Local_Controller
{
    protected $auto_render = TRUE;

    public function __construct()
    {
        parent::__construct();
    }

   /**
     * Cancel Release of a new release.
     */
    public function approve()
    {
        $repack = $this->_getRequestedRepack(false, 'thunderbird_repack');
        if (!$repack->checkPrivilege('approve')) 
            return Event::run('system.403');

        if ('post' == request::method()) {
            if (isset($_POST['confirm'])) {
                $repack = $repack->approveRelease($this->input->post('comments'));
            }
            return url::redirect($repack->url);
        }
        $this->view->repack = $repack;
    } 

    /**
     * Cancel browser relase request.
     */
    public function cancel()
    {
        $rp = $this->_getRequestedRepack(false, 'thunderbird_repack');
        if (!$rp->checkPrivilege('cancel')) 
            return Event::run('system.403');

        if ('post' == request::method()) {
            if (isset($_POST['confirm'])) {
                $rp = $rp->cancelRelease($this->input->post('comments'));
            }
            return url::redirect($rp->url);
        }
        $this->view->repack = $rp;
    }

    /**
     * Creates an instance of Thunderbird_Repack_Model.
     */

    public static function create()
    {
        $tb = ORM::factory('Thunderbird_repack');
        $tb->profile_id = authprofiles::get_profile('id');
        $tb->save();
        return url::redirect($tb->url.";edit");
    }

    /**
     * Delete the details of a customized repack
     */
    public function delete()
    {
        $rp = $this->_getRequestedRepack(false, 'thunderbird_repack');
        if (!$rp->checkPrivilege('delete')) 
            return Event::run('system.403');

        $this->view->repack = $rp;

        if ('post' == request::method()) {
            if (isset($_POST['confirm']) ) {
                $rp->delete();
                return url::redirect(
                    "profiles/".authprofiles::get_profile('screen_name')."/mailclients"
                );
            } else {
                return url::redirect($rp->url);
            }
        }
    }

    /**
     * Spew out the raw distribution.ini used by repack
     */
    public function distributionini()
    {
        $rp = $this->_getRequestedRepack(false, 'thunderbird_repack');
        if (!$rp->checkPrivilege('distributionini')) 
            return Event::run('system.403');

        $this->auto_render = false;
        header('Content-Type: text/plain');
        echo $rp->buildDistributionIni();
    }
    

    /**
     * Download a build of a repack.
     */
    public function download()
    {
        // Find repack and filename parameter.
        $repack = $this->_getRequestedRepack(false, 'thunderbird_repack');
        $params = Router::get_params(array(
            'filename' => null
        ), 'filename');

        // Does the file exist for this repack?
        if (!in_array($params['filename'], $repack->files)) {
            return Event::run('system.404');
        }

        // Is the user allowed to download it?
        if (!$repack->checkPrivilege('download')) 
            return Event::run('system.403');

        // Build a full path to the downloadable file.
        $base_path = $repack->isRelease() ?
            Kohana::config('thunderbird_repacks.downloads_public') :
            Kohana::config('thunderbird_repacks.downloads_private');
        $repack_name = 
            "{$repack->profile->screen_name}_{$repack->short_name}";
        $filename = 
            "{$base_path}/{$repack_name}/{$params['filename']}";

        // Try guessing a content-type for the file.
        $ext_map = array(
            '.tar.bz2' => 'application/x-bzip2',
            '.dmg'     => 'application/x-apple-diskimage',
            '.exe'     => 'application/octet-stream',
        );
        $content_type = 'application/octet-stream';
        foreach ($ext_map as $ext=>$type) {
            if (strpos($filename, $ext) !== FALSE) {
                $content_type = $type; break;
            }
        }

        // Finally, dump the file out as a response.
        $this->auto_render = FALSE;
        header('Content-Type: ' . $content_type);
        header('Content-Length: ' . filesize($filename));
        header('Content-Description: File Transfer');
        //header('Content-Disposition: attachment; filename='.basename($filename));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        ob_clean();
        flush();
        readfile($filename);
    }


    public function edit()
    {
        $params = Router::get_params(array(
            'create' => false,
            'uuid'   => null
        ));

        $section = $this->input->get('section', 'thunderbird_general');

        $this->view->create = false;

        $tb = $this->_getRequestedRepack(false, 'thunderbird_repack');

        $editable_tb = $tb->findEditable();

        if (!$editable_tb) {
            // No editable alternative, so bail.
            return Event::run('system.403');
        }

        if (!$editable_tb->checkPrivilege('edit')) {
            return Event::run('system.403');
        }

        if ($editable_tb->id != $tb->id) {
            // Redirect to editable alternative.
            if (!$editable_tb->saved) {
                $editable_tb->save();
            }
            if ($editable_tb->isLockedForChanges()) {
                return url::redirect($editable_tb->url);
            } else {
                return url::redirect($editable_tb->url.';edit?section='.$section);
            }
        }

        if ($this->input->post('cancel', false)) {
            return url::redirect($tb->url);
        }

        $editor = Mozilla_BYOB_EditorRegistry::findById($section);
        if ($editor && !$editor->isAllowed($tb)) {
            return Event::run('system.403');
        }
       
        // Grab the form data, ensure UUID not changed.
        $form_data = $this->input->post();
        $form_data['uuid'] = $tb->uuid;

        // Try to validate the form data and update the repack.
        $is_valid = $tb->validateRepack(
            $form_data, ('post' == request::method()), $section
        );

        $this->view->set_global(array (
            'repack'        => $tb,
            'section'       => $section,
            'editor'        => $editor,
            'form_data'     => $form_data,
            'show_review'   => $this->input->get('show_review', 'false'),
        ));

        /* TB_Repack::findEditable gets called twice before here */
        /* ##################################################### */
        if ('post' != request::method()) {
            return;
        }

        if (!$is_valid) {

            // Not valid, so flag the errors.
            $this->view->form_errors = 
                $form_data->errors('form_thunderbird_repacks_edit');
        
        } else {

            // Mark this section as changed if the page thinks so.
            if (!is_array($tb->changed_sections)) 
                $tb->changed_sections = array();
            if ('true' == $this->input->post('changed', 'false')) {
                if (!in_array($section, $tb->changed_sections)) {
                    $changed = $tb->changed_sections;
                    array_push($changed, $section);
                    $tb->changed_sections = $changed;
                }
            }

            // This was a valid POST, so save the modified repack.
            $tb->save();

            // Mark sections as 'changed', save repack
            if ('true' == $this->input->post('done', false)) {
                // (Save and close), (No, cancel)
                return url::redirect($tb->url);
            } else if ($this->input->post('review', false)) {
                return url::redirect($tb->url.';release');
            } else if ('true' === $this->input->post('show_review', false)) {
                return url::redirect($tb->url.';edit?show_review=true&section='.
                    $this->input->post('next_section', $section));
            } else {
                return url::redirect($tb->url.';edit?section='.
                    $this->input->post('next_section', $section));
            }
        }
    }

    /**
     * Force build failure state.
     */
    public function fail()
    {
        $rp = $this->_getRequestedRepack(false, 'thunderbird_repack');
        if (!authprofiles::is_allowed('repacks', 'fail'))
            return Event::run('system.403');
        $rp = $rp->failRelease('Solar flares induced failure');
        return url::redirect($rp->url);
    }

    /**
     * Force build finish state.
     */
    public function finish()
    {
        $rp = $this->_getRequestedRepack(false, 'thunderbird_repack');
        if (!authprofiles::is_allowed('repacks', 'finish'))
            return Event::run('system.403');
        $rp = $rp->finishRelease();
        return url::redirect($rp->url);
    }


    /**
     * Present the browser first run page for a repack.
     */
    public function firstrun()
    {
        $rp = $this->_getRequestedRepack(false, 'thunderbird_repack');

        $this->view->set(array(
            'repack' => $rp
        ));

        $this->layout = null;
    }

    /**
     * List browsers for a profile.
     */
    public function index()
    {
        $params = Router::get_params(array(
            'screen_name' => null,
        ));

        // Look for a profile by screen name, 404 if not found.
        $profile = $this->view->profile = 
            ORM::factory('profile', $params['screen_name']);
        if (false === $profile->loaded) {
            return Event::run('system.404');
        }

        // Find all repacks for the profile.
        $all_profile_repacks = ORM::factory('thunderbird_repack')
            ->where('profile_id', $profile->id)
            ->find_all();

        // Index unique repacks by UUID and release / non-release
        $indexed_repacks = array();
        foreach ($all_profile_repacks as $repack) {
            if (!$repack->checkPrivilege('view')) continue;
            
            $uuid = $repack->uuid;
            if (!isset($indexed_repacks[$uuid])) 
                $indexed_repacks[$uuid] = array();
            $key = ($repack->isRelease()) ?
                'released' : 'unreleased';
            $indexed_repacks[$uuid][$key] = $repack;
        }

        $this->view->indexed_repacks = $indexed_repacks;
    }

    /**
     * Kicks off series of functions that get called when a Thunderbird 
     * customization is approved to be repacked.
     */
    public function release()
    {
        $tb = $this->_getRequestedRepack(false, 'thunderbird_repack');        

        if ('post' == request::method()) {
            if (isset($_POST['confirm'])) {
                $tb = $tb->requestRelease($this->input->post('comments'));
            }
            return url::redirect($tb->url); 
        }
        $this->view->repack = $tb;
    }

    /**
     * Reject release of a new release.
     */
    public function reject()
    {
        $rp = $this->_getRequestedRepack(false, 'thunderbird_repack');
        if (!$rp->checkPrivilege('reject')) 
            return Event::run('system.403');

        if ('post' == request::method()) {
            if (isset($_POST['confirm'])) {
                $rp = $rp->rejectRelease($this->input->post('comments'));
            }
            return url::redirect($rp->url);
        }
        $this->view->repack = $rp;
    }

    /**
     * Spew out the raw repack.cfg used by repack
     */
    public function repackcfg()
    {
        $rp = $this->_getRequestedRepack(false, 'thunderbird_repack');
        if (!$rp->checkPrivilege('repackcfg')) 
            return Event::run('system.403');

        $this->auto_render = false;
        header('Content-Type: text/plain');
        echo $rp->buildRepackCfg();
    }

    /**
     * Spew out the raw repack JSON data, or pretty-print PHP serialization.
     */
    public function repackjson()
    {
        $rp = $this->_getRequestedRepack(false, 'thunderbird_repack');
        if (!$rp->checkPrivilege('repackjson'))
            return Event::run('system.403');

        $this->auto_render = false;

        if ('pretty' != $this->input->get('format')) {
            header('Content-Type: application/json');
            echo $rp->as_json();
        } else {
            header('Content-Type: text/plain');
            $arr = $rp->as_array();
            unset($arr['json_data']);
            var_export($arr);
        }
    }

    /**
     * Output the last repack.log from build
     */
    public function repacklog()
    {
        $rp = $this->_getRequestedRepack(false, 'thunderbird_repack');
//        if (!$rp->checkPrivilege('repacklog'))
//            return Event::run('system.403');

        $workspace = Kohana::config('repacks.workspace');
        $repack_name = "{$rp->profile->screen_name}_{$rp->short_name}";

        $this->auto_render = false;
        header('Content-Type: text/plain');
        readfile("{$workspace}/partners_thunderbird/{$repack_name}/repack.log");
    }

    /**
     * Revert a public release
     */
    public function revert()
    {
        $rp = $this->_getRequestedRepack(false, 'thunderbird_repack');
        if (!$rp->checkPrivilege('revert')) 
            return Event::run('system.403');

        if ('post' == request::method()) {
            if (isset($_POST['confirm'])) {
                $rp = $rp->revertRelease($this->input->post('comments'));
            }
            return url::redirect($rp->url);
        }
        $this->view->repack = $rp;
    }

    /**
     * View details of a customized repack
     */
    public function view()
    {
        $repack = $this->_getRequestedRepack(true, 'thunderbird_repack');
        if (!$repack->checkPrivilege('view')) { 
            return Event::run('system.403');
        }
        $this->view->repack  = $repack;

        $release = $repack->findRelease();
        if ($release && $release->id != $repack->id) {
            if ($repack->checkPrivilege('view_changes')) {
                $this->view->changes = $repack->compare($release);
            }
        }

        if ($repack->checkPrivilege('view_history')) {
            $this->view->logevents = ORM::factory('logevent')
                ->findByUUID($repack->uuid);
        }
    }
}
