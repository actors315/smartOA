<?php
namespace SmartSpider\lib;
/**
 * 日志管理
 */
class Log {
    
    private $debug;
    
    private static $_instance = array();
    
    public static function instance($config = array(),$driver = 'file',$debug = false){
        $this->debug = $debug;
        
        if(!isset(self::$_instance[$driver])){
            $class = '\\SmartSpider\\lib\\log\\Log_'.$driver;
            if(class_exists($class)){
                self::$_instance[$driver] = new $class($config,$debug);
            }else{
                throw new Exception("Log Driver $driver is not exist.", -1);
            }
        }
                
        return self::$_instance[$driver];
    }
}
