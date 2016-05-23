<?php
namespace Home\Controller;

use Think\Controller;

class PublicController extends Controller {

	public function login() {

		$this -> assign('is_verify_code', get_system_config("IS_VERIFY_CODE"));

		$this -> display();
	}

	public function check_login() {

		$is_verify_code = get_system_config("IS_VERIFY_CODE");
		if (!empty($is_verify_code)) {
			$verify = new \Think\Verify();
			if (!$verify -> check($_REQUEST['verify'], 'login')) {
				$this -> error('验证码错误！');
			}
		}

		$emp_no = $_POST['emp_no'];
		$password = $_POST['password'];
		if (empty($emp_no) || empty($password)) {
			$this -> error('工号和密码不能为空！');
		}

		$map['emp_no'] = $_POST['emp_no'];
		$map["is_del"] = array('eq', 0);
		$auth_info = M('User') -> where($map) -> find();
		if (false == $auth_info) {
			$this -> error('账号不存在或已被禁用！');
		}

		if (md5($password . $auth_info['salt']) != $auth_info['password']) {
			$this -> error('密码不正确！');
		}

		if (in_array($emp_no, C('ADMIN_AUTH_USERS'))) {
			session(C('ADMIN_AUTH_KEY'), true);
		}

		session(C('USER_AUTH_KEY'), $auth_info['id']);
		session('emp_no', $auth_info['emp_no']);
		session('user_name', $auth_info['name']);
		session('user_pic', $auth_info['pic']);

		//记录登录信息
		$ip = get_client_ip();
		$time = time();
		$data = array();
		$data['id'] = $auth_info['id'];
		$data['last_login_time'] = $time;
		$data['login_count'] = array('exp', 'login_count+1');
		$data['last_login_ip'] = $ip;
		M('User') -> save($data);
		header('Location: ' . U("index/index"));
		//登录成功直接跳转
	}

	public function logout() {
		$auth_id = session(C('USER_AUTH_KEY'));
		if (isset($auth_id)) {
			session(null);
			$this -> assign("jumpUrl", __APP__);
			$this -> success('退出成功！');
		} else {
			$this -> assign("jumpUrl", __APP__);
			$this -> error('退出成功！');
		}
	}

	public function register() {
		$is_verify_code = get_system_config("IS_VERIFY_CODE");
		if (!empty($is_verify_code)) {
			$verify = new \Think\Verify();
			if (!$verify -> check($_REQUEST['verify'], 'register')) {
				$this -> error('验证码错误！');
			}
		}

		if (empty($_POST['emp_no'])) {
			$this -> error('帐号必须！');
		} elseif (empty($_POST['password'])) {
			$this -> error('密码必须！');
		} elseif ($_POST['password'] !== $_POST['check_password']) {
			$this -> error('密码不一致');
		}

		$map = array();
		// 支持使用绑定帐号登录
		$map['emp_no'] = $_POST['emp_no'];
		$count = M("User") -> where($map) -> count();

		if ($count) {
			$this -> error('该账户已注册');
		} else {
			$model = D("User");
			if (false === $model -> create()) {
				$this -> error($model -> getError());
			}
			$list = $model -> add();
			if ($list !== false) {//保存成功
				$this -> assign('jumpUrl', get_return_url());
				$this -> success('注册成功!');
			} else {
				$this -> error('注册失败!');
				//失败提示
			}

		}
	}

	public function verify($type) {
		$verify = new \Think\Verify();
		$verify -> entry($type);
	}

}
