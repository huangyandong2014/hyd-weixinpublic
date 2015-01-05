<?php
/**
 * 日志
 */
return array(
	'LOG_RECORD'			=>  true   // 记录日志
	, 'LOG_TYPE'			=>  'File' // 日志记录类型 默认为文件方式
	, 'LOG_LEVEL'			=>  'EMERG,ALERT,CRIT,ERR,WARN,NOTIC,INFO,DEBUG,SQL'// 允许记录的日志级别
	, 'LOG_FILE_SIZE' 		=>  2097152	// 日志文件大小限制
	, 'LOG_EXCEPTION_RECORD'=>  false   // 是否记录异常信息日志
);