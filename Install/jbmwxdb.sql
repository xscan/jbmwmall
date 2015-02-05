-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 01 月 19 日 16:56
-- 服务器版本: 5.5.19
-- PHP 版本: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `jbmwmall1`
--

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_access`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `jbmwmall_access`
--

INSERT INTO `jbmwmall_access` (`role_id`, `node_id`, `level`, `module`) VALUES
(2, 1, 1, NULL),
(2, 3, 2, NULL),
(2, 18, 3, NULL),
(2, 19, 3, NULL),
(2, 20, 3, NULL),
(2, 21, 3, NULL),
(2, 22, 3, NULL),
(2, 23, 3, NULL),
(2, 4, 2, NULL),
(2, 24, 3, NULL),
(2, 25, 3, NULL),
(2, 26, 3, NULL),
(2, 27, 3, NULL),
(2, 28, 3, NULL),
(1, 1, 1, NULL),
(1, 2, 2, NULL),
(1, 10, 3, NULL),
(1, 11, 3, NULL),
(1, 12, 3, NULL),
(1, 13, 3, NULL),
(1, 14, 3, NULL),
(1, 15, 3, NULL),
(1, 16, 3, NULL),
(1, 17, 3, NULL),
(1, 3, 2, NULL),
(1, 18, 3, NULL),
(1, 19, 3, NULL),
(1, 20, 3, NULL),
(1, 21, 3, NULL),
(1, 22, 3, NULL),
(1, 23, 3, NULL),
(1, 4, 2, NULL),
(1, 24, 3, NULL),
(1, 25, 3, NULL),
(1, 26, 3, NULL),
(1, 27, 3, NULL),
(1, 28, 3, NULL),
(1, 5, 2, NULL),
(1, 29, 3, NULL),
(1, 30, 3, NULL),
(1, 31, 3, NULL),
(1, 6, 2, NULL),
(1, 32, 3, NULL),
(1, 33, 3, NULL),
(1, 34, 3, NULL),
(1, 7, 2, NULL),
(1, 35, 3, NULL),
(1, 36, 3, NULL),
(1, 8, 2, NULL),
(1, 37, 3, NULL),
(1, 9, 2, NULL),
(1, 38, 3, NULL),
(1, 39, 3, NULL),
(1, 41, 3, NULL),
(1, 45, 3, NULL),
(1, 46, 3, NULL),
(1, 47, 3, NULL),
(1, 48, 3, NULL),
(1, 49, 3, NULL),
(1, 53, 3, NULL),
(1, 54, 3, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_alipay`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_alipay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alipayname` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '支付宝名称',
  `partner` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '合作身份者id',
  `key` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '安全检验码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `jbmwmall_alipay`
--

INSERT INTO `jbmwmall_alipay` (`id`, `alipayname`, `partner`, `key`) VALUES
(1, '11122', '111', '111');

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_extdb`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_extdb` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号',
  `ext_ip` text NOT NULL COMMENT 'IP地址',
  `ext_type` text NOT NULL COMMENT '数据库类型',
  `ext_dbname` text NOT NULL COMMENT '数据库名称',
  `ext_uid` text NOT NULL COMMENT '用户名',
  `ext_pwd` text NOT NULL COMMENT '密码',
  `ext_name` text COMMENT '外部数据源名',
  `ext_port` int(11) NOT NULL DEFAULT '0' COMMENT '端口号',
  PRIMARY KEY (`ext_port`),
  UNIQUE KEY `ext_port` (`ext_port`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `id_3` (`id`),
  KEY `id_4` (`id`),
  KEY `id_5` (`id`),
  KEY `id_6` (`id`),
  KEY `ext_port_2` (`ext_port`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='外部数据源表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `jbmwmall_extdb`
--

INSERT INTO `jbmwmall_extdb` (`id`, `ext_ip`, `ext_type`, `ext_dbname`, `ext_uid`, `ext_pwd`, `ext_name`, `ext_port`) VALUES
(1, '127.0.0.1', 'odbc_mssql', 'bmdatawxtest', 'sa', 'bm0731', '金博美12', 1433);

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_good`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_good` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `name` text NOT NULL,
  `price` text NOT NULL,
  `old_price` text NOT NULL,
  `image` text,
  `detail` text NOT NULL,
  `status` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `jbmwmall_good`
--

INSERT INTO `jbmwmall_good` (`id`, `menu_id`, `sort`, `name`, `price`, `old_price`, `image`, `detail`, `status`, `time`) VALUES
(1, 1, 1, '美容美发', '1200', '1800', '54b477dceda94.jpg', '美容美发软件<span>美容美发软件</span><span>美容美发软件</span>', 1, '2015-01-13 01:41:49'),
(2, 1, 444, '4444444', '444', '444', '54b4883f3dbe9.jpg', '<div style="text-align:center;">\r\n	<span>rrrrrrrrrrrrrrr44444444455555888899999;lllll6666666666666666666666666666</span>\r\n</div>', 1, '2015-01-13 02:51:43');

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_info`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `notification` text NOT NULL,
  `theme` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `jbmwmall_info`
--

INSERT INTO `jbmwmall_info` (`id`, `name`, `notification`, `theme`) VALUES
(1, '金博美', '欢迎来到金博美微信世界！！！', 'default');

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_mail`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_mail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `smtp` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `on` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_menu`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `jbmwmall_menu`
--

INSERT INTO `jbmwmall_menu` (`id`, `name`, `pid`) VALUES
(1, '软件', 0),
(2, '会员', 1),
(3, '硬件', 0);

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_node`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_node` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- 转存表中的数据 `jbmwmall_node`
--

INSERT INTO `jbmwmall_node` (`id`, `name`, `title`, `status`, `remark`, `sort`, `pid`, `level`) VALUES
(1, 'Admin', '后台应用', 1, NULL, 1, 0, 1),
(2, 'Weixin', '微信基本信息管理', 1, NULL, 2, 1, 2),
(3, 'Messmassage', '微信群发', 1, NULL, 3, 1, 2),
(4, 'ScSetup', '商城设置', 1, NULL, 4, 1, 2),
(5, 'Menu', '商城菜单', 1, NULL, 5, 1, 2),
(6, 'Good', '商品设置', 1, NULL, 6, 1, 2),
(7, 'Order', '订单管理', 1, NULL, 7, 1, 2),
(8, 'User', '客户列表', 1, NULL, 8, 1, 2),
(9, 'Rbac', '用户管理', 1, NULL, 9, 1, 2),
(10, 'index', '微信基本信息首页', 1, NULL, 1, 2, 3),
(11, 'setconfig', '保存微信配置', 1, NULL, 2, 2, 3),
(12, 'addmenu', '添加微信菜单', 1, NULL, 3, 2, 3),
(13, 'UpdateMenu', '修改微信菜单', 1, NULL, 4, 2, 3),
(14, 'delmenu', '删除微信菜单', 1, NULL, 5, 2, 3),
(15, 'addmessage', '增改自定义回复', 1, NULL, 6, 2, 3),
(16, 'delmessage', '删除自定义回复', 1, NULL, 7, 2, 3),
(17, 'setext', '设置外部数据源', 1, NULL, 8, 2, 3),
(18, 'index', '微信群发功能', 1, NULL, 1, 3, 3),
(19, 'massSendMsg', '群发信息', 1, NULL, 2, 3, 3),
(20, 'SendMsg', '选人发信息', 1, NULL, 3, 3, 3),
(21, 'getGroupUser', '获取用户列表', 1, NULL, 4, 3, 3),
(22, 'sendmsglog', '群发消息记录', 1, NULL, 5, 3, 3),
(23, 'loopsendmsglog', '查询历史记录', 1, NULL, 6, 3, 3),
(24, 'set', '商城设置首页', 1, NULL, 1, 4, 3),
(25, 'setting', '商城设置修改', 1, NULL, 2, 4, 3),
(26, 'settheme', '模版设置', 1, NULL, 3, 4, 3),
(27, 'setalipay', '支付宝设置', 1, NULL, 4, 4, 3),
(28, 'preview', '模版预览', 1, NULL, 6, 4, 3),
(29, 'index', '商城菜单', 1, NULL, 1, 5, 3),
(30, 'addmenu', '增改商城菜单', 1, NULL, 2, 5, 3),
(31, 'del', '删除商城菜单', 1, NULL, 3, 5, 3),
(32, 'index', '商品列表', 1, NULL, 1, 6, 3),
(33, 'addgood', '增改商品', 1, NULL, 2, 6, 3),
(34, 'delgood', '删除商品', 1, NULL, 3, 6, 3),
(35, 'index', '订单列表', 1, NULL, 1, 7, 3),
(36, 'del', '删除订单', 1, NULL, 2, 7, 3),
(37, 'index', '客户列表', 1, NULL, 1, 8, 3),
(38, 'userList', '用户列表', 1, NULL, 1, 9, 3),
(39, 'roleList', '角色列表', 1, NULL, 2, 9, 3),
(41, 'addUser', '添加用户界面', 1, NULL, 4, 9, 3),
(45, 'addUserHandle', '增改用户', 1, NULL, 4, 9, 3),
(46, 'delUser', '删除用户', 1, NULL, 5, 9, 3),
(47, 'addRole', '添加角色界面', 1, NULL, 7, 9, 3),
(48, 'addRoleHandle', '增改角色', 1, NULL, 8, 9, 3),
(49, 'delRole', '删除角色', 1, NULL, 9, 9, 3),
(53, 'access', '权限配置', 1, NULL, 13, 9, 3),
(54, 'setAccess', '保存权限配置', 1, NULL, 14, 9, 3);

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_order`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `orderid` text NOT NULL,
  `totalprice` text NOT NULL,
  `pay_style` text NOT NULL,
  `pay_status` text NOT NULL,
  `note` text NOT NULL,
  `order_status` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cartdata` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_role`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `jbmwmall_role`
--

INSERT INTO `jbmwmall_role` (`id`, `name`, `pid`, `status`, `remark`) VALUES
(1, 'manager', NULL, 1, '网站管理员');

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_role_user`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `jbmwmall_role_user`
--

INSERT INTO `jbmwmall_role_user` (`role_id`, `user_id`) VALUES
(1, '15'),
(1, '16');

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_sendmsglog`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_sendmsglog` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `FakeId` text NOT NULL,
  `type` char(30) NOT NULL,
  `content` text NOT NULL,
  `send_time` datetime NOT NULL,
  `send_name` varchar(50) NOT NULL,
  `rev_name` varchar(50) NOT NULL,
  `tel` text,
  `mem_id` text,
  `msg_type` char(2) DEFAULT NULL,
  `send_start` char(2) DEFAULT NULL,
  `wx_name` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `jbmwmall_sendmsglog`
--

INSERT INTO `jbmwmall_sendmsglog` (`id`, `FakeId`, `type`, `content`, `send_time`, `send_name`, `rev_name`, `tel`, `mem_id`, `msg_type`, `send_start`, `wx_name`) VALUES
(1, '1708260517', 'TEXT', 'demo', '2015-01-18 07:32:30', 'system', '陈龙坚', '', '', '0', '0', 'jbm'),
(2, '1556778743', 'TEXT', 'demo', '2015-01-18 07:32:30', 'system', '辉哥.移动应用开发', '', '', '0', '0', 'jbm'),
(3, '2649456800', 'TEXT', 'demo', '2015-01-18 07:32:30', 'system', '梧桐', '', '', '0', '0', 'jbm'),
(4, '2732056504', 'TEXT', 'demo', '2015-01-18 07:32:30', 'system', '后知后觉', '', '', '0', '0', 'jbm'),
(5, '1984243430', 'TEXT', 'demo', '2015-01-18 07:32:31', 'system', '微笑等待黎明', '', '', '0', '0', 'jbm'),
(6, '1612583462', 'TEXT', 'demo', '2015-01-18 07:32:31', 'system', '李玉辉.新媒体.移动电商', '', '', '0', '0', 'jbm'),
(7, '1708260517', 'TEXT', '123', '2015-01-19 09:50:15', 'system', '陈龙坚', '', '', '0', '0', 'JBMWX'),
(8, '1708260517', 'TEXT', '测试信息', '2015-01-19 09:50:50', 'system', '陈龙坚', '', '', '0', '0', 'JBMWX'),
(9, '1556778743', 'TEXT', '测试信息', '2015-01-19 09:50:50', 'system', '辉哥.移动应用开发', '', '', '0', '0', 'JBMWX'),
(10, '1708260517', 'TEXT', 'demo', '2015-01-19 01:45:16', 'system', '陈龙坚', '', '', '0', '0', 'JBMWX');

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_user`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `loginip` varchar(15) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- 转存表中的数据 `jbmwmall_user`
--

INSERT INTO `jbmwmall_user` (`id`, `username`, `password`, `time`, `loginip`, `status`) VALUES
(10, 'admin', '21232f297a57a5a743894a0e4a801fc3', '2015-01-18 19:45:17', '127.0.0.1', 1);

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_wscust`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_wscust` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号',
  `uid` text,
  `username` text,
  `phone` text,
  `password` text,
  `address` text,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_wxconfig`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_wxconfig` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `num` text NOT NULL,
  `ini_num` text NOT NULL,
  `token` text NOT NULL,
  `appid` text NOT NULL,
  `appsecret` text NOT NULL,
  `encodingaeskey` text NOT NULL,
  `partnerid` text NOT NULL,
  `partnerkey` text NOT NULL,
  `paysignkey` text NOT NULL,
  `pttelemp` text,
  `ptaccount` text COMMENT '公众平台登录账号',
  `ptpassword` text COMMENT '公众平登录密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `jbmwmall_wxconfig`
--

INSERT INTO `jbmwmall_wxconfig` (`id`, `num`, `ini_num`, `token`, `appid`, `appsecret`, `encodingaeskey`, `partnerid`, `partnerkey`, `paysignkey`, `pttelemp`, `ptaccount`, `ptpassword`) VALUES
(1, 'JBMWX', 'JBMWX', 'JBMWX', '', '', '', '', '111', '123', '13973343798 言', '123', '123');

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_wxmenu`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_wxmenu` (
  `menu_id` int(5) NOT NULL AUTO_INCREMENT,
  `menu_type` varchar(10) DEFAULT NULL,
  `menu_name` varchar(10) NOT NULL,
  `event_key` varchar(200) NOT NULL,
  `view_url` varchar(300) NOT NULL,
  `pid` int(5) NOT NULL DEFAULT '0',
  `listorder` varchar(5) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `jbmwmall_wxmenu`
--

INSERT INTO `jbmwmall_wxmenu` (`menu_id`, `menu_type`, `menu_name`, `event_key`, `view_url`, `pid`, `listorder`, `status`) VALUES
(1, 'click', '进入商城', 'BUY', '1', 0, '1', 0),
(2, 'view', '关于我们', '', 'http://www.baidu.com', 0, '3', 1),
(3, 'click', '会员专区', '', '', 0, '2', 1),
(5, 'click', '会员注册', 'ZC', '1113', 1, '2', 1);

-- --------------------------------------------------------

--
-- 表的结构 `jbmwmall_wxmessage`
--

CREATE TABLE IF NOT EXISTS `jbmwmall_wxmessage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `picurl` text NOT NULL,
  `url` text NOT NULL,
  `key` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- 转存表中的数据 `jbmwmall_wxmessage`
--

INSERT INTO `jbmwmall_wxmessage` (`id`, `type`, `title`, `description`, `picurl`, `url`, `key`) VALUES
(1, '0', 'jbm', '欢迎来到金博美', '538d80973f66e.jpg', 'http://www.bm0731.com/index.php/App/Index/index.html', 'BUY'),
(45, '1', '111', '111', '54b09f2dccb06.png', '111', '111'),
(46, '0', '2223335', '222', '54b488f510100.jpg', '2212222222', '22122222');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
