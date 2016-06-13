<?php
namespace Home\Org\Util;

require_once EXTEND_PATH.'Curl'.DIRECTORY_SEPARATOR.'SmartCurl.php';
class ThinkCurl extends \SmartCurl{
    
	public function __construct($config = array()){
		parent::__construct();
	}
}
