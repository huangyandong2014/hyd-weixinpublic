<?php
/**
 * PKCS#7算法类
 * 参考: http://tools.ietf.org/html/rfc2315
 *
 */
namespace Helper;

class PKCS7 {
	/**
	 * 对需要加密的明文进行填充补位
	 * @param $text 需要进行填充补位操作的明文
	 * @return 补齐明文字符串
	 */
	static public function encode($text, $block_size=32) {
		$text_length = strlen($text);
		//需要补位的位数
		$amount_to_pad = $block_size - ($text_length % $block_size);
		$amount_to_pad = 0 == $amount_to_pad ? $block_size : $amount_to_pad;
		//获得补位所用的字符
		$pad_chr = chr($amount_to_pad);
		$tmp = '';
		for($i=0;$i<$amount_to_pad;$i++) {
			$tmp .= $pad_chr;
		}
		return $text.$tmp;
	}
	
	/**
	 * 对解密后的明文进行补位删除
	 * @param decrypted 解密后的明文
	 * @return 删除填充补位后的明文
	 */
	static public function decode($text, $block_size=32) {
		$pad = ord(substr($text, -1));  //获取最后一位字符的ASCII码
		$pad = ($pad < 1 || $pad > $block_size)?0:$pad;
		return substr($text, 0, (strlen($text) - $pad));
	}
}