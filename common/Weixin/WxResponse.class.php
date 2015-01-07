<?php
/**
 * 微信公众号消息回复类
 */
namespace Weixin;
use Table\PublicAccountMenu;

class WxResponse {
	static protected $logTurnOn = true;				//日志开关
	static protected $publicaccountinfo = array();	//公众号信息
	static protected $requestData = array();		//解析后的请求参数
	static protected $responseData = array();		//回复数据
	
	/**
	 * 处理请求，生成responseData数据
	 */
	static public function handleRequestData() {
		
	}
	
	/**
	 * 输出回复
	 */
	static public function output() {
		
	}
	
	/**
	 * 记录日志
	 */
	static public function record() {
		
	}
	
	/*设置日志开关*/
	static public function setLogTurnOn($t=true) {
		self::$logTurnOn = (boolean)$t;
	}
	
	/*设置公众号信息*/
	static public function setPublicAccountInfo($info) {
		self::$publicaccountinfo = $info;
	}
	
	/*设置请求数据*/
	static public function setRequestData($d) {
		self::$requestData = $d;
	}
	
	/*返回回复数据*/
	static public function responseData() {
		return self::$responseData;
	}
}