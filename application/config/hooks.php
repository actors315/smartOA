<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
/**
 * 访问控制列表
 */
$hook['acl_auth'] = array(
		'class'    => 'Acl',
		'function' => 'filter',
		'filename' => 'acl.php',
		'filepath' => 'hooks',
		//'params'   => '',
);