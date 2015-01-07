<?php
/**
 * 微信公众号请求操作类
 */
namespace Weixin;
use Table\PublicAccountRequest;
use \SimpleXMLElement;

class WxRequest {
	static protected $logTurnOn = true;				//日志开关
	static protected $publicaccountinfo = array();	//公众号信息
	static protected $httpRawPostData = '';			//HTTP_RAW_POST_DATA数据
	static protected $postData = array();			//将HTTP_RAW_POST_DATA转换成数组
	
	/**
	 * 解析HTTP_RAW_POST_DATA数据
	 */
	static public function parseHttpRawPostData() {
		if(!empty(self::$httpRawPostData) && !empty(self::$publicaccountinfo)) {
			libxml_disable_entity_loader(true);
			$postObj = simplexml_load_string(self::$httpRawPostData, 'SimpleXMLElement', LIBXML_NOCDATA);
			$openID = self::getValue($postObj->ToUserName);
			$wechat = self::getValue($postObj->FromUserName);
			if($openID != self::$publicaccountinfo['openid'] || empty($wechat))	return false; //不是发给这个公众号的
			$type = strtolower(self::getValue($postObj->MsgType));
			self::$postData = array(
				'openid'	=>	$openID
				, 'wechat'	=>	$wechat
				, 'type'	=>	$type
				, 'content'	=>	''
				, 'extra'	=>	''
			);
				
			switch($type) {
				case 'text':	//文本消息
					self::$postData['content'] = self::getValue($postObj->Content);
					break;
				case 'image': 	//图片消息
					self::$postData['content'] = self::getValue($postObj->MediaId);
					self::$postData['extra']   = self::getValue($postObj->PicUrl);
					break;
				case 'voice':	//语言消息
					self::$postData['content'] = self::getValue($postObj->MediaId);
					self::$postData['extra']   = array(
						'format' 		=>	self::getValue($postObj->Format)
						, 'recognition'	=>	self::getValue($postObj->Recognition?$postObj->Recognition:'')
					);
					break;
				case 'video':	//视频消息
					self::$postData['content'] = self::getValue($postObj->MediaId);
					self::$postData['extra']   = self::getValue($postObj->ThumbMediaId);
					break;
				case 'location'://地理位置消息
					self::$postData['content'] = self::getValue($postObj->Label);
					self::$postData['extra']   = array(
						'x' 	=>	self::getValue($postObj->Location_X)
						, 'y'	=>	self::getValue($postObj->Location_Y)
						, 'scale'=>	self::getValue($postObj->Scale)
					);
					break;
				case 'link':	//链接消息
					self::$postData['content'] = self::getValue($postObj->Title);
					self::$postData['extra']   = array(
						'description' 	=>	self::getValue($postObj->Description)
						, 'url'	=>	self::getValue($postObj->Url)
					);
					break;
				case 'event':	//事件消息
					self::$postData['content'] = self::getValue($postObj->Event);
					self::$postData['extra']   = array(
						'eventkey' 		=>	self::getValue($postObj->EventKey?$postObj->EventKey:'')
						, 'ticket'		=>	self::getValue($postObj->Ticket?$postObj->Ticket:'')
						, 'x'			=>	self::getValue($postObj->Latitude?$postObj->Latitude:'')
						, 'y'			=>	self::getValue($postObj->Longitude?$postObj->Longitude:'')
						, 'precision'	=>	self::getValue($postObj->Precision?$postObj->Precision:'')
					);
					break;
			}
			return self::$logTurnOn?self::record():true;
		} else {
			return false;
		}
	}
	
	/**
	 * 设置日志开关
	 */
	static public function setLogTurnOn($t=true) {
		self::$logTurnOn = (boolean)$t;
	}
	
	/**
	 * 设置公众号信息
	 */
	static public function setPublicAccountInfo($info) {
		self::$publicaccountinfo = $info;
	}
	
	/**
	 * 设置
	 */
	static public function setHttpRawPostData($rawData) {
		self::$httpRawPostData = $rawData;
	}
	
	/**
	 * 返回解析后的postData数据
	 */
	static public function postData() {
		return self::$postData;
	}
	
	/**
	 * 获取值
	 */
	static protected function getValue($a) {
		return trim($a);
	} 
	
	/**
	 * 纪录到数据库中
	 */
	static public function record() {
		if(self::$logTurnOn && !empty(self::$postData)) {
			self::$postData['extra'] = is_array(self::$postData['extra'])?serialize(self::$postData['extra']):'';
			$dbObj = new PublicAccountRequest;
			return $dbObj->addRecord(self::$postData['openid'], self::$postData['wechat']
					, self::$postData['type'], self::$postData['content'], self::$postData['extra']);
		}
		return false;
	}
}