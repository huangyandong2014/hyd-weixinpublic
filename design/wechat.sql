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
	`status` tinyint(1) DEFAULT 0 COMMENT '状态，-1禁用，0未接入，1接入',
	`addtime` int(11) NOT NULL DEFAULT 0 COMMENT '添加时间戳',
	PRIMARY KEY(`id`)
);

-- 2. wechat request log
CREATE TABLE `wx_public_account_request`(
	`id`
	`openid`
	`wechat`
	`type`
	`content`
	`extra`
	`addtime`
);