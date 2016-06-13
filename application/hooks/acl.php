<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Acl
 *
 * 访问控制列表
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	hook
 * @author        	xie.hj
 * @link			http://xiehuanjin.cn
 * @version         0.0.1
 */
class Acl {

	private $ci_obj;

	public function __construct() {
		$this -> ci_obj = &get_instance();
		$this -> ci_obj -> load -> model('User_model');
		$this -> ci_obj -> load -> model('Auth_model');
	}

	public function filter() {
		if (!isset($this -> ci_obj -> request_args['service_name'])) {
			throw new Exception("服务不存在");
		}
		if (!isset($this -> ci_obj -> request_args['operate_name'])) {
			throw new Exception("操作不合法");
		}

		//不进行权限控制
		if (!$this -> ci_obj -> config -> item('acl_auth_on')) {
			return true;
		}

		//预留开放平台访问控制

		$service_name = $this -> ci_obj -> request_args['service_name'];
		$operate_name = $service_name . '/' . $this -> ci_obj -> request_args['operate_name'];

		//不要求登录
		if (in_array($service_name, $this -> ci_obj -> config -> item('acl_notauth_service'))) {
			return TRUE;
		}
		if (in_array($operate_name, $this -> ci_obj -> config -> item('acl_notauth_operate'))) {
			return TRUE;
		}

		//较验登录
		if (!isset($this -> ci_obj -> request_args['token'])) {
			throw new Exception("token不存在！");
		}
		$token_info = $this -> ci_obj -> User_model -> get_token(array('access_token' => $this -> ci_obj -> request_args['token']));
		if (!isset($token_info['userid'])) {
			throw new Exception("token不存在或已过期！");
		}

		//公开接口
		$api = $this -> ci_obj -> Auth_model -> get_api(array('service_name' => $service_name, 'operate_name' => $this -> ci_obj -> request_args['operate_name']));
		if (isset($api['status']) && $api['status'] == 0) {
			return TRUE;
		}
		//预留接口权限控制位置
		//$user = $this->ci_obj->User_model->get_user(array('id'=>$token_info['userid']));

		return FALSE;
	}

}
