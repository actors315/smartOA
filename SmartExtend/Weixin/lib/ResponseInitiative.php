<?php
namespace SmartWechat\lib;

/**
 * 主动推送消息
 */
class ResponseInitiative {
	/**
	 * 调试模式，将错误通过文本消息回复显示
	 * @var boolean
	 */
	private $debug;

	/**
	 * 发送数据
	 * @var array
	 */
	private $data = array();

	public function __construct($config = array(), $debug = false) {
		$this -> debug = $debug;
	}

	/**
	 * 主动发送消息
	 * @param mixed $content 待发送消息
	 * @param string $openid 消息接收者
	 * @param string $type 消息类型
	 * @return array 返回的信息
	 */
	public function sendMsg($content, $openid = '', $type = 'text') {
		$this -> data['touser'] = $openid;
		$this -> data['msgtype'] = $type;
		$this -> $type($content);
		return $this->send(json_encode($this->data));
	}

	/**
	 * 文本消息
	 * @param string $content
	 */
	private function text($content) {
		$this -> data['text'] = array('content' => $content);
	}

	/**
	 * 图片消息
	 * @param string $image 通过素材管理中的接口上传多媒体文件，得到的id
	 */
	private function image($image) {
		$this -> data['image'] = array('media_id' => $image);
	}

	/**
	 * 语音消息
	 * @param string $voice 通过素材管理中的接口上传多媒体文件，得到的id
	 */
	private function voice($voice) {
		$this -> data['voice'] = array('media_id' => $voice);
	}

	/**
	 * 视频消息
	 * @param array $video
	 */
	private function video($video) {
		list($video['media_id'], $video['thumb_media_id'], $video['title'], $video['description']) = $video;
		$this -> data['video'] = $video;
	}

	/**
	 * 音乐消息
	 * @param array $music
	 */
	private function music($music) {
		list($music['title'], $music['description'], $music['musicurl'], $music['hqmusicurl'], $music['thumb_media_id']) = $music;
		$this -> data['music'] = $music;
	}

	/**
	 * 图文信息
	 * @param array $news 发送图文消息（点击跳转到外链） 图文消息条数限制在8条以内，注意，如果图文数超过8，则将会无响应。
	 */
	private function news($news) {
		$articles = array();
		foreach ($news as $key => $value) {
			list($articles[$key]['title'], $articles[$key]['description'], $articles[$key]['url'], $articles[$key]['picurl']) = $value;
			if ($key >= 7) {
				break;
			}
		}
		$this -> data['news'] = array('articles' => $articles);
	}

	/**
	 * 图文信息
	 * @param string $news 发送图文消息（点击跳转到图文消息页面） 图文消息条数限制在8条以内，注意，如果图文数超过8，则将会无响应。
	 */
	private function mpnews($mpnews) {
		$this -> data['mpnews'] = array('media_id' => $mpnews);
	}

	/**
	 * 发送卡券
	 * @param array $news 发送图文消息（点击跳转到外链） 图文消息条数限制在8条以内，注意，如果图文数超过8，则将会无响应。
	 */
	private function wxcard($wxcard) {

	}

	private function send($data) {
		$token = \SmartWechat\Wechat::instance('AccessToken')->getToken();
		$curl = new \SmartWechat\Curl();
		return $curl->post("https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$token}",$data);
	}

}
