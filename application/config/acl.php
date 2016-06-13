<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 |--------------------------------------------------------------------------
 | Acl config
 |
 | Acl权限控制，配置需要权限控制的方法
 |--------------------------------------------------------------------------
 */

/*
 |--------------------------------------------------------------------------
 | acl_auth_on	是否开启权限控
 |
 | TRUE 开启
 | FALSE 关闭，并闭之后Acl权限控制模块不启作用
 |--------------------------------------------------------------------------
 */
$config['acl_auth_on'] = TRUE;

/*
 |--------------------------------------------------------------------------
 | acl_notauth_service 不需要权限控制的service_name
 |
 | 配置方式，array('service_name1','service_name2')
 | 如 array('user')
 |--------------------------------------------------------------------------
 */
$config['acl_notauth_service'] = array('auth', );

/*
 |--------------------------------------------------------------------------
 | acl_notauth_operate 不需要权限控制的operate_name，
 |
 | 配置方式array('service_name1/operate_name1','service_name2/operate_name2')
 | 如 array('user/check_login')
 |--------------------------------------------------------------------------
 */
$config['acl_notauth_operate'] = array();

/* End of file acl.php */
/* Location: ./application/config/acl.php */
