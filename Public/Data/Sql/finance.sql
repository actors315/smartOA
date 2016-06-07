DROP TABLE IF EXISTS `oa_finance_account`;
CREATE TABLE `oa_finance_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '�ʺ�����',
  `bank` varchar(20) DEFAULT NULL COMMENT '����',
  `no` varchar(50) DEFAULT NULL COMMENT '�����ʺ�',
  `init` int(11) DEFAULT NULL COMMENT '��ʼ�ʺ�',
  `balance` int(11) DEFAULT NULL COMMENT '���',
  `remark` varchar(200) DEFAULT NULL COMMENT '��ע',
  `is_del` tinyint(3) DEFAULT '0' COMMENT 'ɾ�����',
  `create_time` int(11) DEFAULT NULL COMMENT '����ʱ��',
  `update_time` int(11) DEFAULT NULL COMMENT '����ʱ��',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `oa_finance`;
CREATE TABLE `oa_finance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_no` varchar(10) DEFAULT NULL COMMENT '���ݱ��',
  `remark` varchar(255) DEFAULT NULL,
  `input_date` date DEFAULT NULL COMMENT '¼������',
  `account_id` int(11) DEFAULT NULL COMMENT '�ʺ�ID',
  `account_name` varchar(20) DEFAULT NULL COMMENT '�ʺ���',
  `income` int(11) DEFAULT NULL COMMENT '����',
  `payment` int(11) DEFAULT NULL COMMENT '֧��',
  `amount` int(11) DEFAULT NULL COMMENT '�ϼ�',
  `type` varchar(20) DEFAULT NULL COMMENT '����',
  `partner` varchar(50) DEFAULT NULL COMMENT '������',
  `actor_name` varchar(10) DEFAULT NULL COMMENT '������',
  `user_id` int(11) DEFAULT NULL COMMENT '��½��',
  `user_name` varchar(10) DEFAULT NULL COMMENT '��¼��',
  `create_time` int(11) DEFAULT NULL COMMENT '��������',
  `update_time` int(11) DEFAULT NULL COMMENT '��������',
  `add_file` varchar(255) DEFAULT NULL COMMENT '����',
  `doc_type` tinyint(3) DEFAULT NULL COMMENT '����',
  `is_del` tinyint(3) DEFAULT '0' COMMENT 'ɾ�����',
  `related_account_id` int(11) DEFAULT NULL COMMENT '����ʺ�ID',
  `related_account_name` varchar(20) DEFAULT NULL COMMENT '����ʺ�����',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;