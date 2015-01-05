<?php
/**
 * 配置
 */
return array(
	/*URL设定*/
	'URL_CASE_INSENSITIVE'	=>  true		//不分大小写
	, 'URL_MODEL'			=>  URL_REWRITE	//Rewrite
	, 'URL_HTML_SUFFIX'		=>	'html'		//伪静态后缀
		
	/*默认设定*/
	, 'ACTION_SUFFIX'		=>	'Action' 	//方法名后缀
	, 'DEFAULT_THEME'		=>	'default'	//默认主题
			
	/* 模板引擎设置 */
	, 'TMPL_ENGINE_TYPE'	=>  'Smarty'	//默认模板引擎,Smarty
	, 'TMPL_L_DELIM'		=>  '{'			// 模板引擎普通标签开始标记
    , 'TMPL_R_DELIM'		=>  '}'			// 模板引擎普通标签结束标记
	, 'TMPL_CONTENT_TYPE'	=>  'text/html' // 默认模板输出类型
	, 'TMPL_DETECT_THEME'	=>  false       // 自动侦测模板主题
	, 'TMPL_TEMPLATE_SUFFIX'=>  '.tpl'     // 默认模板文件后缀
	
	/* 错误设置 */
	, 'ERROR_MESSAGE'		=>  '页面错误！请稍后再试～' //错误显示信息,非调试模式有效
	, 'SHOW_ERROR_MSG'		=>  false    // 显示错误信息
	, 'TRACE_MAX_RECORD'	=>  100    // 每个级别的错误信息 最大记录数
		
	/*加载扩展配置*/
	, 'LOAD_EXT_CONFIG'	=>	'db,cookie,log,pages'
);