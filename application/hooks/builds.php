<?php
/**
 * Hooks to perform repack builds in response to state changes
 *
 * @package    Mozilla_BYOB_RepackBuilds
 * @subpackage hooks
 * @author     l.m.orchard <lorchard@mozilla.com>
 */
class Mozilla_BYOB_RepackBuilds {

    /**
     * Initialize and wire up event responders.
     */
    public static function init() {
        // Respond to repack state changes
        Event::add(
            "BYOB.repack.changeState",
            array(get_class(), 'handleStateChange')
        );
//        Event::add(
//            "BYOB.thunderbird_repack.changeState",
//            array(get_class(), 'handleStateChange')
//        ); 

        // Defer performance of build actions to a queue.
        DeferredEvent::add(
            'BYOB.builds.perform',
            array(get_class(), 'performBuild')
        );
//        DeferredEvent::add(
//            'BYOB.builds.thunderbird_perform',
//            array(get_class(), 'performThunderbirdBuild')
//        );
        DeferredEvent::add(
            'BYOB.builds.release',
            array(get_class(), 'releaseBuilds')
        );
        DeferredEvent::add(
            'BYOB.builds.delete',
            array(get_class(), 'deleteBuilds')
        );
    }

    /**
     * Dispatcher for repack state change events.
     */
    public static function handleStateChange() {
        if (!Kohana::config('repacks.enable_builds')) return;

        $old_data =& Event::$data; // HACK: Event::run() is not re-entrant.
        switch (Event::$data['new_state']) {
            case 'requested':
//                if (Event::$data['repack']['repack_for'] == "firefox") {
                    Event::run('BYOB.builds.perform', Event::$data); break;
//                } 
//                else { // if (Event::$data['repack']['repack_for'] == "thunderbird") 
//                    Event::run('BYOB.builds.thunderbird_perform', Event::$data); break;
//                }
            case 'released':
                Event::run('BYOB.builds.release', Event::$data); break;
            case 'canceled':
            case 'rejected':
            case 'reverted':
            case 'deleted':
                Event::run('BYOB.builds.delete', Event::$data); break;
        }
        Event::$data =& $old_data;
    }
    
    /**
     * Delete all builds for the repack.
     */
    public static function deleteBuilds() {
        LMO_Utils_EnvConfig::apply('buildqueue');

        $repack = ORM::factory('repack', Event::$data['repack']['id']);

        if (Event::$data['repack']['repack_for'] == "firefox") {
            $base_paths = array(
                Kohana::config('repacks.downloads_public'),
                Kohana::config('repacks.downloads_private')
            );
        } else {
            $base_paths = array(
                Kohana::config('thunderbird_repacks.downloads_public'),
                Kohana::config('thunderbird_repacks.downloads_private')
            );
        }
        foreach ($base_paths as $base_path) {
            $repack_name = "{$repack->profile->screen_name}_{$repack->short_name}";
            $dest = "{$base_path}/{$repack_name}";

            Kohana::log('info', 'Deleting builds for ' .
                $repack->profile->screen_name . ' - ' . $repack->short_name);
            Kohana::log('debug', "rmdir $dest");
            Kohana::log_save();

            self::rmdirRecurse($dest);
        }

        // Forget any files the repack knew about.
        $repack->files = array();
        $repack->save();
    }

    /**
     * Move private builds to public release path.
     */
    public static function releaseBuilds() {
        LMO_Utils_EnvConfig::apply('buildqueue');

        if (Event::$data['repack']['repack_for'] == "firefox")  
        {
            $repack = ORM::factory('repack', Event::$data['repack']['id']);
            $src_path  = Kohana::config('repacks.downloads_private');
            $dest_path = Kohana::config('repacks.downloads_public');
        } 
        else {
            $repack = ORM::factory('thunderbird_repack', Event::$data['repack']['id']);
            $src_path  = Kohana::config('thunderbird_repacks.downloads_private');
            $dest_path = Kohana::config('thunderbird_repacks.downloads_public');
        }
        $repack_name = "{$repack->profile->screen_name}_{$repack->short_name}";

        $src  = "{$src_path}/{$repack_name}";
        $dest = "{$dest_path}/{$repack_name}";
        if (is_dir($dest)) self::rmdirRecurse($dest);
        mkdir("$dest", 0775, true);
        $cmd = rename($src, $dest);

        Kohana::log('debug', "Moved {$src} to {$dest}");
    }

    /**
     * Perform the process of repack builds
     */
    public static function performBuild($run_script=true) {
        LMO_Utils_EnvConfig::apply('buildqueue');

        $application = Event::$data['repack']['repack_for']; 

        if ($application == "firefox") {
            $repack = ORM::factory('repack', Event::$data['repack']['id']); 
        }
        else {
            $repack = ORM::factory('thunderbird_repack', Event::$data['repack']['id']);
        }

        $repack->beginRelease();

        // HACK: Since rebuilds might not have been edited (eg. is part of a 
        // global rebuild), ensure older-format bookmarks have been converted.
        if ($application == "firefox") {
            $repack->convertOlderBookmarks();
        }

        $ev_data = array(
            'repack' => $repack
        );
		
        if ($application == "firefox") { 
            /* If any certificates exist, modify the addonTemplate extension */
            if (!empty($repack->certificates)) {
                /* Build the custom extension */
                $repack->useCCKExtension('customize');
            }
        } 
		
        try {

            Event::run('BYOB.builds.perform.start', $ev_data);

            Kohana::log('info', 'Processing repack for ' .
                $repack->profile->screen_name . ' - ' . $repack->short_name);
            Kohana::log_save();

            $workspace = Kohana::config('repacks.workspace');

            // Clean up and make the repack directory.
            if ($application == "firefox") {
                $repack_dir = "$workspace/partners/".
                              "{$repack->profile->screen_name}_{$repack->short_name}";
            }
            else {
                $repack_dir = "$workspace/partners_thunderbird/".
                              "{$repack->profile->screen_name}_{$repack->short_name}";
            }

            if (is_dir($repack_dir)) {
                self::rmdirRecurse($repack_dir);
            }
            mkdir("$repack_dir/distribution", 0775, true);

            Kohana::log('debug', "Repack directory at {$repack_dir}");
            Kohana::log_save();

            $ev_data['repack_dir'] = $repack_dir;

            Event::run('BYOB.builds.perform.beforeConfig', $ev_data);

            // Generate the repack configs.
            file_put_contents("$repack_dir/repack.cfg",
                $repack->buildRepackCfg());
            file_put_contents("$repack_dir/distribution/distribution.ini",
                $repack->buildDistributionIni());

            $repack_assets_dir = $repack->getAssetsDirectory();
            self::recurseCopy($repack_assets_dir, $repack_dir);

            if ($application == "firefox")
                Event::run('BYOB.builds.perform.afterConfig', $ev_data);
            else {
                Event::run('BYOB.thunderbird_builds.perform.afterConfig', $ev_data);
            }

            Event::run('BYOB.thunderbird_builds.perform.beforeBuild', $ev_data);
            if ($application == "firefox") {
                if ($repack->numLockablePrefs > 0) {
                    $prefsFileTxt = "byob.cfg";
                    file_put_contents("{$repack_dir}/{$prefsFileTxt}", 
                        $repack->buildCfg());
                    
                    $prefsJS = "byob.js";
                    if (is_dir("{$repack_dir}/defaults/pref"))  
                        self::rmdirRecurse("{$repack_dir}/defaults/pref");
                    mkdir("{$repack_dir}/defaults/pref", 0775, true);
                    file_put_contents("{$repack_dir}/defaults/pref/{$prefsJS}",
                        $repack->buildPrefJS()); 
                    
                    /* Go to view createJS.php, which should spit out file that will 
                    determine where byob.cfg is. .js must be in workspace/pn_sn/distribution/greprefs */
                }

                if (!empty($repack->certificates)) {
                  $repack->useCCKExtension('insert'); 
                }
            }

            if ($run_script) {
                $script = Kohana::config('repacks.repack_script');
                // Remember the original directory and change to the repack dir.
                $origdir = getcwd();
                chdir($workspace);

                // Execute the repack script and capture output / state.
                $output = array();
                $state = 0;
                $repack_name = "{$repack->profile->screen_name}_{$repack->short_name}";
                if ($application == "firefox") { 
                    $cmd = join(' ', array(
                           "{$script}",
                           "-d partners",
                           "-p $repack_name",
                           "--app firefox",
                           "-v {$repack->ffversion}",
                           "-n {$repack->product->build}",
                           "--nightly-dir firefox/nightly",
                           ">partners/{$repack_name}/repack.log 2>&1"
                    ));
                }
                else {
                    $cmd = join(' ', array(
                           "{$script}",
                           "-d partners_thunderbird",
                           "-p $repack_name",
                           "--app thunderbird",
//                           "-v {$repack->product->version}",
//                           "-n {$repack->product->build}",
                           "-v {$repack->tbversion}",
                           "-n 1",
                           "--nightly-dir thunderbird/nightly",
                           ">partners_thunderbird/{$repack_name}/repack.log 2>&1"
                    ));
                } 
                Kohana::log('debug', "Executing {$cmd}...");
                Kohana::log_save();
                exec($cmd, $output, $state);

                // Restore original directory.
                chdir($origdir);

                // MySQL "goes away" while the repack is executing, so try 
                // reconnecting.
                $repack->reconnect();

                if (0 != $state) {
                    Kohana::log('error', "Failure in {$script} with state $state");
                    $repack->failRelease();
                    return;
                }

                Kohana::log('debug', "Success in {$script} with state $state");
                Kohana::log_save();

                // Record all the filenames generated by the repack.
                if ($application == "firefox") {
                    $src = "{$workspace}/repacked_builds/{$repack->product->version}".
                           "/build{$repack->product->build}/{$repack_name}";
                }
                else {
                    $src = "{$workspace}/repacked_builds/thunderbird_{$repack->tbversion}".
                           "/build{$repack->thunderbird_product->build}/{$repack_name}";
                }
                $files = array();
                foreach (glob("{$src}/*/*/*") as $fn) {
                    if (is_file($fn)) $files[] = str_replace("$src/", '', $fn);
                }
                $repack->files = $files;
                $repack->save();

                // Move the repacks to the private downloads area
                if ($application == "firefox") {
                    $downloads_private = Kohana::config('repacks.downloads_private');
                }
                else {
                    $downloads_private = Kohana::config('thunderbird_repacks.downloads_private');
                }
                $dest = "{$downloads_private}/{$repack_name}";
                if (is_dir($dest)) self::rmdirRecurse($dest);
                $cmd = rename($src, $dest);

                Kohana::log('debug', "Moved {$src} to {$dest}");
                Kohana::log_save();

            }

            Event::run('BYOB.builds.perform.afterBuild', $ev_data);

            Kohana::log('info', 'Finished repack for ' . 
                $repack->profile->screen_name . ' - ' . $repack->short_name);
            Kohana::log_save();

            $repack->finishRelease();

            Event::run('BYOB.builds.perform.afterRelease', $ev_data);

        } catch (Exception $e) {
            Event::run('BYOB.builds.perform.failure', $ev_data);
            $repack->failRelease($e->getMessage());
        }
    }


    /**
     * Utility function to recursively delete a directory.
     */
    public static function rmdirRecurse($path) {
        $path= rtrim($path, '/').'/';
        if (!is_dir($path)) return;
        $handle = opendir($path);
        for (;false !== ($file = readdir($handle));)
            if($file != "." and $file != ".." ) {
                $fullpath= $path.$file;
                if( is_dir($fullpath) ) {
                    self::rmdirRecurse($fullpath);
                } else {
                    unlink($fullpath);
                }
            }
        closedir($handle);
        rmdir($path);
    } 

    /**
     * Utility function to recursively copy files.
     */
    public static function recurseCopy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    self::recurseCopy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    } 

}

Event::add('system.ready', array('Mozilla_BYOB_RepackBuilds', 'init'));
