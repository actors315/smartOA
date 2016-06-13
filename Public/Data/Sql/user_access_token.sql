-- ----------------------------
-- Table structure for oa_user_access_token
-- ----------------------------
DROP TABLE IF EXISTS `oa_user_access_token`;
CREATE TABLE `oa_user_access_token` (
  `userid` int(11) NOT NULL COMMENT 'userid',
  `access_token` varchar(128) NOT NULL COMMENT 'session_id',
  `ip` varchar(128) NOT NULL COMMENT 'ip地址',
  `user_agent` varchar(1024) NOT NULL COMMENT '终端信息',
  `last_activity` int(11) NOT NULL COMMENT '最后一次活跃时间戳',
  `lastdate` varchar(32) NOT NULL COMMENT '时间',
  `lastguid` varchar(128) NOT NULL COMMENT '全局唯一标识符',
  `invalidate` int(11) NOT NULL COMMENT '失效时间',
  UNIQUE KEY `userid` (`userid`),
  UNIQUE KEY `access_token` (`access_token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
