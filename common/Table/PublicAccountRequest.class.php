<?php
/**
 * public_account_request表
 */
namespace Table;
use Table\BaseTable;

class PublicAccountRequest extends BaseTable {
	protected $tableName = 'public_account_request';
	
	/*获取列表*/
	public function getList() {
		return $this->select();
	}
	
	/*根据ID获取记录*/
	public function getRecordByID($id) {
		$options['where'] = sprintf("`id`='%s'", $id);
		$options['limit'] = '1';
		$results = $this->select($options);
		return is_array($results)&&count($results)==1?$results[0]:false;
	}
	
	/*是否存在*/
	public function existsID($id) {
		$info = $this->getRecordByID($openID);
		return !empty($info);
	}
	
	/**
	 * 添加记录
	 *		
	 */
	public function addRecord($openID, $wechat, $type, $content, $extra) {
		if($this->existsOpenID($openID))	return false;
		$data = array(
			'openid'		=>	$openID
			, 'wechat'		=>	$wechat
			, 'type'		=>	$type
			, 'content'		=>	$content
			, 'extra'		=>	$extra
			, 'addtime'		=>	time()
		);
		return (boolean)$this->insert($data);
	}
	
	/**
	 * 修改记录
	 */
	public function alterRecord($id, $data) {
		if(!is_array($data))	return false;
		if(!$this->existsID($id))	return false;
		$options['where'] = sprintf("`id`='%s'", $id);
		$options['limit'] = '1';
		return (boolean)$this->update($data, $options);
	}
	
	/**
	 * 删除记录
	 */
	public function deleteRecord($id) {
		if(!$this->existsID($id))	return false;
		$options['where'] = sprintf("`id`='%s'", $id);
		return (boolean)$this->delete($options);
	}
}