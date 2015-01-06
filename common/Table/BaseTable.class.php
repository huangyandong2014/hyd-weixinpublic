<?php
/**
 * 表操作基础类
 */
namespace Table;
use Think\Db;

class BaseTable {
	protected $tableName = '';		//不带前缀的数据表名,子类必须设置该值
	protected $tablePrefix = null; 	//表前缀
	protected $trueTableName = '';	//完整表名
	
	protected $dh = null;			//数据库操作对象
	
	public function __construct() {
		$this->setTablePrefix()->setTrueTableName()->db();
	}
	
	//1.查询，返回数据集
	public function query($str,$fetchSql=false) {
		if(!$this->dh)	return false;
		return $this->dh->query($str,$fetchSql);
	}
	
	//2.执行
	public function execute($str,$fetchSql=false) {
		if(!$this->dh)	return false;
		return $this->dh->execute($str,$fetchSql);
	}
	
	//3.插入数据
	public function insert($data,$options=array(),$replace=false) {
		if(!$this->dh)	return false;
		$options['table'] = $options['table']?$options['table']:$this->trueTableName;
		return $this->dh->insert($data,$options,$replace);
	}
	
	//4.批量插入
	public function batchInsert($dataSet,$options=array(),$replace=false) {
		if(!$this->dh)	return false;
		$options['table'] = $options['table']?$options['table']:$this->trueTableName;
		return $this->dh->batchInsert($dataSet,$options,$replace);
	}
	
	//5.选择插入
	public function selectInsert($fields,$table,$options=array()) {
		if(!$this->dh)	return false;
		$options['table'] = $options['table']?$options['table']:$this->trueTableName;
		return $this->dh->selectInsert($fields,$table,$options);
	}
	
	//6.更新
	public function update($data,$options) {
		if(!$this->dh)	return false;
		$options['table'] = $options['table']?$options['table']:$this->trueTableName;
		return $this->dh->update($data,$options);
	}
	
	//7.删除
	public function delete($options=array()) {
		if(!$this->dh)	return false;
		$options['table'] = $options['table']?$options['table']:$this->trueTableName;
		return $this->dh->delete($options);
	}
	
	//8.查找记录
	public function select($options=array()) {
		if(!$this->dh)	return false;
		$options['table'] = $options['table']?$options['table']:$this->trueTableName;
		return $this->dh->select($options);
	}
	
	//9.生成SQL
	public function buildSelectSql($options=array()) {
		if(!$this->dh)	return false;
		$options['table'] = $options['table']?$options['table']:$this->trueTableName;
		return $this->dh->buildSelectSql($options);
	}
	
	//连接数据库
	public function db() {
		if($this->dh)	return $this->dh;
		$this->dh = Db::getInstance();
		return $this;
	}
	
	//设置表前缀
	public function setTablePrefix() {
		if(is_null($this->tablePrefix)) {
			$this->tablePrefix = C('DB_PREFIX', NULL, '');
		}
		return $this;
	}
	
	//获得表前缀
	public function getTablePrefix() {
		return $this->tablePrefix;
	}
	
	//设置完整表名
	public function setTrueTableName() {
		if(empty($this->trueTableName)) {
			//子类中没有设置，则自动获取前缀来设置
			$this->trueTableName = $this->tablePrefix.$this->tableName;
		}
		return $this;
	}
	
	//获得真实表名
	public function getTrueTableName() {
		return $this->trueTableName;
	}
	
	public function __destruct() {
		$this->tableName = null;
		$this->tablePrefix = null;
		$this->trueTableName = null;
		$this->dh = null;
	}
}