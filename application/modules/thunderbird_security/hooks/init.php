<?php
/**
 * Initialization hook for the security editor
 * 
 * @package Mozilla_BYOB_Editor_Security
 * @subpackage hooks
 * @author d.oberes
 */

$path = array(
    dirname(__FILE__).'/../libraries',
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));
Event::add('system.ready', 
    array('Mozilla_BYOB_Editor_ThunderbirdSecurity', 'register'));

