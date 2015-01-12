<?php
/**
 *	实用函数类
 */
namespace Weixin;
use Helper\Snoopy;
use Weixin\WxCrypt;
use Helper\Util;
use \DOMDocument;
use ThinkPHP\Exception;

class WxUtil {
	/**
	 * 获得token
	 * 	返回: false|array('access_token'=>'xxx', 'expires_in'=>7200)
	 */
	static public function getToken($appID, $appSecret) {
		if(empty($appID)||empty($appSecret)) return false;
		$url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s'
			, $appID, $appSecret);
		$snoopy = new Snoopy;
		if($snoopy->fetch($url)) {
			$jsonStr = $snoopy->results;
			$json = json_decode($jsonStr, true);
			if($json['access_token'])	
				return array(
					'token'		=>	$json['access_token']
					, 'expires'	=>	$json['expires_in']
				);
			else return false;
		} else {
			return false;
		}
	}
	
	/**
	 * 获取微信服务器IP地址
	 */
	static public function getServerList($token) {
		if(empty($token))	return false;
		$url = sprintf('https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=%s', $token);
		$snoopy = new Snoopy;
		if(!$snoopy->fetch($url)) return false;
		$json = json_decode($snoopy->results, true);
		return !empty($json['ip_list'])?$json['ip_list']:false;
	}
	
	/**
	 * 上传多媒体文件(未测试)
	 * 	$fileType	多媒体文件类型:分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb） 
	 * 	$mediaFile	form-data中媒体文件标识，有filename、filelength、content-type等信息 
	 * 	
	 * 	上传的多媒体文件有格式和大小限制，如下：
	 *  	图片（image）: 1M，支持JPG格式
	 *  	语音（voice）：2M，播放长度不超过60s，支持AMR\MP3格式
	 *  	视频（video）：10MB，支持MP4格式
	 *  	缩略图（thumb）：64KB，支持JPG格式 
	 */
	static public function uploadFile($fileType, $mediaFile, $token) {
		$url = 	sprintf('http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=%s&type=%s'
					, $token, $fileType);
		$postData['media'] = $mediaFile;
		$snoopy = new Snoopy;
		if($snoopy->submit($url, $postData)) {
			return $snoopy->results;
		} else {
			return false;
		}
	}
	
	/**
	 * 下载媒体文件(未测试)
	 */
	static public function downloadFile($mediaID, $token) {
		$url = sprintf('http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s'
				, $token, $mediaID);
		$snoopy = new Snoopy;
		if($snoopy->fetch($$url)) {
			return $snoopy->results;
		} else {
			return false;
		}
	}
	
	/**
	 * 解密发送给公众号的消息体
	 * 	成功后返回解密后的XML字符串
	 */
	static public function decryptMsg($rawPostData, $msgSignature, $timestamp, $nonce, $appID, $token, $encodingAesKey) {
		if(empty($rawPostData) || empty($msgSignature) || empty($timestamp) || empty($appID) || empty($token) || empty($encodingAesKey)) return false;
		try {
			$xml = new DOMDocument;
			$xml->loadXML($rawPostData);
			$encrypt= $xml->getElementsByTagName('Encrypt')->item(0)->nodeValue;
			//验证安全签名
			$tmpArr = array($encrypt, $token, $timestamp, $nonce);
			sort($tmpArr, SORT_STRING);
			$tmpStr = implode($tmpArr);	
			if(sha1($tmpStr) != $msgSignature)	return false;
			return WxCrypt::decrypt($encrypt, $encodingAesKey, $appID);
		} catch(Exception $e) {
			return false;
		}
	}
	
	/**
	 * 加密消息发送给公众号服务器
	 * 	返回加密后的XML字符串
	 */
	static public function encryptMsg($text, $appID, $token, $encodingAesKey) {
		if(empty($text) || empty($appID) || empty($token) || empty($encodingAesKey)) return false;
		$encrypt = WxCrypt::encrypt($text, $encodingAesKey, $appID);
		if(empty($encrypt)) return false;
		$timestamp = time();
		$nonce = Util::generateRandomForLength(10);
		
		//生成安全签名
		$tmpArr = array($encrypt, $token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$msg_signature = sha1($tmpStr);
		
		$xmlTpl = '<xml>';
		$xmlTpl .= '<Encrypt><![CDATA[%s]]></Encrypt>';
		$xmlTpl .= '<MsgSignature><![CDATA[%s]]></MsgSignature>';
		$xmlTpl .= '<TimeStamp>%s</TimeStamp>';
		$xmlTpl .= '<Nonce><![CDATA[%s]]></Nonce>';
		$xmlTpl .= '</xml>';
		
		return sprintf($xmlTpl, $encrypt, $msg_signature, $timestamp, $nonce);
	}
	
	/**
	 * 判断是否在微信浏览器里
	 */
	static public function isWeixinBrowser() {
		$agent = $_SERVER ['HTTP_USER_AGENT'];
		return (boolean)strpos($agent, "icroMessenger");
	}
	
}