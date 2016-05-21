<?php
namespace Cli\Org\Util;

require_once EXTEND_PATH.'Curl'.DIRECTORY_SEPARATOR.'SmartMultiCurl.php';
class ThinkCurl{
    
    private static $_instance;
    	
	public static function getInstance($config = array()){
	    if(!isset(self::$_instance)){
	        self::$_instance = new \SmartCurl\SmartCurl($config);
	    }
        
        return self::$_instance;
	}
}