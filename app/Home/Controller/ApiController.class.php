<?php
/**
 *	微信公众号接入
 */
namespace Home\Controller;
use Think\Controller;
use Weixin\Wechat;

class ApiController extends Controller {
	//微信公众号接入
	public function weixinAction() {
		//公众号的OpenID
		$openID 	= I('get.openid', false);
		$signature	= I('get.signature', false);
		$timestamp	= I('get.timestamp', false);
		$nonce		= I('get.nonce', false);
		
		if(false === $openID
			|| false === $signature
			|| false === $timestamp
			|| false === $nonce
		) exit;
		
		//设置公众号ID
		Wechat::setOpenID($openID);
		if(Wechat::valid($timestamp, $nonce, $signature)) {
			if(Wechat::isFirstIn()) {
				//第一次接入
				$echostr 	= I('get.echostr', false);
				Wechat::firstInAnswer($echostr);
			} else {
				//回复消息
				Wechat::setPostData($GLOBALS["HTTP_RAW_POST_DATA"]);
				Wechat::response();
			}
		}
		exit;
	}
}