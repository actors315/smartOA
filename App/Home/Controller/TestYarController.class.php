<?php
namespace Home\Controller;

use Think\Controller\YarController;

class TestYarController extends YarController  {
    public function index(){
        return 'Hello, Yar RPC!';
    }
	
}
