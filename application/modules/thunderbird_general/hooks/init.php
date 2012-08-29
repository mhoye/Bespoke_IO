<?php
/**
 * Initialization hook for the thunderbird general editor
 * 
 * @package Mozilla_BYOB_Editor_GeneralSpecs
 * @subpackage hooks
 * @author d.oberes
 */

$path = array(
    dirname(__FILE__).'/../libraries',
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));
Event::add('system.ready', 
    array('Mozilla_BYOB_Editor_ThunderbirdGeneralSpecs', 'register'));
