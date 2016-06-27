<?php
/*---------------------------------------------------------------------------
 |                              _oo8oo_
 |                             o8888888o
 |                             88" . "88
 |                             (| -_- |)
 |                             0\  =  /0
 |                           ___/'==='\___
 |                         .' \\|     |// '.
 |                        / \\|||  :  |||// \
 |                       / _||||| -:- |||||_ \
 |                      |   | \\\  -  /// |   |
 |                      | \_|  ''\---/''  |_/ |
 |                      \  .-\__  '-'  __/-.  /
 |                    ___'. .'  /--.--\  '. .'___
 |                 ."" '<  '.___\_<|>_/___.'  >' "".
 |                | | :  `- \`.:`\ _ /`:.`/ -`  : | |
 |                \  \ `-.   \_ __\ /__ _/   .-` /  /
 |            =====`-.____`.___ \_____/ ___.`____.-`=====
 |                              `=---=`
 |           ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 |
 |           		佛祖保佑         永不宕机/永无bug
 |
 | Copyright (c) 2016 http://www.xiehuanjin.cn All rights reserved.
 |
 | @Author:  xie.hj<xie.hj@thinkalways.net>
 -------------------------------------------------------------------------*/

namespace Common\Behavior;
use Think\Behavior;

defined('THINK_PATH') or exit('No direct script access allowed');

class AuthCheckBehavior extends Behavior {

	protected $config;

	public function run(&$params) {
		if (isset($params['app_type']) && $params['app_type'] == 'public') {
			return TRUE;
		}

		//登录处理
		if (empty(session('emp_no'))) {
			$token = cookie(C('USER_AUTH_KEY'));
			if (empty($token)) {
				redirect(U(C('USER_AUTH_GATEWAY')));
			}
			$user = cookie('SMART_LOGIN_INFO');
			if (empty($user)) {
				import('Curl.SmartCurl', EXTEND_PATH, '.php');
				$curl = new \SmartCurl();
				$curl -> set_cookiefile(C('SESSION_OPTIONS.path').C('SESSION_OPTIONS.name'.session_id()));
				$curl -> set_postfields(array('token' => $token, 'service_name' => 'auth', 'operate_name' => 'check_token'));
				$user_json = $curl -> post(C('AUTH_API_URL')) -> execute();
				\Think\Log::record('调用接口自动登录，接口返回user_json=' . $user_json, 'INFO');
				$user = json_decode($user_json, TRUE);
				if ($user['status'] != 1) {//接口失败
					redirect(U(C('USER_AUTH_GATEWAY')));
				}
				cookie('SMART_LOGIN_INFO', http_build_query($user['data'], '', '&'), array('path' => '/', 'domain' => '.lingyin99.cn', 'expire' => 3600));
				$auth_info = $user['data'];
			} else {
				parse_str(urldecode($user), $auth_info);
			}
			session('emp_no', $auth_info['emp_no']);
			session('user_name', $auth_info['name']);
			session('user_pic', $auth_info['pic']);
		}

		return FALSE;
	}

}
?>