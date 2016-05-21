<?php
namespace Home\Org\Util;

require_once EXTEND_PATH.'Weixin'.DIRECTORY_SEPARATOR.'SmartWechat.php';
class ThinkWechat{
    	
	public static function getInstance($instance = 'Request', $config = array(), $debug = FALSE){
	    
		return \SmartWechat\Wechat::instance($instance, $config, $debug);
	}
}