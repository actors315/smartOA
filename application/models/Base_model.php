<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
	}
    
}
	