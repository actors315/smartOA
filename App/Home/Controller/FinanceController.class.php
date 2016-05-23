<?php
namespace Home\Controller;

use Think\Controller;

class FinanceController extends CommonController
{
    public function calendar()
    {
    	$this->assign('title','日历表');
        $this->display();
    }
}