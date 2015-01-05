<?php
/**
 * Cookie和Session配置
 */
return array(
	/*Cookie设定*/
	'COOKIE_EXPIRE'	=>  (30 * 24 * 60 * 60) 	// Cookie有效期,30天
	, 'COOKIE_DOMAIN'	=>  '.513cha.cn'    	// Cookie有效域名
	, 'COOKIE_PATH'		=>  '/'     			// Cookie路径
	, 'COOKIE_PREFIX'	=>  'tea_'     			// Cookie前缀 避免冲突
			
	/*Session设定*/
	, 'SESSION_AUTO_START'=>true    // 是否自动开启Session
	, 'SESSION_OPTIONS'	=>  array(
		'EXPIRE'	=>	(1 * 24 * 60 * 60) //过期时间,1天
		, 'DOMAIN'	=>	'.513cha.cn'
		, 'PATH'	=>	'/'
	) 
	, 'SESSION_PREFIX'	=>  'tea_' // session 前缀
);