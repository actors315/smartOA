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

	public function wechat() {
		import('Weixin.SmartWechat', EXTEND_PATH, '.php');
		echo C('ACCESSTOKEN_FILE');
		echo "<br />";
		$request = \SmartWechat\Wechat::instance('ResponseInitiative');
		print_r($request);
		$this -> display();
	}

	public function bundle() {
		
	}

	public function testcurl() {
		import('Curl.SmartCurl', EXTEND_PATH, '.php');
		$curl = new \SmartCurl();
		//$to_uid = '4r2uh401t0fgv4rqm2i1he10q1';
		// 推送的url地址，上线时改成自己的服务器地址
		$push_api_url = "http://msg.lingyin99.cn:2121/";
		$post_data = array('type' => 'publish', 'content' => '这个是推送的测试数据', 'to' => $to_uid, );
		$return = $curl -> post($push_api_url,$post_data) -> execute();
		var_export($return);
	}

	public function testyar() {
		$client = new \Yar_client('https://admin.lingyin99.cn/Home/TestYar');
		$result = $client -> index();
		var_dump($result);
		// 结果：Hello, Yar!
	}

}
