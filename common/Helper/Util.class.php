<?php
/**
 * 通用操作类
 */
namespace Helper;

class Util {
	/**
	 * 生成随即位数字符串
	 */
	static public function generateRandomForLength($length=10) {
		$base = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max = strlen($base)-1;
		$str = '';
		for($i=0;$i<$length;$i++) {
			$v = mt_rand(0, $max);
			$str .= $base[$v];
		}
		return $str;
	}
	
}