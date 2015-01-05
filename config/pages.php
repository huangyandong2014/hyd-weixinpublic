<?php
/**
 * 一些页面设置
 */
return array(
	//404页面
	'URL_404_REDIRECT'		=>	''
	//错误定向页面
	, 'ERROR_PAGE'			=>  '' 
	//默认错误跳转对应的模板文件
	, 'TMPL_ACTION_ERROR'	=>  THINK_PATH.'Tpl/dispatch_jump.tpl' 
	//默认成功跳转对应的模板文件
	, 'TMPL_ACTION_SUCCESS'	=>  THINK_PATH.'Tpl/dispatch_jump.tpl' 
	//异常页面的模板文件
	, 'TMPL_EXCEPTION_FILE'	=>  THINK_PATH.'Tpl/think_exception.tpl'
);