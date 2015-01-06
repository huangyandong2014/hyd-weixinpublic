<?php
/**
 * 微信公众号接入和消息回复分发类
 */
namespace Weixin;
use Table\PublicAccountInfo;

class Wechat {
	protected $publicAccountOpenID = ''; 	//公众号OpenID
	protected $publicAccountInfo = false; 	//公众号信息
	protected $httpRawPostData = ''; 		//POST Raw数据
	protected $postData = array();			//POST提交的数据转换为数组
	
	public function __construct($openID, $info) {
		$this->setPublicAccountOpenID($openID)->setPublicAccountInfo($info);
	}
	
	//1.设置公众号OpenID
	public function setPublicAccountOpenID($openID) {
		$this->publicAccountOpenID = $openID;
		return $this;
	}
	
	//2.设置公众号信息
	public function setPublicAccountInfo($info) {
		$this->publicAccountInfo = $info;
		return $this;
	}
	
	//3.接入校验
	public function valid($nonce, $timestamp, $signature) {
		$token = $this->publicAccountInfo['token'];
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		if($tmpStr == $signature){
			return true;
		}else{
			return false;
		}
	}
	
	//4.成功接入
	public function successInterface($echostr) {
		$obj = new PublicAccountInfo;
		$success = $obj->modifyAccountInfo($this->publicAccountInfo['openid'], array('status'=>'1'));
		echo $str;
	}
	
	//5.设置HTTP_RAW_POST_DATA
	public function setHttpRawPostData($d) {
		$this->httpRawPostData = $d;
		return $this;
	}
	
	//6.将HTTP_RAW_POST_DATA转换为数组
	public function setPostData($rawData) {
		
		//此次解析$rawData
		
		$this->postData = $rawData;
		return $this;
	}
	
	//7.将用户请求的消息记录到数据库中
	public function recordRequest() {

		return $this;
	}
	
	//8.分发消息回复
	public function dispatch() {
		
		return $this;
	}
	
	//回复消息
	public function response() {
		//1.解析HTTP_RAW_POST_DATA
		$this->setPostData($this->httpRawPostData);
		
		//2.将消息记录到数据库中
		$this->recordRequest();
		
		//3.分发消息回复操作
		$this->dispatch();
	}
}
