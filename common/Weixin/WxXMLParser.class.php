<?php
/**
 *	微信XML解析和生成
 */
namespace Weixin;
use \DOMDocument;
use \Exception;

class WxXMLParser {
	/**
	 * 解码加密消息
	 */
	static public function decodeCryptXMLStr($xmlStr) {
		if(empty($xmlStr))	return false;
		try {
			$xml = new DOMDocument;
			$xml->loadXML($xmlStr);
			$encryptObj = $xml->getElementsByTagName('Encrypt');
			$openIDObj = $xml->getElementsByTagName('ToUserName');
			return array(
				'openid'	=>	$openIDObj->item(0)->nodeValue
				, 'encrypt'	=>	$encryptObj->item(0)->nodeValue
			);
		} catch(Exception $e) {
			return false;
		}
	}
	
	/**
	 * 编码加密消息
	 */
	static public function encodeCryptXMLStr($encrypt, $signature, $timestamp, $nonce) {
		if(empty($encrypt))	return false;
		$xmlTpl = '<xml>';
		$xmlTpl .= '<Encrypt><![CDATA[%s]]></Encrypt>';
		$xmlTpl .= '<MsgSignature><![CDATA[%s]]></MsgSignature>';
		$xmlTpl .= '<TimeStamp>%s</TimeStamp>';
		$xmlTpl .= '<Nonce><![CDATA[%s]]></Nonce>';
		$xmlTpl .= '</xml>';
		return sprintf($xmlTpl, $encrypt, $signature, $timestamp, $nonce);
	}
	
	/**
	 * 解码非加密信息
	 */
	static public function decodePlainXMLStr($xmlStr) {
		
	}
	
	/**
	 * 编码非加密信息
	 */
	static public function encodePlainXmlStr($info) {
		
	}
}
