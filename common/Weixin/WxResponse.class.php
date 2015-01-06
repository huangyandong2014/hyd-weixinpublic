<?php
/**
 * 微信公众号消息回复类
 */
namespace Weixin;
use Table\PublicAccountMenu;

class WxResponse {
	static protected $postData = array();
	static protected $responseData = array();
	
	//设置postData
	static public function setPostData($postData) {
		self::$postData = $postData;
	}
	
	//获得postData
	static public function postData() {
		return self::$postData;
	}
	
	//获得responseData
	static public function responseData() {
		return self::$responseData;
	}
	
	//生成responseData
	static public function generateResponseData() {
		//1.获取公众
		
		$type = 'text';
		
		self::$responseData = array(
			'openid'		=>	self::$postData['openid']
			, 'wechat'		=>	self::$postData['wechat']
			, 'type'		=>	$type
			, 'createtime'	=>	time()
		);
	}
	
	//记录日志
	static public function record() {
		
	}
	
	//输出回复
	static public function output() {
		self::record();
	}
}