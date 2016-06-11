<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Service {
	
	protected $data;
	
	function __construct($param){
		parent::__construct();		
		$this->data = $param;
		$this->load->model('User_model');
	}
	
	public function check_login(){
		if (empty($this->data['emp_no']) || empty($this->data['password'])) {
			throw new Exception('工号和密码不能为空！');			
		}		
		$auth_info = $this->User_model->get_user(array(
			'emp_no' => $this->data['emp_no'],
			'is_del' => 0
		));
		if (false == $auth_info) {
			throw new Exception('账号不存在或已被禁用！');
		}
		if (md5($this->data['password'] . $auth_info['salt']) != $auth_info['password']) {
			throw new Exception('密码不正确！');
		}
		
		$info['last_login_ip'] = $this->input->ip_address();
		$info['last_login_time'] = time();
		$info['login_count'] = array('expr', 'login_count+1');
		
		$this->User_model->update_user(array('id'=>$auth_info['id']),$info);
		
		$this->User_model->update_token($auth_info);
		
		unset($auth_info['id']);
		unset($auth_info['password']);
		unset($auth_info['salt']);
		return $auth_info;
	}
	
	public function check_token(){
		
	}
	
}
	