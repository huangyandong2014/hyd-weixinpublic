<?php
/**
 * 微信公众号接入
 */
namespace Weixin;
use Weixin\WxRequest;
use Weixin\WxResponse;

class Wechat {
	
	static protected $requestData = array();	//解析后的请求数据
	static protected $responseData = array();	//回复数据
	
	static protected $openid = '';  //公众号OpenID
	static protected $name = '';	//公众号名
	static protected $headimg = '';	//头像URL
	static protected $wechat = ''; 	//公众号相关的微信号
	static protected $type = 0;		//公众号类型，0没认证，1认证订阅号，2认证服务号
	static protected $appid = '';	//公众号APPID
	static protected $appsecret ='';//公众号AppSecret
	static protected $token = '';	//Token
	static protected $encodingaeskey='';//EncodingAESKey
	static protected $signtype = 0;	//消息体加密方式，0明文，1兼容，2安全
	static protected $status = 0;	//公众号状态，-1禁用，0未接入，1接入
	static protected $getparams = array(); //GET参数
	static protected $postparams = '';	//POST参数
	static protected $recordRequest = true; //是否记录用户发送给公众号的消息
	static protected $recordResponse = false; //是否记录公众号回复给用户的消息
	
	/**
	 * 消息回复
	 */
	static public function answer() {
		if(empty(self::$postparams))	return;  //HTTP_RAW_POST_DATA为空则不回复
	
		/*请求处理*/
		WxRequest::setLogTurnOn(self::$recordRequest);
		WxRequest::setPublicAccountInfo(self::info());
		WxRequest::setHttpRawPostData(self::$postparams);
		$success = WxRequest::parseHttpRawPostData();
		if(!$success) return;	//处理请求失败
		self::$requestData = WxRequest::postData();
		
		//请求消息不为空，则回复消息
		WxResponse::setLogTurnOn(self::$recordResponse);
		WxResponse::setPublicAccountInfo(self::info());
		WxResponse::setRequestData(self::$requestData);
		$success = WxResponse::handleRequestData();
		if(!$success) return;	//处理回复失败
		self::$responseData = WxResponse::responseData();
		WxResponse::output();
		return;
	}
	
	/**
	 * 校验是否合法
	 */
	static public function isValid() {
		if(empty(self::$openid) || -1 == self::$status)	return false;
		$tmpArr = array(self::$token, self::$getparams['timestamp'], self::$getparams['nonce']);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		return self::$getparams['signature']==$tmpStr;
	}
	
	/**
	 * 是否第一次接入
	 */
	static public function isFirstAnswer() {
		return  (0 == self::$status);
	}
	
	/**
	 * 第一次接入
	 */
	static public function firstAnswer() {
		echo self::$getparams['echostr'];
	}
	
	
	/*设置OpenID*/
	static public function setOpenID($openid) {
		self::$openid = $openid;
	}
	
	/*设置Name*/
	static public function setName($name) {
		self::$name = $name;
	}
	
	/*设置头像*/
	static public function setHeadImg($headimg) {
		self::$headimg = $headimg;
	}
	
	/*设置Wechat*/
	static public function setWechat($wechat) {
		self::$wechat = $wechat;
	}
	
	/*设置公众号类型，0没认证，1认证订阅号，2认证服务号*/
	static public function setType($type) {
		$type = intval($type);
		$type = in_array($type,array(0,1,2))?$type:0;
		self::$type = $type;
	}
	
	/*设置APPID*/
	static public function setAppID($appid) {
		self::$appid = $appid;
	}
	
	/*设置AppSecret*/
	static public function setAppSecret($appsecret) {
		self::$appsecret = $appsecret;
	}
	
	/*设置Token*/
	static public function setToken($token){
		self::$token = $token;
	}
	
	/*设置EncodingAESKey*/
	static public function setEncodingAESKey($aes) {
		self::$encodingaeskey = $aes;
	}
	
	/*设置消息体加密方式，0明文，1兼容，2安全*/
	static public function setSignType($type){
		//消息体加密方式，0明文，1兼容，2安全
		$type = intval($type);
		$type = in_array($type,array(0,1,2))?$type:0;
		self::$signtype = $type;
	}
	
	/*设置公众号状态，-1禁用，0未接入，1接入*/
	static public function setStatus($status){
		$status = intval($status);
		$status = in_array($status,array(-1,0,1))?$status:0;
		self::$status = $status;
	}
	
	/*设置GET参数*/
	static public function setGetParams($params) {
		self::$getparams = array(
			'signature'		=>	$params['signature']?$params['signature']:''
			, 'timestamp'	=>	$params['timestamp']?$params['timestamp']:''
			, 'nonce'		=>	$params['nonce']?$params['nonce']:''
			, 'echostr'		=>	$params['echostr']?$params['echostr']:''
		);
	}
	
	/*POST得到的参数*/
	static public function setPostParams($rawData) {
		self::$postparams = $rawData;
	}
	
	/*记录用户请求*/
	static public function setRecordRequest($t=true) {
		self::$recordRequest = (boolean)$t;
	}
	
	/*记录回复用户*/
	static public function setRecordResponse($t=true) {
		self::$recordResponse = $t;
	}
	
	/*解析后的请求数据*/
	static public function requestData() {
		return self::$requestData;
	}
	
	/*回复数据*/
	static public function responseData() {
		return self::$responseData;
	}
	
	/*获取公众号信息*/
	static public function info() {
		return array(
			'openid'		=>	self::$openid
			, 'name'		=>	self::$name
			, 'headimg'		=>	self::$headimg
			, 'wechat'		=>	self::$wechat
			, 'type'		=>	self::$type
			, 'appid'		=>	self::$appid
			, 'appsecret'	=>	self::$appsecret
			, 'token'		=>	self::$token
			, 'encodingaeskey'=>self::$encodingaeskey
			, 'signtype'	=>	self::$signtype
			, 'status'		=>	self::$status	
		);
	}
}