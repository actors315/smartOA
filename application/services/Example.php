<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Example extends MY_Service {
	
	protected $data;
	
	function __construct($param){
		parent::__construct();
		
		$this->data = $param;
	}
	
	function test(){
				
		return ['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'fact' => 'Loves coding'];
	}
}
	