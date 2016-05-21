<?php
namespace SmartSpider\lib;
/**
 * 进程管理
 */
class Worker {

    private static $_instance = array();

    public static function instance($driver = 'pcntl', $config = array()) {

        if (!isset(self::$_instance[$driver])) {
            $class = '\\SmartSpider\\lib\\worker\\Worker_' . $driver;
            if (class_exists($class)) {
                self::$_instance[$driver] = new $class($config, $debug);
            } else {
                throw new Exception("Driver $driver is not exist.", -1);
            }
        }

        return self::$_instance[$driver];
    }

}
