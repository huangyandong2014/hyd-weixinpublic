<?php
/**
 * 微信公众号请求操作类
 */
namespace Weixin;
use Table\PublicAccountRequest;

class WxRequest {
	static protected $httpRawPostData = '';	//HTTP_RAW_POST_DATA数据
	static protected $postData = array();	//将HTTP_RAW_POST_DATA转换成数组
	static protected $logTurnOn = true;		//日志开关
	
	/**
	 * 日志开关
	 */
	static public function setLogTurnOn($t=true) {
		self::$logTurnOn = $t;
	}

	/**
	 * 设置HTTP_RAW_POST_DATA
	 */
	static public function setHttpRawPostData($rawData) {
		self::$httpRawPostData = $rawData;
	}
	
	/**
	 * 获得HTTP_RAW_POST_DATA
	 */
	static public function httpRawPostData() {
		return self::$httpRawPostData;
	}
	
	/**
	 * 解析httpRawPostData为postData
	 */
	static public function parseHttpRawPostData() {
		if(!empty(self::$httpRawPostData)) {
			libxml_disable_entity_loader(true);
			$postObj = simplexml_load_string(self::$httpRawPostData, 'SimpleXMLElement', LIBXML_NOCDATA);
			self::$postData['openid']		= trim($postObj->ToUserName);
			self::$postData['wechat']		= trim($postObj->FromUserName);
			self::$postData['createtime']	= trim($postObj->CreateTime);
			self::$postData['type']			= strtolower(trim($postObj->MsgType));
			self::$postData['id']			= trim($postObj->MsgId);
			
			if('text' == self::$postData['type']) {
				//文本消息
				self::$postData['content']	=	trim($postObj->Content);
			} else if('image' == self::$postData['type']) {
				//图片消息
				self::$postData['mediaid']	=	trim($postObj->MediaId);
				self::$postData['picurl']	=	trim($postObj->PicUrl);
			} else if('voice' == self::$postData['type']) {
				//语音消息
				self::$postData['mediaid']	=	trim($postObj->MediaId);
				self::$postData['format'] 	=	trim($postObj->Format);
				self::$postData['recognition'] = trim($postObj->Recognition)?trim($postObj->Recognition):'';
			} else if('video' == self::$postData['type']) {
				//视频消息
				self::$postData['mediaid']	=	trim($postObj->MediaId);
				self::$postData['thumbmediaid']	=	trim($postObj->ThumbMediaId);
			} else if('location' == self::$postData['type']) {
				//地理位置消息
				self::$postData['x'] = trim($postObj->Location_X);
				self::$postData['y'] = trim($postObj->Location_Y);
				self::$postData['scale'] = trim($postObj->Scale);
				self::$postData['label'] = trim($postObj->Label);
			} else if('link' == self::$postData['type']) {
				//链接地址
				self::$postData['title'] = trim($postObj->Title);
				self::$postData['description'] = trim($postObj->Description);
				self::$postData['url'] = trim($postObj->Url);
			} else if('event' == self::$postData['type']) {
				//事件
				self::$postData['event'] = strtolower(trim($postObj->Event));
				self::$postData['eventkey'] = trim($postObj->EventKey)?trim($postObj->EventKey):'';
				self::$postData['ticket'] = trim($postObj->Ticket)?trim($postObj->Ticket):'';
				self::$postData['x'] = trim($postObj->Latitude)?trim($postObj->Latitude):'';
				self::$postData['y'] = trim($postObj->Longitude)?trim($postObj->Longitude):'';
				self::$postData['precision'] =  trim($postObj->Precision)?trim($postObj->Precision):'';
			} else {
				self::$postData = array();
			}
			return true;
		}
		return false;
	}
	
	/**
	 * 获取postData
	 */
	static public function postData() {
		return self::$postData;
	}
	
	/**
	 * 纪录到数据库中
	 */
	static public function record() {
		if(self::$logTurnOn && !empty(self::$postData)) {
			$data = array(
				'openid'	=>	self::$postData['openid']
				, 'wechat'	=>	self::$postData['wechat']
				, 'type'	=>	self::$postData['type']
				, 'addtime'	=>	time()
			);
			switch(self::$postData['type']) {
				case 'text': //文本消息
					$data['content'] = self::$postData['content'];
					break;
				case 'image': //图片消息
					$data['content'] = self::$postData['mediaid'];
					$data['extra'] = self::$postData['picurl'];
					break;
				case 'voice': //语音消息
					$data['content'] = self::$postData['mediaid'];
					$data['extra'] = serialize(array(
						'format'	=>	self::$postData['format']
						, 'text'	=>	self::$postData['recognition']	
					));
					break;
				case 'video': //视频消息
					$data['content'] = self::$postData['mediaid'];
					$data['extra'] = self::$postData['thumbmediaid'];
					break;
				case 'location': //地理消息
					$data['content'] = self::$postData['label'];
					$data['extra'] = serialize(array(
						'x'		=>	self::$postData['x']
						, 'y'	=>	self::$postData['y']
						, 'scale'=>	self::$postData['scale']
					));
					break;
				case 'link': //链接消息
					$data['content'] = self::$postData['title'];
					$data['extra'] = serialize(array(
						'description'	=>	self::$postData['description']
						, 'url'			=>	self::$postData['url']
					));
					break;
				case 'event': //事件消息
					$data['content'] = self::$postData['event'];
					$data['extra'] = serialize(array(
						'eventkey'	=>	self::$postData['eventkey']
						, 'ticket'	=>	self::$postData['ticket']
						, 'x'		=>	self::$postData['x']
						, 'y'		=>	self::$postData['y']
						, 'precision'=>	self::$postData['precision']
					));
					break;
				default: //取消纪录
					$data = false;
					break;
			}
			if(!empty($data)) {
				$dbObj = new PublicAccountRequest;
				return $dbObj->addRequest($data);
			}
		}
		return false;
	}
}