<?php
/**
 * 微信公众号消息回复类
 */
namespace Weixin;
use Table\PublicAccountMenu;
use Table\PublicAccountResponse;
use Weixin\WxUtil;

class WxResponse {
	static protected $logTurnOn = true;				//日志开关
	static protected $publicaccountinfo = array();	//公众号信息
	static protected $requestData = array();		//解析后的请求参数
	static protected $responseData = array();		//回复数据
	
	/**
	 * 处理请求，生成responseData数据
	 */
	static public function handleRequestData() {
		if(empty(self::$requestData)) return false;

		self::$responseData = array(
			'openid'	=>	self::$requestData['openid']
			, 'wechat'	=>	self::$requestData['wechat']
			, 'type'	=>	'text'
			, 'content'	=>	'Hello World'
			, 'extra'	=>	''
			, 'addtime'	=>	time()
		);
		
		
		return self::$logTurnOn?self::record():true;
	}
	
	/**
	 * 输出回复
	 */
	static public function output($responseData) {
		if(empty($responseData))	return;
		
		$format =	'<xml>';
		$format .=	'<ToUserName><![CDATA[%s]]></ToUserName>';
		$format .=	'<FromUserName><![CDATA[%s]]></FromUserName>';
		$format .=	'<CreateTime>%s</CreateTime>';
		$format .=	'<MsgType><![CDATA[%s]]></MsgType>';
		$format .= 	'%s';
		$format .= '</xml>';
		
		$extra = '';
		switch($responseData['type']) {
			case 'text':	//回复文本
				$extra = sprintf('<Content><![CDATA[%s]]></Content>', $responseData['content']);
			break;
			case 'image':	//回复图片
				$extra = sprintf('<Image><MediaId><![CDATA[%s]]></MediaId></Image>', $responseData['content']);
				break;
			case 'voice':	//回复语音
				$extra = sprintf('<Voice><MediaId><![CDATA[%s]]></MediaId></Voice>', $responseData['content']);
				break;
			case 'video':	//回复视频
				$extra = sprintf('<Video><MediaId><![CDATA[%s]]></MediaId><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description>'
					, $responseData['content'], $responseData['extra']['title'], $responseData['extra']['description']);
				break;
			case 'music':	//回复音乐
				$extra = sprintf('<Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl><ThumbMediaId><![CDATA[%s]]></ThumbMediaId></Music>'
					, $responseData['content'], $responseData['extra']['description'], $responseData['extra']['url'], $responseData['extra']['hqurl'], $responseData['extra']['thumbmediaid']);
				break;
			case 'news':	//回复图文
				$itemTpl = '<item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item>';
				$count = is_array($responseData['extra'])?count($responseData['extra']):0;
				$count = $count>10?10:$count;
				$arts = '';
				if(is_array($responseData['extra'])) {
					$i = 0;
					foreach($responseData['extra'] as $eachItem) {
						if($i > 9) break;
						++$i;
						if(!is_array($eachItem)) continue;
						$arts .= sprintf($itemTpl, $eachItem['title'], $eachItem['description'], $eachItem['picurl'], $eachItem['url']);
					}
				}
				if(!empty($arts) && $count > 0) {
 					$extra = sprintf('<ArticleCount>%s</ArticleCount><Articles>%s</Articles>', $count, $arts);	
				}
		}
		
		if(empty($extra)) return;
		
		$xmlStr = sprintf($format, $responseData['wechat'], $responseData['openid'], time()
			, $responseData['type'], $extra);
		if(self::$publicaccountinfo['signtype'] == 'AES') {
			//AES加密
			$xmlStr = WxUtil::encryptMsg($xmlStr, self::$publicaccountinfo['appid'], 
					self::$publicaccountinfo['token'], self::$publicaccountinfo['encodingaeskey']);
		}
		echo $xmlStr;
	}
	
	/**
	 * 记录日志
	 */
	static public function record() {
		/*
		if(self::$logTurnOn && !empty(self::$responseData)) {
			self::$responseData['extra'] = is_array(self::$responseData['extra'])?serialize(self::$responseData['extra']):'';
			$dbObj = new PublicAccountResponse;
			return $dbObj->addRecord(self::$responseData['openid'], self::$responseData['wechat']
					, self::$responseData['type'], self::$responseData['content'], self::$responseData['extra']);
		}
		*/
		return true;
		return false;
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