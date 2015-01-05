<?php
/**
 * 系统入口
 */

/**
 * 开启调试，正式环境下关闭
 */
define('APP_DEBUG', TRUE);

/**
 * 项目应用目录
 */
define('APP_PATH', './app/');

/**
 * 公共目录
 */
define('COMMON_PATH', './common/');

/**
 * 配置目录
 */
define('CONF_PATH', './config/');

/**
 * 语言目录
 */
define('LANG_PATH', './language/');

/**
 * 应用运行时目录
 */
define('RUNTIME_PATH', './runtime/');

/**
 * 应用数据目录
 */
define('DATA_PATH', './data/');

/**
 * 插件目录
 */
define('ADDON_PATH', './addons/');

/**
 * 系统框架路径
 */
define('THINK_PATH', realpath('../hyd-thinkphp').'/');

/**
 * 非调试情况下生成index.html文件
 */
define('BUILD_DIR_SECURE', !APP_DEBUG);

/**
 * 载入框架文件
 */
require THINK_PATH . '/ThinkPHP.php';