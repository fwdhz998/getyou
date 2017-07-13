
CREATE TABLE `Mini_User` (
      `UserID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'UserID',
      `username` varchar(100) NOT NULL DEFAULT '' COMMENT '用户名',
      `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
      `nickname` varchar(50) DEFAULT '' COMMENT '昵称',
      `QQ` varchar(64) NOT NULL DEFAULT '' COMMENT 'QQ',
      `phone_number` varchar(64) NOT NULL DEFAULT '' COMMENT '手机',
      `self_introduction` varchar(32) DEFAULT NULL COMMENT '自我介绍',
      `tags` varchar(32) DEFAULT NULL COMMENT '标签',
      
      PRIMARY KEY (`UserID`),
      UNIQUE (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Mini_Bubble` (
      `BubbleID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'BubbleID',
      `UserID` int(10) unsigned NOT NULL COMMENT 'UserID',

     
      `longtitude` varchar(50) DEFAULT '' COMMENT '经度',
      `latitude` varchar(64) NOT NULL DEFAULT '' COMMENT '纬度',
      `bu_question` varchar(32) DEFAULT NULL COMMENT '问题',
      `bu_answer` varchar(32) DEFAULT NULL COMMENT '答案',
      `sex` TINYINT(1) DEFAULT '0' COMMENT '性别:1男0女',

      'num_to_stick' int(11) DEFAULT 3 COMMENT '允许戳破该泡泡的人数',
      `exist_flag` TINYINT(1) DEFAULT 1 COMMENT '状态，1：未戳破.0:已戳破',
      `bu_create_time` datetime default getdate() COMMENT '创建时间',
      PRIMARY KEY (`BubbleID`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Mini_friendship` (
      `frID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'frID',
      `FromID` int(10) unsigned NOT NULL COMMENT 'FromID',
      `ToID` int(10) unsigned NOT NULL COMMENT 'ToID',
      PRIMARY KEY (`frID`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





-- ----------------------------
-- Table structure for `phalapi_user_login_qq`
-- ----------------------------
DROP TABLE IF EXISTS `phalapi_user_login_qq`;
CREATE TABLE `phalapi_user_login_qq` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `qq_openid` varchar(28) DEFAULT '' COMMENT 'QQ的OPENID',
  `qq_token` varchar(150) DEFAULT '' COMMENT 'QQ的TOKEN',
  `qq_expires_in` int(10) DEFAULT '0' COMMENT 'QQ的失效时间',
  `user_id` bigint(10) DEFAULT '0' COMMENT '绑定的用户ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `phalapi_user_session_0` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) DEFAULT '0' COMMENT '用户id',
      `token` varchar(64) DEFAULT '' COMMENT '登录token',
      `client` varchar(32) DEFAULT '' COMMENT '客户端来源',
      `times` int(6) DEFAULT '0' COMMENT '登录次数',
      `login_time` int(11) DEFAULT '0' COMMENT '登录时间',
      `expires_time` int(11) DEFAULT '0' COMMENT '过期时间',
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `phalapi_user_session_1` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) DEFAULT '0' COMMENT '用户id',
      `token` varchar(64) DEFAULT '' COMMENT '登录token',
      `client` varchar(32) DEFAULT '' COMMENT '客户端来源',
      `times` int(6) DEFAULT '0' COMMENT '登录次数',
      `login_time` int(11) DEFAULT '0' COMMENT '登录时间',
      `expires_time` int(11) DEFAULT '0' COMMENT '过期时间',
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_2` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) DEFAULT '0' COMMENT '用户id',
      `token` varchar(64) DEFAULT '' COMMENT '登录token',
      `client` varchar(32) DEFAULT '' COMMENT '客户端来源',
      `times` int(6) DEFAULT '0' COMMENT '登录次数',
      `login_time` int(11) DEFAULT '0' COMMENT '登录时间',
      `expires_time` int(11) DEFAULT '0' COMMENT '过期时间',
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_3` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) DEFAULT '0' COMMENT '用户id',
      `token` varchar(64) DEFAULT '' COMMENT '登录token',
      `client` varchar(32) DEFAULT '' COMMENT '客户端来源',
      `times` int(6) DEFAULT '0' COMMENT '登录次数',
      `login_time` int(11) DEFAULT '0' COMMENT '登录时间',
      `expires_time` int(11) DEFAULT '0' COMMENT '过期时间',
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_4` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) DEFAULT '0' COMMENT '用户id',
      `token` varchar(64) DEFAULT '' COMMENT '登录token',
      `client` varchar(32) DEFAULT '' COMMENT '客户端来源',
      `times` int(6) DEFAULT '0' COMMENT '登录次数',
      `login_time` int(11) DEFAULT '0' COMMENT '登录时间',
      `expires_time` int(11) DEFAULT '0' COMMENT '过期时间',
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_5` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) DEFAULT '0' COMMENT '用户id',
      `token` varchar(64) DEFAULT '' COMMENT '登录token',
      `client` varchar(32) DEFAULT '' COMMENT '客户端来源',
      `times` int(6) DEFAULT '0' COMMENT '登录次数',
      `login_time` int(11) DEFAULT '0' COMMENT '登录时间',
      `expires_time` int(11) DEFAULT '0' COMMENT '过期时间',
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_6` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) DEFAULT '0' COMMENT '用户id',
      `token` varchar(64) DEFAULT '' COMMENT '登录token',
      `client` varchar(32) DEFAULT '' COMMENT '客户端来源',
      `times` int(6) DEFAULT '0' COMMENT '登录次数',
      `login_time` int(11) DEFAULT '0' COMMENT '登录时间',
      `expires_time` int(11) DEFAULT '0' COMMENT '过期时间',
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_7` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) DEFAULT '0' COMMENT '用户id',
      `token` varchar(64) DEFAULT '' COMMENT '登录token',
      `client` varchar(32) DEFAULT '' COMMENT '客户端来源',
      `times` int(6) DEFAULT '0' COMMENT '登录次数',
      `login_time` int(11) DEFAULT '0' COMMENT '登录时间',
      `expires_time` int(11) DEFAULT '0' COMMENT '过期时间',
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_8` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) DEFAULT '0' COMMENT '用户id',
      `token` varchar(64) DEFAULT '' COMMENT '登录token',
      `client` varchar(32) DEFAULT '' COMMENT '客户端来源',
      `times` int(6) DEFAULT '0' COMMENT '登录次数',
      `login_time` int(11) DEFAULT '0' COMMENT '登录时间',
      `expires_time` int(11) DEFAULT '0' COMMENT '过期时间',
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_9` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) DEFAULT '0' COMMENT '用户id',
      `token` varchar(64) DEFAULT '' COMMENT '登录token',
      `client` varchar(32) DEFAULT '' COMMENT '客户端来源',
      `times` int(6) DEFAULT '0' COMMENT '登录次数',
      `login_time` int(11) DEFAULT '0' COMMENT '登录时间',
      `expires_time` int(11) DEFAULT '0' COMMENT '过期时间',
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

