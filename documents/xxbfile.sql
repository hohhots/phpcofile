-- phpMyAdmin SQL Dump
-- version 2.6.4-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jan 21, 2006 at 10:06 PM
-- Server version: 5.0.12
-- PHP Version: 5.0.5
-- 
-- Database: `xxbfile`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `bureau`
-- 

CREATE TABLE `bureau` (
  `bureauid` smallint(3) NOT NULL,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `fullname` varchar(100) collate utf8_unicode_ci NOT NULL,
  `address` varchar(200) collate utf8_unicode_ci NOT NULL,
  `zip` varchar(6) collate utf8_unicode_ci NOT NULL,
  `www` varchar(30) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`bureauid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `bureau`
-- 

INSERT INTO `bureau` VALUES (1, '呼和浩特市信息办', '', '党政机关大楼11楼', '010011', 'www.ddd.ddd');
INSERT INTO `bureau` VALUES (2, '呼和浩特市统计局', '', '党政机关大楼9楼', '010011', 'www.tongjiju.com');
INSERT INTO `bureau` VALUES (3, '呼和浩特市教育局', '', '党政机关大楼6楼', '010011', 'www.jiaoyuju.com');

-- --------------------------------------------------------

-- 
-- Table structure for table `cookies`
-- 

CREATE TABLE `cookies` (
  `userid` int(5) unsigned NOT NULL default '0',
  `cookieval` char(32) collate utf8_unicode_ci NOT NULL default '',
  `usertype` enum('G','U','A') collate utf8_unicode_ci NOT NULL default 'G',
  `registorder` enum('1','2','3') collate utf8_unicode_ci default NULL,
  `cookie_start` int(11) NOT NULL default '0',
  `browser` char(150) collate utf8_unicode_ci NOT NULL default '',
  `ip` char(8) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`userid`,`cookieval`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `cookies`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `loginfo`
-- 

CREATE TABLE `loginfo` (
  `order` int(8) NOT NULL auto_increment,
  `userid` int(5) default '0',
  `ip` varchar(15) collate utf8_unicode_ci NOT NULL default '',
  `time` varchar(15) collate utf8_unicode_ci NOT NULL default '',
  `sysinfo` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `loginfo`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `oaviso`
-- 

CREATE TABLE `oaviso` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `oaviso`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `obill`
-- 

CREATE TABLE `obill` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `obill`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `obulletin`
-- 

CREATE TABLE `obulletin` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `obulletin`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ocase`
-- 

CREATE TABLE `ocase` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `ocase`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ocommand`
-- 

CREATE TABLE `ocommand` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `ocommand`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `odecision`
-- 

CREATE TABLE `odecision` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `odecision`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `oencyclic`
-- 

CREATE TABLE `oencyclic` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `oencyclic`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `officefile`
-- 

CREATE TABLE `officefile` (
  `officetypeid` smallint(2) NOT NULL,
  `fileid` int(8) NOT NULL,
  `deltime` varchar(15) collate utf8_unicode_ci NOT NULL default '0',
  `send` varchar(15) collate utf8_unicode_ci NOT NULL default '0',
  `freezed` varchar(15) collate utf8_unicode_ci NOT NULL default '0',
  PRIMARY KEY  (`officetypeid`,`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `officefile`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `officetype`
-- 

CREATE TABLE `officetype` (
  `officetypeid` smallint(2) NOT NULL default '0',
  `name` varchar(4) collate utf8_unicode_ci default NULL,
  `ictbname` varchar(15) collate utf8_unicode_ci default NULL,
  `description` varchar(200) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`officetypeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `officetype`
-- 

INSERT INTO `officetype` VALUES (1, '命令', 'ocommand', '适用于依照有关法律公布行政法规和规章；宣布施行重大强制性行政措施；嘉奖有关单位及人员。');
INSERT INTO `officetype` VALUES (2, '决定', 'odecision', ' 适用于重要事项或者重大行动做出安排，奖惩有关单位及人员，变更或者撤消下级机关不适当的决定事项。');
INSERT INTO `officetype` VALUES (3, '公告', 'obulletin', '适用于向国内外宣布重要事项或者法定事项。');
INSERT INTO `officetype` VALUES (4, '通告', 'oencyclic', '适用于公布社会各有关方面应当遵守或者周知的事项。');
INSERT INTO `officetype` VALUES (5, '通知', 'onotice', '适用于批转下级机关的公文，转发上级机关和不相隶属机关的公文，传达要求下级机关办理和需要有关单位周知或者执行的事项，任免人员。');
INSERT INTO `officetype` VALUES (6, '通报', 'oaviso', ' 适用于表彰先进，批评错误传达重要精神或者情况。');
INSERT INTO `officetype` VALUES (7, '议案', 'obill', ' 适用于各级人民政府按照法律程序向同级人民代表大会或人民代表大会常务委员会提请审议事项。');
INSERT INTO `officetype` VALUES (8, '报告', 'oreporting', ' 适用于向上级机关汇报工作，反映情况，答复上级机关的询问。');
INSERT INTO `officetype` VALUES (9, '请示', 'oinstructions', '适用于向上级机关请求指示、批准。');
INSERT INTO `officetype` VALUES (10, '批复', 'oreversion', '适用于答复下级机关的请示事项。');
INSERT INTO `officetype` VALUES (11, '意见', 'oopinion', ' 适用于对重要问题提出见解和处理办法。');
INSERT INTO `officetype` VALUES (12, '函', 'ocase', '适用于不相隶属机关之间商洽工作，询问和答复问题，请求批准和答复审批事项。');
INSERT INTO `officetype` VALUES (13, '会议纪要', 'osummary', '适用于记载、传达会议情况和议定事项。');
INSERT INTO `officetype` VALUES (14, '其它', 'oothers', '非正式类型的公文。');

-- --------------------------------------------------------

-- 
-- Table structure for table `oinstructions`
-- 

CREATE TABLE `oinstructions` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `oinstructions`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `onotice`
-- 

CREATE TABLE `onotice` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `onotice`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `oopinion`
-- 

CREATE TABLE `oopinion` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `oopinion`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `oothers`
-- 

CREATE TABLE `oothers` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `oothers`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `oreadright`
-- 

CREATE TABLE `oreadright` (
  `userid` int(5) default '0',
  `officeid` int(8) default '0',
  `read` enum('N','Y') collate utf8_unicode_ci NOT NULL default 'N',
  `startreadtime` varchar(15) collate utf8_unicode_ci default NULL,
  `readok` enum('Y','N') collate utf8_unicode_ci NOT NULL default 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `oreadright`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `oreporting`
-- 

CREATE TABLE `oreporting` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `oreporting`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `oreversion`
-- 

CREATE TABLE `oreversion` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `oreversion`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `osummary`
-- 

CREATE TABLE `osummary` (
  `fileid` int(8) NOT NULL default '0',
  `userid` int(5) default '0',
  `secretlevel` enum('绝密','机密','秘密','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `lasttime` varchar(15) collate utf8_unicode_ci default NULL,
  `yearmonth` enum('年','月') collate utf8_unicode_ci NOT NULL default '年',
  `urgencydegree` enum('特急','急件','普通') collate utf8_unicode_ci NOT NULL default '普通',
  `bureauname` varchar(100) collate utf8_unicode_ci default NULL,
  `agencyname` varchar(20) collate utf8_unicode_ci NOT NULL,
  `year` varchar(4) collate utf8_unicode_ci NOT NULL,
  `ordernum` varchar(4) collate utf8_unicode_ci NOT NULL,
  `title` varchar(150) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `accessories` varchar(200) collate utf8_unicode_ci default NULL,
  `validatetime` varchar(15) collate utf8_unicode_ci default NULL,
  `annotation` varchar(200) collate utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) collate utf8_unicode_ci NOT NULL,
  `sendnames` varchar(400) collate utf8_unicode_ci default NULL,
  `printbureau` varchar(100) collate utf8_unicode_ci NOT NULL,
  `printdate` varchar(15) collate utf8_unicode_ci NOT NULL,
  `pubtime` varchar(15) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `osummary`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `sendtobureau`
-- 

CREATE TABLE `sendtobureau` (
  `officetypeid` smallint(2) NOT NULL,
  `fileid` int(8) NOT NULL,
  `bureauid` smallint(3) NOT NULL,
  `firstread` varchar(15) collate utf8_unicode_ci NOT NULL default '0',
  `readed` varchar(15) collate utf8_unicode_ci NOT NULL default '0',
  PRIMARY KEY  (`officetypeid`,`fileid`,`bureauid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `sendtobureau`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `user`
-- 

CREATE TABLE `user` (
  `userid` int(5) unsigned NOT NULL default '0',
  `usertype` enum('U','A') collate utf8_unicode_ci default 'U',
  `bureauid` smallint(3) NOT NULL,
  `name` varchar(25) collate utf8_unicode_ci NOT NULL,
  `publish` enum('N','Y') collate utf8_unicode_ci NOT NULL default 'N',
  `sex` enum('男','女') collate utf8_unicode_ci NOT NULL default '男',
  `age` smallint(5) unsigned NOT NULL default '0',
  `registdate` date NOT NULL default '0000-00-00',
  `telephone` varchar(30) collate utf8_unicode_ci default NULL,
  `mobile` varchar(30) collate utf8_unicode_ci default NULL,
  `email` varchar(30) collate utf8_unicode_ci default NULL,
  `user_pass` varchar(8) collate utf8_unicode_ci NOT NULL default '',
  `ua_checked` enum('Y','N') collate utf8_unicode_ci NOT NULL default 'N',
  PRIMARY KEY  (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `user`
-- 

INSERT INTO `user` VALUES (1, 'U', 1, '达赖', 'Y', '男', 0, '0000-00-00', NULL, NULL, NULL, '1', 'N');
INSERT INTO `user` VALUES (2, 'U', 1, '迟涛', 'Y', '女', 0, '0000-00-00', NULL, NULL, NULL, '2', 'N');
INSERT INTO `user` VALUES (3, 'U', 2, '巴图', 'Y', '男', 0, '0000-00-00', NULL, NULL, NULL, '3', 'N');
