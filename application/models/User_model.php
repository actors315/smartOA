<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class User_model extends Base_model {

	function __construct() {
		parent::__construct();
	}

	/**
	 * 查询用户
	 */
	public function get_user($where, $table = 'user') {
		$qurey = $this -> db -> where($where) -> get($table);
		$result = $qurey -> result_array();
		return isset($result[0]) ? $result[0] : FALSE;
	}

	/**
	 * 更新用户
	 */
	public function update_user($where, $data, $table = 'user') {
		if (empty($data)) {
			return FALSE;
		}

		if (is_array($data)) {
			foreach ($data as $key => $item) {
				if (is_array($item) && $item[0] == 'expr') {
					$this -> db -> set($key, $item[1], FALSE);
				} else {
					$this -> db -> set($key, $item);
				}
			}
		}

		return $this -> db -> where($where) -> update($table);
	}

	/**
	 * 新增用户
	 */
	public function insert_user($data, $table = 'user') {
		return $this -> db -> set($data) -> insert($table);
	}

	/**
	 * 查询token
	 */
	public function get_token($where, $table = 'user_access_token') {
		if (@$this -> config -> item('enable_cache')) {
			if(isset($where['access_token'])){
				$result = $this -> cache -> get($where['access_token']);
			}			
		}
		if (!isset($result['access_token'])) {
			$qurey = $this -> db -> where($where) -> where(array('invalidate >' => time())) -> get($table);
			$result = $qurey -> result_array();
			return isset($result[0]) ? $result[0] : FALSE;
		}
		return $result;
	}

	/**
	 * 更新token
	 */
	public function update_token($user) {
		//更新CI session
		$sessiondata['access_token'] = isset($user['token']) ? $user['token'] : $this -> _GUID();
		$sessiondata['invalidate'] = 3 * 24 * 60 * 60;
		$sessiondata['userid'] = $user['id'];
		$sessiondata['lastguid'] = $this -> _GUID();
		$sessiondata['lastdate'] = date('Y-m-d H:i:s');
		$sessiondata['last_activity'] = time();
		$sessiondata['ip_address'] = $this -> input -> ip_address();
		$sessiondata['user_agent'] = $this -> input -> user_agent();
		$this -> session -> set_userdata($sessiondata);

		//更新到缓存
		$this -> cache -> save($this -> session -> userdata('access_token'), $this -> session -> userdata, $this -> session -> userdata('invalidate'));
		
		//更新access_token到表
		$data['userid'] = $user['id'];
		$data['access_token'] = $this -> session -> userdata('access_token');
		$data['ip'] = $this -> session -> userdata('ip_address');
		$data['user_agent'] = $this -> session -> userdata('user_agent');
		$data['last_activity'] = $this -> session -> userdata('last_activity');
		$data['lastdate'] = $this -> session -> userdata('lastdate');
		$data['lastguid'] = $this -> session -> userdata('lastguid');
		$data['invalidate'] = $this -> session -> userdata('invalidate');
		$this -> db -> set($data) -> where(array('userid' => $user['id'])) -> replace('user_access_token');

		return $sessiondata['access_token'];
	}

	private function _GUID() {
		if (function_exists('com_create_guid') === true) {
			return trim(com_create_guid(), '{}');
		} else {
			mt_srand((double)microtime() * 10000);
			//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);
			// "-"
			$uuid = substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
			return $uuid;
		}
	}

}
