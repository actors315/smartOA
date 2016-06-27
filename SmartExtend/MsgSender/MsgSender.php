<?php
namespace SmartMsgSender;

class MsgSender {
	public function send($content, $type = 'publish', $to_uid = '') {
		import('Curl.SmartCurl', EXTEND_PATH, '.php');
		$curl = new \SmartCurl();
		$post_data = array('type' => $type, 'content' => $content, 'to' => $to_uid);
		$result = $curl -> post(MSG_PUSH_URL, $post_data) -> execute();
		return $result;
	}
}
