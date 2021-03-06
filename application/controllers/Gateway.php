<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gateway extends MY_REST_Controller {

	public $request_args;

	public function __construct() {
		parent::__construct();
		$this -> request_args = $this -> _args;
	}

	public function route_get() {
		$this -> route_post();
	}

	public function route_post() {

		try {
			$this -> hooks -> call_hook('acl_auth');

			$this -> load -> service($this -> _args['service_name'], $this -> _args);
			$this -> rsp_data['data'] = $this -> {$this -> _args['service_name']} -> {$this->_args['operate_name']}();
		} catch(Exception $msg) {
			$this -> rsp_data['status'] = $msg -> getCode();
			$this -> rsp_data['info'] = $msg -> getMessage();
		}

		$this -> response($this -> rsp_data, 200);
	}

}
