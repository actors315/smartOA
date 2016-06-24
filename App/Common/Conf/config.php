<?php
$config = array(
	'default' => array(
		//'配置项'=>'配置值'
		'LOAD_EXT_CONFIG'	=>'db,auth,weixin,alias',
		'URL_MODEL' => 2, // 如果你的环境不支持PATHINFO 请设置为3,
		/* 文件上传相关配置 */
		'DOWNLOAD_UPLOAD' => array(
		    'mimes'    => '', //允许上传的文件MiMe类型
		    'maxSize'  => 512*1024*1024, //上传的文件大小限制 (0-不做限制)
		    'autoSub'  => true, //自动子目录保存文件
		    'subName'  =>  array('date','Y_m_d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
		    'rootPath' => './Data/Files/'.COMPANY_NO . '/', //保存根路径
		    'savePath' => '', //保存路径
		    'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
		    'saveExt'  => '', //文件保存后缀，空则使用原后缀
		    'replace'  => false, //存在同名是否覆盖
		    'hash'     => true, //是否生成hash编码
		    'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
		),
		'DATA_AUTH_KEY'	=> 'fa53887f68f4d37985160f9011d9147b',
		'SHOW_PAGE_TRACE' =>FALSE,
	),
	'LINGYIN' => array(
		'SESSION_OPTIONS'	=> array('name'=>'smart_session','expire'=>7200,'domain'=>'.lingyin99.cn'),
		'COOKIE_DOMAIN'		=> '.lingyin99.cn',
	),
);
return isset($config[COMPANY_NO]) ? $config[COMPANY_NO] + $config['default']: $config['default'];