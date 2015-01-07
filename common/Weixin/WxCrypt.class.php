<?php
/**
 * 微信公众加密/解密
 */
namespace Weixin;
use Think\Exception;
use Helper\Util;
use Helper\PKCS7Crypt;

class WxCrypt {
	/**
	 * 加密
	 */	
	static public function encrypt($text, $encodingaeskey, $appid) {
		$key = base64_decode($encodingaeskey.'=');
		try {
			//获得16位随机字符串，填充到明文之前
			$random = Util::generateRandomForLength(16);
			$text = $random . pack("N", strlen($text)) . $text . $appid;
			// 网络字节序
			$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
			$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
			$iv = substr($key, 0, 16);
			//使用自定义的填充方式对明文进行补位填充
			$text = PKCS7Crypt::encode($text, 32);
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
	 */
	static public function decrypt($encrypted, $encodingaeskey, $appid) {
		$key = base64_decode($encodingaeskey.'=');
		try {
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
			$result = PKCS7Encoder::decode($decrypted);
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
}