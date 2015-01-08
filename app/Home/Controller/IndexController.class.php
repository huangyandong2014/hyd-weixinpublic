<?php
namespace Home\Controller;
use Think\Controller;
use Weixin\WxCrypt;
use Weixin\WxUtil;

class IndexController extends Controller {
    public function indexAction(){
    	$appID = "wx12e0e608e4447018";
    	$token = "38D9D791F050940FA6F90BB5D18C9B10";
    	$encodingAesKey = "g13VoR6YIzAxTYgrg1VXV1L9nZeIdWXM8lESHh489FE";
    	$e = WxUtil::encryptMsg("eeee Hello WOrld", $appID, $token, $encodingAesKey);
    	var_dump($e);
    	//var_dump(WxUtil::decryptMsg($rawPostData, $msgSignature, $timestamp, $nonce, $appID, $token, $encodingAesKey))
    }
}