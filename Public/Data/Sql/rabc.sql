DROP TABLE IF EXISTS `oa_access`;
CREATE TABLE IF NOT EXISTS `oa_access` (
`role_id` smallint(6) unsigned NOT NULL,
`node_id` smallint(6) unsigned NOT NULL,
`level` tinyint(1) NOT NULL,
`module` varchar(50) DEFAULT NULL,
KEY `groupId` (`role_id`),
KEY `nodeId` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `oa_node`;
CREATE TABLE IF NOT EXISTS `oa_node` (
`id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(20) NOT NULL,
`title` varchar(50) DEFAULT NULL,
`status` tinyint(1) DEFAULT '0',
`remark` varchar(255) DEFAULT NULL,
`sort` smallint(6) unsigned DEFAULT NULL,
`pid` smallint(6) unsigned NOT NULL,
`level` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `level` (`level`),
KEY `pid` (`pid`),
KEY `status` (`status`),
KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `oa_role`;
CREATE TABLE IF NOT EXISTS `oa_role` (
`id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(20) NOT NULL,
`pid` smallint(6) DEFAULT NULL,
`status` tinyint(1) unsigned DEFAULT NULL,
`remark` varchar(255) DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `pid` (`pid`),
KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `oa_role_user`;
CREATE TABLE IF NOT EXISTS `oa_role_user` (
`role_id` mediumint(9) unsigned DEFAULT NULL,
`user_id` char(32) DEFAULT NULL,
KEY `group_id` (`role_id`),
KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `oa_user`;
CREATE TABLE `oa_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `emp_no` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(20) NOT NULL,
  `letter` varchar(10) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL,
  `salt` char(32) NOT NULL DEFAULT '0' COMMENT '加点盐',
  `company_no` varchar(32) DEFAULT NULL COMMENT '公司标识',
  `sex` varchar(50) NOT NULL,
  `birthday` int(11) DEFAULT '0',
  `login_count` int(8) DEFAULT '0' COMMENT '登录次数',
  `last_login_time` int(11) DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(40) DEFAULT NULL,
  `pic` varchar(200) DEFAULT 'Uploads/emp_pic/no_avatar.jpg' COMMENT '头像',
  `email` varchar(50) NOT NULL,
  `office_tel` varchar(20) NOT NULL,
  `mobile_tel` varchar(20) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  `is_del` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_emp_no` (`emp_no`),
  KEY `key_status` (`is_del`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `oa_user_info`;
CREATE TABLE `oa_user_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `emp_no` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(20) NOT NULL,
  `letter` varchar(10) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL,
  `salt` char(32) NOT NULL DEFAULT '0' COMMENT '加点盐',
  `company_no` varchat(32) NOT NULL DEFAULT '' COMMENT '公司标识',
  `dept_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL,
  `sex` varchar(50) NOT NULL,
  `birthday` int(11) DEFAULT '0',
  `login_count` int(8) DEFAULT '0' COMMENT '登录次数',
  `last_login_time` int(11) DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(40) DEFAULT NULL,
  `pic` varchar(200) DEFAULT 'Uploads/emp_pic/no_avatar.jpg' COMMENT '头象',
  `email` varchar(50) NOT NULL,
  `duty` varchar(2000) NOT NULL,
  `office_tel` varchar(20) NOT NULL,
  `mobile_tel` varchar(20) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  `is_del` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_emp_no` (`emp_no`),
  KEY `key_status` (`is_del`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;