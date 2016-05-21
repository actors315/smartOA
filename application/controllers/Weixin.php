<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weixin extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this -> render_type = 'json';
        $this->load->library('wechat');
    }

    public function index() {
        $data['data'] = array('name' => 'xiehuanjin', 'age' => 18, 'sex' => 'male');
        $this -> render($data);
    }

    public function think() {
       log_message('debug', '接收到请求');
       $request = $this->wechat->getInstance('Request');
       $data = $request ->request();
       log_message('debug', '请求报文'.json_encode($data));
       $response = $this->wechat->getInstance('ResponsePassive',$data);
       $send = $response->response('消息已收到，感谢支持','text',0,TRUE);
       log_message('debug', '响应报文'.json_encode($send));
       $response -> sendMsg($send);
    }
    
    public function token(){
        $token = $this->wechat->getInstance('AccessToken');
        $access_token = $token->getToken();
        echo "access_token:".$access_token;
        log_message('debug', '成功获取access_token:'.$access_token);
    }
    
    public function initiative(){
        $initiative = $this->wechat->getInstance('ResponseInitiative');
        $result = $initiative->sendMsg('感谢关注','oyvTRjuKeAjwK0lGxDDAopWXLD6E','text');
        echo "发送结果：".$result;
        log_message('debug', '发送结果：'.$result);
    }

}
