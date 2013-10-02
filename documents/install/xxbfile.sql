-- phpMyAdmin SQL Dump
-- version 2.6.3-rc1
-- http://www.phpmyadmin.net
-- 
-- 主机: localhost
-- 生成日期: 2005 年 07 月 27 日 17:03
-- 服务器版本: 3.23.58
-- PHP 版本: 5.0.4
-- 
-- 数据库: `xxbfile`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `cookies`
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
-- 导出表中的数据 `cookies`
-- 

INSERT INTO `cookies` VALUES (0, '302128926c68a8e5e0675d2e6eb546fc', 'G', NULL, 1122454987, 'Mozilla/5.0 (X11; U; Linux i686; zh-CN; rv:1.7.8) Gecko/20050511 Firefox/1.0.4', '7f000001');

-- --------------------------------------------------------

-- 
-- 表的结构 `user`
-- 

CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `user_type` enum('G','U','AU','UA','SA') NOT NULL default 'G',
  `administrator_name` varchar(25) NOT NULL default '',
  `sex` enum('男','女') NOT NULL default '男',
  `age` smallint(5) unsigned NOT NULL default '0',
  `registdate` date NOT NULL default '0000-00-00',
  `telephone` varchar(30) default NULL,
  `mobile` varchar(30) default NULL,
  `email` varchar(30) default NULL,
  `www` varchar(30) default NULL,
  `user_pass` varchar(6) NOT NULL default '',
  `organization` enum('呼和浩特市','包头市','鄂尔多斯市','赤峰市','通辽市','呼伦贝尔市','阿拉善盟','巴音淖尔盟','锡林郭勒盟','乌海市','乌兰察布盟','兴安盟') NOT NULL default '呼和浩特市',
  `address` varchar(255) NOT NULL default '',
  `zip` varchar(6) NOT NULL default '',
  `lastlogin_ip` varchar(15) default NULL,
  `lastlogin_time` varchar(30) default NULL,
  `ua_checked` enum('Y','N') NOT NULL default 'N',
  `login_count` int(10) default NULL,
  PRIMARY KEY  (`user_id`)
) TYPE=MyISAM;

-- 
-- 导出表中的数据 `user`
-- 

