<?php
namespace SmartWechat\lib;

/**
 * 微信Access_Token的获取
 */
class AccessToken {
    
    public function __construct($config = array(),$debug = false){
        
    }
	
	public function getToken(){
		if (($accessToken = $this->checkAccessToken()) === false) {
			return $this->getAcessToken();
		}		
		return $accessToken;
	}
	
	private function getAcessToken(){
		//获取token
		$curl = new \SmartWechat\Curl();
		$data = $curl -> get('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WECHAT_APPID.'&secret='.WECHAT_APPSECRET);
		$accessToken = json_decode($data,TRUE);
		$accessToken['token_time']  = time();
		file_put_contents(ACCESSTOKEN_FILE, json_encode($accessToken),LOCK_EX);
	    //$fp = @fopen(ACCESSTOKEN_FILE, 'ab');
        //flock($fp, LOCK_EX);
        //fwrite($fp, json_encode($accessToken));
        //flock($fp, LOCK_UN);
        //fclose($fp);		
		return $accessToken['access_token'];
	}
	
	private function checkAccessToken(){
		$data = @file_get_contents(ACCESSTOKEN_FILE);
		if(!empty($data)){
			$accessToken = json_decode($data,TRUE);
			if(isset($accessToken['access_token']) && time() - $accessToken['token_time'] < $accessToken['expires_in'] - 200){
				return $accessToken['access_token'];
			}
		}
		return false;
	}
}
