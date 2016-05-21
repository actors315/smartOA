<?php
/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 单位 秒
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

function think_encrypt($data, $key = '', $expire = 0) {
	$key = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
	$data = base64_encode($data);
	$x = 0;
	$len = strlen($data);
	$l = strlen($key);
	$char = '';

	for ($i = 0; $i < $len; $i++) {
		if ($x == $l)
			$x = 0;
		$char .= substr($key, $x, 1);
		$x++;
	}

	$str = sprintf('%010d', $expire ? $expire + time() : 0);

	for ($i = 0; $i < $len; $i++) {
		$str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
	}
	return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
}

/**
 * 系统解密方法
 * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param  string $key  加密密钥
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

function think_decrypt($data, $key = '') {
	$key = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
	$data = str_replace(array('-', '_'), array('+', '/'), $data);
	$mod4 = strlen($data) % 4;
	if ($mod4) {
		$data .= substr('====', $mod4);
	}
	$data = base64_decode($data);
	$expire = substr($data, 0, 10);
	$data = substr($data, 10);

	if ($expire > 0 && $expire < time()) {
		return '';
	}
	$x = 0;
	$len = strlen($data);
	$l = strlen($key);
	$char = $str = '';

	for ($i = 0; $i < $len; $i++) {
		if ($x == $l)
			$x = 0;
		$char .= substr($key, $x, 1);
		$x++;
	}

	for ($i = 0; $i < $len; $i++) {
		if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
			$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
		} else {
			$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
		}
	}
	return base64_decode($str);
}

//以下函数需连接数据库

/**
 * 获取系统变量
 */
function get_system_config($code) {
	$model = M("SystemConfig");
	$where['code'] = array('eq', $code);
	$count = $model -> where($where) -> count();
	if ($count > 1) {
		return $model -> where($where) -> getfield("val,name");
	} else {
		$result = $model -> where($where) -> getfield("val");
		if (substr($result, 0, 5) == 'type_') {
			$result = substr($result, 5);
			$array = explode('|', $result);
			switch ($array[0]) {
				case 'list' :
					array_shift($array);
					return $array;
					break;
			}
		} else {
			return $result;
		}
	}
}