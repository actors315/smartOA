<?php

namespace Cli\Model;
use Think\Model;

class ZhihuUserModel extends Model {
    public $_validate = array( 
        array('username', 'require', 'username不能为空'),
        array('username', '', 'username已存在！', 0, 'unique', 1), 
    );

    public $_auto = array( 
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
    );
    
    function get_user_queue($key = 'list', $count = 10000){
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);
        if(!$redis->lsize($key)){
            $sql = "select `username`, `{$key}_uptime` From `".$this->tablePrefix."zhihu_user` "; 
            if($key <> 'info'){
            	$sql = $sql . "where {$key}s > 0 ";
			}
            $sql = $sql . "order By `{$key}_uptime` Asc limit {$count} ";
            $rs = $this->db->query($sql);
            foreach ($rs as $row) {
                $redis->lpush($key,$row['username']);
            }
        }        
        return $redis->lpop($key);
    }
}
?>