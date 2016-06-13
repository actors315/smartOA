<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends CommonController
{
    public function index()
    {		
    	$this->assign('title','仪表盘');
        $this->display();
    }
}