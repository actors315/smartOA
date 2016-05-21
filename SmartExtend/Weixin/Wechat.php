<?php
namespace SmartWechat;

class Wechat {
	
	const NAMESPACE_PREFIX = 'SmartWechat';//只处理这个命名空间下的内容

	private static $_instance = array();
	
	public static function register(){
		spl_autoload_register(array(new self(),'autoload'));
	}
	
	public static function autoload($className){
		$classArr = explode('\\', $className);
		if (array_shift($classArr) == self::NAMESPACE_PREFIX) {
			$filePath = implode(DIRECTORY_SEPARATOR, $classArr).'.php';
			$filePath = realpath(__DIR__.DIRECTORY_SEPARATOR.$filePath);
			if(file_exists($filePath)){
				require_once $filePath;
			}
		}
	}

	public static function instance($instance = 'Request', $config = array(), $debug = FALSE) {
		
		if(!isset(self::$_instance[$instance])){
			$class = "\\SmartWechat\\lib\\{$instance}";            
            if(class_exists($class)){
                self::$_instance[$instance] = new $class($config,$debug);
            }else{
                throw new Exception("$instance is not exist", -1);
            }
		}
		
		return self::$_instance[$instance];
	}
}
