-- phpMyAdmin SQL Dump
-- version 2.6.3-rc1
-- http://www.phpmyadmin.net
-- 
-- ����: localhost
-- ��������: 2005 �� 07 �� 27 �� 17:03
-- �������汾: 3.23.58
-- PHP �汾: 5.0.4
-- 
-- ���ݿ�: `xxbfile`
-- 

-- --------------------------------------------------------

-- 
-- ��Ľṹ `cookies`
-- 

CREATE TABLE `cookies` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `cookieval` char(32) NOT NULL default '',
  `usertype` enum('G','U','AU','UA','SA') NOT NULL default 'G',
  `registorder` enum('1','2','3') default NULL,
  `cookie_start` int(11) NOT NULL default '0',
  `browser` char(150) NOT NULL default '',
  `ip` char(8) NOT NULL default '',
  PRIMARY KEY  (`user_id`,`cookieval`)
) TYPE=MyISAM;

-- 
-- �������е����� `cookies`
-- 

INSERT INTO `cookies` VALUES (0, '302128926c68a8e5e0675d2e6eb546fc', 'G', NULL, 1122454987, 'Mozilla/5.0 (X11; U; Linux i686; zh-CN; rv:1.7.8) Gecko/20050511 Firefox/1.0.4', '7f000001');

-- --------------------------------------------------------

-- 
-- ��Ľṹ `user`
-- 

CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `user_type` enum('G','U','AU','UA','SA') NOT NULL default 'G',
  `administrator_name` varchar(25) NOT NULL default '',
  `sex` enum('��','Ů') NOT NULL default '��',
  `age` smallint(5) unsigned NOT NULL default '0',
  `registdate` date NOT NULL default '0000-00-00',
  `telephone` varchar(30) default NULL,
  `mobile` varchar(30) default NULL,
  `email` varchar(30) default NULL,
  `www` varchar(30) default NULL,
  `user_pass` varchar(6) NOT NULL default '',
  `organization` enum('���ͺ�����','��ͷ��','������˹��','�����','ͨ����','���ױ�����','��������','�����׶���','���ֹ�����','�ں���','�����첼��','�˰���') NOT NULL default '���ͺ�����',
  `address` varchar(255) NOT NULL default '',
  `zip` varchar(6) NOT NULL default '',
  `lastlogin_ip` varchar(15) default NULL,
  `lastlogin_time` varchar(30) default NULL,
  `ua_checked` enum('Y','N') NOT NULL default 'N',
  `login_count` int(10) default NULL,
  PRIMARY KEY  (`user_id`)
) TYPE=MyISAM;

-- 
-- �������е����� `user`
-- 

