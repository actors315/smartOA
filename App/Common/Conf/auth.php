<?php
$config = array(
	'default' => array(
		//'配置项'=>'配置值'
		'USER_AUTH_KEY'		=>'auth_id',	// 用户认证SESSION标记
	    'ADMIN_AUTH_KEY'	=>'administrator',        
	    'USER_AUTH_GATEWAY'	=>'public/login',// 默认认证网关
	    'ADMIN_AUTH_USERS'	=> array('admin',),
	),
);
return isset($config[COMPANY_NO]) ? $config[COMPANY_NO] + $config['default']: $config['default'];