<?php
$config = array(
    'default' => array(
        //'配置项'=>'配置值'
        'IPLIST_FILE' => realpath('./Public/Data/Spider/').'/'.COMPANY_NO . '/iplist.txt',
        'SPIDER_COOKIE_FILE' => realpath('./Public/Data/Spider/').'/'.COMPANY_NO . '/cookie.txt'
    ),
);
return isset($config[COMPANY_NO]) ? $config[COMPANY_NO] + $config['default']: $config['default'];