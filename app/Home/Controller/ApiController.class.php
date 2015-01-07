<?php
/**
 *	公众号接入
 */
namespace Home\Controller;
use Think\Controller;
use Weixin\Wechat;
use Table\PublicAccountInfo;

class ApiController extends Controller {
	//微信公众号接入
	public function weixinAction() {
		//公众号的OpenID
		$openID 	= I('get.openid', false);
		$signature	= I('get.signature', '');
		$timestamp	= I('get.timestamp', '');
		$nonce		= I('get.nonce', '');
		$echostr 	= I('get.echostr', '');
		$rawData	= I('globals.HTTP_RAW_POST_DATA', '');
		
		//1.OpenID没传递
		if(false === $openID)	exit;
		
		//2.获取公众号信息
		$publicObj = new PublicAccountInfo;
		$info = $publicObj->getRecordByOpenID($openID);
		if(empty($info) || '-1' == $info['status']) exit; //不存在,或禁用
		
		//3.设置公众号接入相关信息
		Wechat::setOpenID($info['openid']);					//公众号OpenID
		Wechat::setName($info['name']);						//公众号名
		Wechat::setHeadImg($info['headimg']);				//头像URL地址
		Wechat::setWechat($info['wechat']);					//微信号
		Wechat::setType($info['type']);						//公众号类型，0没认证，1认证订阅号，2认证服务号
		Wechat::setAppID($info['appid']);					//APPID
		Wechat::setAppSecret($info['appsecret']);			//AppSecret
		Wechat::setToken($info['token']);					//Token
		Wechat::setEncodingAESKey($info['encodingaeskey']); //EncodingAESKey
		Wechat::setSignType($info['signtype']);				//消息体加密方式，0明文，1兼容，2安全
		Wechat::setStatus($info['status']);					//公众号状态，-1禁用，0未接入，1接入
		Wechat::setGetParams(array(
			'signature'		=>	$signature
			, 'timestamp'	=>	$timestamp
			, 'nonce'		=>	$nonce
			, 'echostr'		=>	$echostr
		));													//GET得到的参数
		Wechat::setPostParams($rawData);					//POST得到的参数
		Wechat::setRecordRequest(true);						//记录用户请求
		Wechat::setRecordResponse(true);					//记录回复消息
		
		if(Wechat::isFirstAnswer()) {
			//接口接入
			if(Wechat::isValid() && $publicObj->alterRecord($info['openid'],array('status'=>1))) {
				//更改登录状态成功则输出
				Wechat::firstAnswer();
			}
		} else {
			//消息回复
			Wechat::answer();
		}
		exit;
	}
}