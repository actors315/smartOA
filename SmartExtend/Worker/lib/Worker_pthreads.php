<?php
namespace SmartWorker\lib;
/**
 * pthreads实现多线程
 */
class Worker_pthreads extends \Thread {
	public $data;

	public function __construct($config = array()) {
		
	}

	public function run() {
		// 线程处理一个耗时5秒的任务
		$tmp_data = [];
		for ($i = 0; $i < 5; $i++) {
			echo '线程: ' . date('H:i:s') . PHP_EOL;
			$tmp_data[]['exec_time'] = date('H:i:s');
			echo '线程ID：' . \Thread::getCurrentThreadId() . PHP_EOL;
			sleep(3);
		}
		$this -> data = json_encode($tmp_data);
		echo "线程: 任务完成" . date('H:i:s') . PHP_EOL;
	}

}
