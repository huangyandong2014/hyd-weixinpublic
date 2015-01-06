-- 1. wechat public account infomation 
CREATE TABLE `wx_public_account_info`(
	`id` int unsigned AUTO_INCREMENT COMMENT '自增ID',
	`openid` varchar(255) NOT NULL COMMENT '微信公众号OpenID',
	`name`	varchar(55) NOT NULL COMMENT '微信公众号名',
	`headimg` varchar(255)	NOT NULL COMMENT '微信公众号头像URL地址',
	`wechat` varchar(100) NOT NULL COMMENT '微信号',
	`type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '公众号类型，0未认证，1认证订阅号，2认证服务号',
	`appid` varchar(255) NOT NULL COMMENT 'APPID',
	`appsecret`	varchar(255) NOT NULL COMMENT 'APPSecret',
	`encodingaeskey` varchar(255) COMMENT 'EncodingAESKey',
	`token` varchar(255) NOT NULL COMMENT 'Token',
	`apitype` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'API接口加密方式，0明文，1兼容，2安全模式',
	`status` tinyint(1) DEFAULT 0 COMMENT '状态，-1禁用，0未接入，1接入',
	`addtime` int(11) NOT NULL DEFAULT 0 COMMENT '添加时间戳',
	PRIMARY KEY(`id`)
);

-- 2. wechat public request log
CREATE TABLE `wx_public_account_request`(
	`id` int unsigned AUTO_INCREMENT COMMENT '自增ID',
	`openid` varchar(255) NOT NULL COMMENT '微信公众号OpenID',
	`wechat` varchar(100) NOT NULL COMMENT '发送消息的微信号',
	`type` varchar(55) NOT NULL DEFAULT 'text' COMMENT '消息类型',
	`content` text COMMENT '消息主要内容',
	`extra` text COMMENT '消息额外内容',
	`addtime` int(11) NOT NULL DEFAULT 0 COMMENT '添加时间戳',
	PRIMARY KEY(`id`)
);

-- 3. wechat menu
CREATE TABLE `wx_wechat_menu`(
	`id` int unsigned AUTO_INCREMENT COMMENT '自增ID',
	`name` varchar(55) NOT NULL COMMENT '菜单名',
	`label` varchar(55) NOT NULL COMMENT '菜单标识符',
	`type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '菜单执行类型，0为本地类执行，1为URL执行',
	`execconfig` text NOT NULL COMMENT '执行命令配置',
	`displayorder` tinyint(3) NOT NULL DEFAULT 0 COMMENT '排序，数字越小越靠前',
	`status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '启用状态，1启用，0关闭',
	PRIMARY KEY(`id`) 
);

-- 4. wechat public menu
CREATE TABLE `wx_public_account_menu`(
	`id` int unsigned AUTO_INCREMENT COMMENT '自增ID',
	`openid` varchar(255) NOT NULL COMMENT '微信公众号OpenID',
	`menuid` int unsigned NOT NULL COMMENT '菜单ID',
	`helplabel` varchar(55) NOT NULL COMMENT '帮助标识符',
	`helpmesg` varchar(255) NOT NULL COMMENT '帮助消息',
	`status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态，1启用，0停用',
	PRIMARY KEY(`id`)
);

-- 5. wechat public response log
CREATE TABLE `wx_public_account_response`(
	`id` int unsigned AUTO_INCREMENT COMMENT '自增ID',
	`openid` varchar(255) NOT NULL COMMENT '微信公众号OpenID',
	`wechat` varchar(100) NOT NULL COMMENT '接收消息的微信号',
	`type` varchar(55) NOT NULL DEFAULT 'text' COMMENT '消息类型',
	`content` text COMMENT '消息主要内容',
	`extra` text COMMENT '消息额外内容',
	`addtime` int(11) NOT NULL DEFAULT 0 COMMENT '添加时间戳',
	PRIMARY KEY(`id`)
);