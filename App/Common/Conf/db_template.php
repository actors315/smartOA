<?php
$config = array(
	'default' => array(
		//'配置项'=>'配置值'
		'DB_TYPE' => 'mysqli', 
		'DB_HOST' => '127.0.0.1', 
		'DB_NAME' => 'test', 
		'DB_USER' => 'root', 
		'DB_PWD' => '', 
		'DB_PORT' => '3306', 
		'DB_PREFIX' => 'oa_', 
	),
);
return isset($config[COMPANY_NO]) ? $config[COMPANY_NO] + $config['default']: $config['default'];
