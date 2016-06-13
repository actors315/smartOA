<?php
namespace Home\Org\Util;

require_once EXTEND_PATH.'Curl'.DIRECTORY_SEPARATOR.'SmartMultiCurl.php';
class ThinkMultiCurl extends \SmartMultiCurl{
	public function __construct($config = array()){
		parent::__construct();
	}
}
