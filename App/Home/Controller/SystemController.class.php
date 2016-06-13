<?php
namespace Home\Controller;

use Think\Controller;

class SystemController extends CommonController
{
    public function node()
    {
    	$this->assign('title','菜单管理');
        $this->display();
    }
}