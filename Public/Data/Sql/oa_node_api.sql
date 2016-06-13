-- ----------------------------
-- Table structure for oa_node_api
-- ----------------------------
DROP TABLE IF EXISTS `oa_node_api`;
CREATE TABLE `oa_node_api` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `service_name` varchar(32) NOT NULL,
  `operate_name` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '接口类型，0为公开接口不需要权限控制，1需权限控制',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `service_operate` (`service_name`,`operate_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;