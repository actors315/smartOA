<?php
$config = array(
	'default' => array(
		//'配置项'=>'配置值'
		'TMPL_PARSE_STRING' => array(
			'__ACE__' => __ROOT__ . '/ace/assets',
			'__STATIC__' => __ROOT__ . '/Public/Static',
			'__REQUIREJS__' => __ROOT__. '/requirejs',
		),
	),
);
return isset($config[COMPANY_NO]) ? $config[COMPANY_NO] + $config['default']: $config['default'];