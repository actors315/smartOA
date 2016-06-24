<?php
namespace Home\Controller;

use Think\Controller;

class TestController extends Controller {
	protected $config = array('app_type' => 'public');
	
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
		import('Weixin.SmartWechat',EXTEND_PATH,'.php');
		echo C('ACCESSTOKEN_FILE');
		echo "<br />";
		$request = \SmartWechat\Wechat::instance('ResponseInitiative');
		print_r($request);
		$this->display();
	}
    
    public function bundle(){
        
    }
	
	public function testyar(){
		$client = new \Yar_client('https://admin.lingyin99.cn/Home/TestYar');
        $result = $client->index();
        var_dump($result); // 结果：Hello, Yar!
	}
}
