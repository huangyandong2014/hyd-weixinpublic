<?php
namespace Home\Controller;
use Think\Controller;
use Table\PublicAccountInfo;
use Weixin\Wechat;

class ApiController extends Controller {
	//微信公众号接入
	public function weixinAction() {
		$id = I('get.id', false);  	//公众号的OpenID
		
		$publicObj = new PublicAccountInfo;
		$publicInfo = $publicObj->getAccountInfo($id);
		if(!$publicInfo || '-1' == $publicInfo['status']) exit; //没找到，或者禁用
		
		$wechatObj = new Wechat($id, $publicInfo);
		if('0' == $publicInfo['status']) {
			//第一次接入
			$signature = I('get.signature');
			$timestamp = I('get.timestamp');
			$nonce = I('get.nonce');
			$echostr = I('get.echostr');
			if($wechatObj->valid($nonce, $timestamp, $signature))
				$wechatObj->successInterface($echostr);
			exit;
		} else {
			//回复消息
			$wechatObj->setHttpRawPostData($GLOBALS["HTTP_RAW_POST_DATA"])
				->response();
			exit;
		}
		exit;
	}
}