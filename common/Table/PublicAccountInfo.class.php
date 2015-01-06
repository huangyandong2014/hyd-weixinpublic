<?php
/**
 * public_account_info表
 */
namespace Table;
use Table\BaseTable;

class PublicAccountInfo extends BaseTable {
	protected $tableName = 'public_account_info';
	
	/*添加公众号信息*/
	public function addAccount($openID, $name, $headimg, $wechat, $appID, $appSecret, $apiType=0, $type=0, $status = 0) {
		if(!$this->accountExists($openID)) {
			$addTime = time();
			$data = array(
				'openid'		=>	$openID
				, 'name'		=>	$name
				, 'headimg'		=>	$headimg
				, 'wechat'		=>	$wechat
				, 'type'		=>	intval($type)
				, 'appid'		=>	$appID
				, 'appsecret'	=>	$appSecret
				, 'apitype'		=>	$apiType
				, 'encodingaeskey'	=>	$this->makeAESKey($openID, $appID)
				, 'token'		=>	$this->makeToken($openID, $addTime)
				, 'status'		=>	$status
				, 'addtime'		=>	$addTime
			);
			return (boolean)$this->insert($data);
		}
		return false;
	}
	
	/*获取公众号信息*/
	public function getAccountInfo($openID) {
		$query = sprintf("SELECT * FROM `%s` WHERE `openid`='%s' LIMIT 1", $this->trueTableName, $openID);
		$results = $this->query($query);
		return is_array($results)&&count($results)==1?$results[0]:false;
	}
	
	/*判断公众号是否存在*/
	public function accountExists($openID) {
		$info = $this->getAccountInfo($openID);
		return is_array($info);
	}
	
	/*修改公众号相关信息，只允许修改name,headimg,wechat,type,appid,appsecret,status*/
	public function modifyAccountInfo($openID, $data) {
		if(!is_array($data))	return false;
		$allowFields = array('name','headimg','wechat','type','appid','appsecret','status');
		$realData = array();
		foreach($data as $k => $v) {
			if(in_array($k, $allowFields))	$realData[$k] = $v;
		}
		if(!empty($realData)) {
			$options['where'] = sprintf("`openid`='%s'", $openID);
			return (boolean)$this->update($realData, $options);
		}
		return false;
	}
	
	/*生成token*/
	public function makeToken($openID, $time, $extra='') {
		return md5($openID.$time.$extra.$openID);
	}
	
	/*生成encodingaeskey*/
	public function makeAESKey($openID, $extra='') {
		return md5($openID.$extra);
	}
	
	/*重新生成token*/
	public function resetToken($openID) {
		$time = time();
		$data = array('addtime'=>$time, 'token'=>$this->makeToken($openID, $time));
		$options['where'] = sprintf("`openid`='%s'", $openID);
		return (boolean)$this->update($data, $options);
	}
	
	/*重新生成EncodingAESKey*/
	public function resetAESKey($openID, $key) {
		$options['where'] = sprintf("`openid`='%s'", $openID);
		$data = array('encodingaeskey'=>$key);
		return (boolean)$this->update($data, $options);
	} 
	
	/*删除公众号*/
	public function deleteAccount($openID) {
		if(!$this->accountExists($openID))	return false;
		$options['where'] = sprintf("`openid`='%s'", $openID);
		return (boolean)$this->delete($options);
	}
	
	/*获取所有列表*/
	public function getAllAccountList() {
		return $this->select();
	}
}