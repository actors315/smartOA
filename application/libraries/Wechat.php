<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH .'SmartExtend/Weixin/SmartWechat.php';
class Wechat {
    public function getInstance($instance = 'Request', $config = array(), $debug = FALSE){ 
        return \SmartWechat\Wechat::instance($instance, $config, $debug);
    }    
}
