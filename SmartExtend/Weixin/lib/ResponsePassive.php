<?php
namespace SmartWechat\lib;

/**
 * 被动回复消息
 */
class ResponsePassive {
    /**
     * 调试模式，将错误通过文本消息回复显示
     * @var boolean
     */
    private $debug;

    /**
     * 响应数据
     * @var array
     */
    private $send = array();

    public function __construct($request, $debug = false) {
        $this -> send = array('ToUserName' => $request['FromUserName'], 'FromUserName' => $request['ToUserName']);
        $this -> debug = $debug;
    }

    public function response($content, $type = 'text', $flag = 0, $return = FALSE) {
        $this -> send['CreateTime'] = time();
        $this -> send['MsgType'] = $type;

        $this -> $type($content);

        $this -> send['FuncFlag'] = $flag;//是否星标消息
        
        if($return){
            return $this->send;
        }
        
        $this->sendMsg($this->send);
    }
    
    /**
     * 发送操作
     * @param mixed $data 将要发送的消息体
     */
    public function sendMsg($data){
        /* 转换数据为XML */
        $xml = new \SimpleXMLElement('<xml></xml>');
        $this -> data2xml($xml, $data);
        exit($xml -> asXML());
    }

    /**
     * 文本消息
     * @param string $content
     */
    private function text($content) {
        $this -> send['Content'] = $content;
    }

    /**
     * 图片消息
     * @param string $image 通过素材管理中的接口上传多媒体文件，得到的id
     */
    private function image($image) {
        $image['MediaId'] = $image;
        $this -> send['Image'] = $image;
    }

    /**
     * 语音消息
     * @param string $image 通过素材管理中的接口上传多媒体文件，得到的id
     */
    private function voice($voice) {
        $voice['MediaId'] = $voice;
        $this -> send['Voice'] = $voice;
    }

    /**
     * 视频消息
     * @param array $video
     */
    private function video($video) {
        list($video['MediaId'], $video['Title'], $video['Description']) = $video;
        $this -> send['Video'] = $video;
    }

    /**
     * 音乐消息
     * @param array $music
     */
    private function music($music) {
        list($music['Title'], $music['Description'], $music['MusicUrl'], $music['HQMusicUrl'], $music['ThumbMediaId']) = $music;
        $this -> send['Music'] = $music;
    }

    /**
     * 图文信息
     * @param  array $news 要回复的图文内容
     */
    private function news($news) {
        $articles = array();
        foreach ($news as $key => $value) {
            list($articles[$key]['Title'], $articles[$key]['Description'], $articles[$key]['PicUrl'], $articles[$key]['Url']) = $value;
            if ($key >= 9) {
                break;
            } //最多只允许10调新闻
        }
        $this -> send['ArticleCount'] = count($articles);
        $this -> send['Articles'] = $articles;
    }

    /**
     * 数据XML编码
     * @param  object $xml  XML对象
     * @param  mixed  $data 数据
     * @param  string $item 数字索引时的节点名称
     */
    private function data2xml($xml, $data, $item = 'item') {
        foreach ($data as $key => $value) {
            /* 指定默认的数字key */
            is_numeric($key) && $key = $item;

            /* 添加子元素 */
            if (is_array($value) || is_object($value)) {
                $child = $xml -> addChild($key);
                $this -> data2xml($child, $value, $item);
            } else {
                if (is_numeric($value)) {
                    $child = $xml -> addChild($key, $value);
                } else {
                    $child = $xml -> addChild($key);
                    $node = \dom_import_simplexml($child);
                    $node -> appendChild($node -> ownerDocument -> createCDATASection($value));
                }
            }
        }
    }

}
