<?php
namespace Home\Controller;

use Think\Controller;

class NodeController extends CommonController
{
	
    public function index()
    {
    	$this->assign('title','菜单管理');
        $this->_index();
    }
	
	
}