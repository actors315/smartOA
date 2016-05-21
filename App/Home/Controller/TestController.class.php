<?php
namespace Home\Controller;

use Think\Controller;

class TestController extends Controller {
    public function test() {
        print_r(C('DOWNLOAD_UPLOAD'));
        echo "<br />";
        echo C('DOWNLOAD_UPLOAD.rootPath');
        echo "<br />";
        echo C('TEST');
        echo "<br />";
        echo "COMPANYID is " . COMPANYID;
    }
	
	public function wechat(){
		$wechat = new \Home\Org\Util\ThinkWechat();
		echo C('ACCESSTOKEN_FILE');
		echo "<br />";
		$request = $wechat->getInstance('ResponseInitiative');
		print_r($request);
		$this->display();
	}
    
    public function bundle(){
        
    }
}
