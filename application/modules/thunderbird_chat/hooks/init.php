<?php
/**
 * Initialization hook for addon management
 *
 * @package    Mozilla_BYOB_Editor_ThunderbirdChat
 * @subpackage hooks
 * @author     l.m.orchard <lorchard@mozilla.com>
 */
$path = array(
    dirname(__FILE__) . '/../libraries',
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));

Event::add('system.ready',
    array('Mozilla_BYOB_Editor_ThunderbirdChat', 'register'));
