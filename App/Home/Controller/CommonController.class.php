<?php
namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller
{
    function _initialize() {
    	$auth_id = session(C('USER_AUTH_KEY'));
		
		if (!isset($auth_id)) {
			//跳转到认证网关			
			redirect(U(C('USER_AUTH_GATEWAY')));
		}
    }
}