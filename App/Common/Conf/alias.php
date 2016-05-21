<?php
$config = array(
	'default' => array(
		//'配置项'=>'配置值'
		'AUTOLOAD_NAMESPACE' => array(
			
    	),
	),
);
return isset($config[COMPANY_NO]) ? $config[COMPANY_NO] + $config['default']: $config['default'];