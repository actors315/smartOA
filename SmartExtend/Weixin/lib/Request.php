<?php
namespace SmartWechat\lib;

/**
 * 接收消息
 */
class Request {

    /**
     * 调试模式，将错误通过文本消息回复显示
     * @var boolean
     */
    private $debug;

    /**
     * 微信推送过来的数据
     * @var array
     */
    private $data = array();

    public function __construct($config = array(), $debug = false) {
        $this -> debug = $debug;
        $this -> auth() || exit ;
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            exit($_GET['echostr']);
        } else {
            $xml = file_get_contents("php://input");
            $xml = new \SimpleXMLElement($xml);
            $xml || exit ;
            foreach ($xml as $key => $value) {
                $this -> data[$key] = strval($value);
            }
        }
    }

    public function request() {
        return $this -> data;
    }

    private function auth() {
        /* 获取数据 */
        $data = array($_GET['timestamp'], $_GET['nonce'], WECHAT_TOKEN);
        $sign = $_GET['signature'];

        /* 对数据进行字典排序 */
        sort($data, SORT_STRING);

        /* 生成签名 */
        $signature = sha1(implode($data));

        return $signature === $sign;
    }

}
