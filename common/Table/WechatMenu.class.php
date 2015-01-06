<?php
/**
 * wechat_menu
 */
namespace Table;
use Table\BaseTable;

class WechatMenu extends BaseTable {
	protected $tableName = 'wechat_menu';
	
	/**
	 * 添加菜单项
	 */
	public function addMenuItem($label, $name, $execconfig, $type = 0, $displayorder = 0, $status = 1) {
		if($type == 0)	return $this->addLocalMenuItem($label, $name, $execconfig, $displayorder, $status);
		else return $this->addRemoteMenuItem($label, $name, $execconfig, $displayorder, $status);
	}
	
	/**
	 * 添加本地执行菜单项
	 * 	$label	菜单项标识
	 * 	$name	菜单项名
	 * 	$execconfig	本地类配置数组，包含以下key
	 * 		class	类名
	 * 		method	方法名
	 * 		params	参数数组	
	 * 	$displayorder 排序 
	 * 	$status 启用状态
	 */
	public function addLocalMenuItem($label, $name, $execconfig, $displayorder = 0, $status = 1) {
		
	}
	
	/**
	 * 添加URL执行菜单
	 *	$label	菜单项标识
	 *	$name	菜单项名
	 *	$execconfig	远程URL配置数组，包含以下key
	 *		url		URL地址
	 *		method	请求方法，默认为GET
	 *		function生成请求参数的函数,该函数，默认接收唯一参数，请求数组
	 *  $displayorder 排序 
	 * 	$status 启用状态
	 */
	public function addRemoteMenuItem($label, $name, $execconfig, $displayorder = 0, $status = 1) {
		
	}
	
	/**
	 * 获取菜单项信息
	 */
	public function getMenuItemInfo($label) {
		
	}
	
	/**
	 * 菜单项是否存在
	 */
	public function menuItemExists($label) {
		
	}
	
	/**
	 * 获取菜单项列表
	 */
	public function getMenuItemList() {
		
	}
	
	/**
	 * 修改菜单项信息
	 */
	public function modifyMenuItem($label, $data) {
		
	}
	
	/**
	 * 删除菜单项
	 */
	public function deleteMenuItem($label) {
		
	}
}