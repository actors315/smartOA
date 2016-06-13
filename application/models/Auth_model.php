<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Auth_model extends Base_model {

	function __construct() {
		parent::__construct();
	}

	/**
	 * 查询接口状态
	 */
	public function get_api($where, $table = 'node_api') {
		$qurey = $this -> db -> where($where) -> get($table);
		$result = $qurey -> result_array();
		return isset($result[0]) ? $result[0] : FALSE;
	}

}
