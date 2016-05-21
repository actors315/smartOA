<?php
namespace Cli\Org\Util;

require_once EXTEND_PATH.'Spider'.DIRECTORY_SEPARATOR.'SmartSpider.php';
class ThinkSpider{
    
    private static $_instance;
    
	public static function getInstance($config = array()){
	    if(!isset(self::$_instance)){
	        self::$_instance = new \SmartSpider\Spider($config);
	    }
        
        return self::$_instance;
	}
}