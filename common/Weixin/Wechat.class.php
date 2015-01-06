<?php
/**
 * 微信公众号接入和消息回复分发类
 */
namespace Weixin;
use Table\PublicAccountInfo;
use Weixin\WxRequest;
use Weixin\WxResponse;

class Wechat {
	static protected $openID = ''; 					//公众号OpenID
	static protected $publicAccountInfo = array(); 	//公众号信息
	static protected $postData = array();			//解析HTTP_RAW_POST_DATA数组 
	static protected $responseData = array();		//回复消息数组
	
	//设置OpenID
	static public function setOpenID($openID) {
		$obj = new PublicAccountInfo;
		$publicInfo = $obj->getAccountInfo($openID);
		self::$publicAccountInfo = empty($publicInfo)?array():$publicInfo;
		self::$openID = empty($publicInfo)?'':$openID;
	}
	
	//获得OpenID
	static public function openID() {
		return self::$openID;
	}
	
	//获得公众号信息
	static public function publicAccountInfo() {
		return self::$publicAccountInfo;
	}
	
	//设置postData
	static public function setPostData($rawData) {
		WxRequest::setHttpRawPostData($rawData);
		WxRequest::parseHttpRawPostData();
		self::$postData = WxRequest::postData();
		WxRequest::record();
	}
	
	//获得postData
	static public function postData() {
		return self::$postData;
	}
	
	//设置responseData
	static public function setResponseData($data) {
		self::$responseData = $data;
	}
	
	//获得responseData
	static public function responseData() {
		return self::$responseData;
	}
	
	//校验
	static public function valid($timestamp, $nonce, $signature) {
		if(empty(self::$publicAccountInfo))	return false;  //不存在账号
		if('-1' == self::$publicAccountInfo['status']) return false; //账号禁用
		$token = self::$publicAccountInfo['token'];
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		return $tmpStr == $signature;
	}
	
	//是否第一次接入
	static public function isFirstIn() {
		return self::$publicAccountInfo['status']=='0';
	}
	
	//第一次接入
	static public function firstInAnswer($echostr) {
		$obj = new PublicAccountInfo;
		if($obj->modifyAccountInfo($this->openID, array('status'=>'1'))) {
			echo $echostr;
		}
	}
	
	//回复消息
	static public function response() {
		WxResponse::setPostData(self::$postData);
		WxResponse::generateResponseData();
		self::setResponseData(WxResponse::responseData());
		WxResponse::output();
	}
}