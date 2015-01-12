<?php
/**
 * 微信公众加密/解密
 */
namespace Weixin;
use Think\Exception;
use Helper\Util;
use Helper\PKCS7;

class WxCrypt {
	/**
	 * 加密
	 * 	$text				要加密的文本
	 * 	$encodingaeskey		43位长度EncodingAESKey
	 * 	$appID				应用ID	
	 * @return string
	 */	
	static public function encrypt($text, $encodingaeskey, $appid) {
		try {
			$key = self::_aesKey($encodingaeskey);
			//16位随机字符+文本长度值得4为网络字节数+消息文本+appID
			$text = Util::generateRandomForLength(16).pack("N",strlen($text)).$text.$appid;
			// 网络字节序
			$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
			$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
			$iv = substr($key, 0, 16);
			//使用自定义的填充方式对明文进行32块大小补位填充
			$text = PKCS7::encode($text, 32);
			mcrypt_generic_init($module, $key, $iv);
			//加密
			$encrypted = mcrypt_generic($module, $text);
			mcrypt_generic_deinit($module);
			mcrypt_module_close($module);
			return base64_encode($encrypted);
		} catch(Exception $e) {
			return "";
		}
	}
	
	/**
	 * 解密
	 * 
	 */
	static public function decrypt($encrypted, $encodingaeskey, $appid) {
		try {
			$key = self::_aesKey($encodingaeskey);
			//使用BASE64对需要解密的字符串进行解码
			$ciphertext_dec = base64_decode($encrypted);
			$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
			$iv = substr($key, 0, 16);
			mcrypt_generic_init($module, $key, $iv);
		
			//解密
			$decrypted = mdecrypt_generic($module, $ciphertext_dec);
			mcrypt_generic_deinit($module);
			mcrypt_module_close($module);
			
			//去除补位字符
			$result = PKCS7::decode($decrypted, 32);
			//去除16位随机字符串,网络字节序和AppId
			if (strlen($result) < 16) return "";
			$content = substr($result, 16, strlen($result));
			$len_list = unpack("N", substr($content, 0, 4));
			$xml_len = $len_list[1];
			$xml_content = substr($content, 4, $xml_len);
			$from_appid = substr($content, $xml_len + 4);
			return $from_appid==$appid?$xml_content:"";
		} catch (Exception $e) {
			return "";
		}
	}
	
	/**
	 * 辅助生成AESKey
	 */
	static private function _aesKey($encodingAesKey) {
		return base64_decode($encodingAesKey.'=');
	}
}