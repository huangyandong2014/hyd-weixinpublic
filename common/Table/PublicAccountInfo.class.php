<?php
/**
 * public_account_info表
 */
namespace Table;
use Table\BaseTable;
use Helper\Util;

class PublicAccountInfo extends BaseTable {
	protected $tableName = 'public_account_info';
	
	/*获取列表*/
	public function getList() {
		return $this->select();
	}
	
	/*根据OpenID获取记录*/
	public function getRecordByOpenID($openID) {
		$options['where'] = sprintf("`openid`='%s'", $openID);
		$options['limit'] = '1';
		$results = $this->select($options);
		return is_array($results)&&count($results)==1?$results[0]:false;
	}
	
	/*是否存在*/
	public function existsOpenID($openID) {
		$info = $this->getRecordByOpenID($openID);
		return !empty($info);
	}
	
	/**
	 * 添加记录
	 * 	$type 公众号类型，0未认证，1认证订阅号，2认证服务号
	 * 	$encodingaeskey EncodingAESKey,固定为43个字符，a-z，A-Z，0-9
	 *  $signtype API接口加密方式，0明文，1兼容，2安全模式
	 *  $status 状态，-1禁用，0未接入，1接入
	 */
	public function addRecord($openID, $name, $headimg, $wechat, $appID, $appSecret
			, $type=0, $signtype=0, $status=0, $token=false, $encodingaeskey=false ) {
		if($this->existsOpenID($openID))	return false;
		$data = array(
			'openid'		=>	$openID
			, 'name'		=>	$name
			, 'headimg'		=>	$headimg
			, 'wechat'		=>	$wechat
			, 'type'		=>	intval($type)
			, 'appid'		=>	$appID
			, 'appsecret'	=>	$appSecret
			, 'encodingaeskey'=>(false===$encodingaeskey?Util::generateRandomForLength(43):$encodingaeskey)
			, 'token'		=>	(false===$token?$this->generateToken($openID,$wechat,$appID,time()):$token)
			, 'signtype'	=>	intval($signtype)
			, 'status'		=>	intval($status)
			, 'addtime'		=>	time()	
		);
		return (boolean)$this->insert($data);
	}
		
	/**
	 * 修改记录
	 */
	public function alterRecord($openID, $data) {
		if(!is_array($data))	return false;
		if(!$this->existsOpenID($openID))	return false;
		$options['where'] = sprintf("`openid`='%s'", $openID);
		$options['limit'] = '1';
		return (boolean)$this->update($data, $options);
	}
	
	/**
	 * 删除记录
	 */
	public function deleteRecord($openID) {
		if(!$this->existsOpenID($openID))	return false;
		$options['where'] = sprintf("`openid`='%s'", $openID);
		return (boolean)$this->delete($options);
	}
	
	/**
	 * 生成Token
	 */
	public function generateToken($openID,$wechat,$appID,$time) {
		return md5($openID.$wechat.$appID.$time.$openID);
	}
}