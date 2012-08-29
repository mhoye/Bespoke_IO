<?php
/**
 * General specifications helper
 */

class general_specs_core {

    public static $products = null;

    /** 
     * Get all build versions 
     */
    public static function get_all_products()
    {
        
        return ORM::factory('product')->orderby('version_root', 'DESC')->find_all();
    } 

    public static function get_all_versions()
    {
        if (self::$products == null)
            self::$products = self::get_all_products();
        $versions = array();
        $temp = array();
        foreach (self::$products as $p)
        {
            $temp = array($p->version => $p->version_root); 
            $versions = array_merge($versions, $temp);
        }
        return $versions;
    }
}
