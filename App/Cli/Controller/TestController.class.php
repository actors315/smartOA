<?php
namespace Cli\Controller;

use Think\Controller;

/**
 * cli test
 */
class TestController extends Controller {

    public function index() {
        import('@.Org.Util.ThinkSpider');

        $w = \SmartSpider\lib\Worker::instance('pcntl');
        $w -> count = 10;
        $w -> is_once = TRUE;
        $w -> on_worker_start = function($worker){
            echo "worker_id = ".$worker->worker_id.PHP_EOL;
            echo "worker_pid = ".$worker->worker_pid.PHP_EOL;
            echo "This is a test".PHP_EOL;
            echo "start time=".time().PHP_EOL;
            sleep(100);
            echo "end time = ".time().PHP_EOL;
        };
        print_r($w);
        $w->run();
    }
	
	 public function test() {
        $cookie = file_get_contents(C('SPIDER_COOKIE_FILE'));
        import('@.Org.Util.ThinkCurl');
		$curl = new \SmartCurl();
        $curl -> set_cookie($cookie);
        $curl -> set_gzip(true);

        $url = "https://www.zhihu.com/people/xiehuanjin";
        $content = $curl -> get($url) -> execute();
		print_r($content);
    }
	 
	public function multi(){
		$cookie = file_get_contents(C('SPIDER_COOKIE_FILE'));
        import('@.Org.Util.ThinkCurl');
		
		$multi = new \SmartMultiCurl();
		$multi -> set_cookie($cookie);
        $multi -> set_gzip(true);
        $url = "https://www.zhihu.com/people/xiehuanjin";
        $request1 = $multi -> get($url);
		
        $url = "https://www.zhihu.com/people/xiehuanjin/about";
        $request2 = $multi -> get($url);
		
		/*
		$curl = new \SmartCurl();
        $curl -> set_cookie($cookie);
        $curl -> set_gzip(true);
        $url = "https://www.zhihu.com/people/xiehuanjin";
        $request1 = $curl -> get($url);
		$multi -> add_request($request1);
		
		$curl = new \SmartCurl();
        $curl -> set_cookie($cookie);
        $curl -> set_gzip(true);
        $url = "https://www.zhihu.com/people/xiehuanjin/about";
        $request2 = $curl -> get($url);		
		$multi -> add_request($request2);
		*/
		$result = $multi -> execute();
		print_r($result);
	}
}