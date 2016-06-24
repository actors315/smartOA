<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH .'SmartExtend/Pay/SmartPay.php';
class Pay extends CI_Controller {
	public function alipay() {
		$result = \Pingpp\Charge::create(array('order_no'  => '123456789',
			'amount'    => '100',//订单总金额, 人民币单位：分（如订单总金额为 1 元，此处请填 100）
			'app'       => array('id' => 'app_uDWjX58GWHq5zbfT'),
			'channel'   => 'alipay',
			'currency'  => 'cny',
			'client_ip' => '127.0.0.1',
			'subject'   => '这是一个测试',
			'body'      => '真的是一个测试')
		);
		exit(json_encode($result));
	}
}
