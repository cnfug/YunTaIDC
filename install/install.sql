-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2019-12-09 12:17:59
-- 服务器版本： 5.5.62-log
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yun_netech_cc`
--

-- --------------------------------------------------------

--
-- 表的结构 `ytidc_config`
--

CREATE TABLE `ytidc_config` (
  `k` varchar(256) NOT NULL,
  `v` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ytidc_config`
--

INSERT INTO `ytidc_config` (`k`, `v`) VALUES
('admin', 'admin'),
('password', '123456'),
('epayurl', 'http://pay.netech.cc/'),
('epayid', '1000'),
('epaykey', 'Z22SKBkX8os82JsnbK1hNNj82ntyznn2'),
('epay_fee', '0.99'),
('invitepercent', '1'),
('siteprice', '0.00'),
('sitedomain', 'yunta.cc'),
('sitenotice', '分站默认公告！'),
('template', 'default'),
('http', 'http');

-- --------------------------------------------------------

--
-- 表的结构 `ytidc_fenzhan`
--

CREATE TABLE `ytidc_fenzhan` (
  `id` int(11) NOT NULL,
  `domain` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `subtitle` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `notice` text NOT NULL,
  `admin` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `user` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ytidc_fenzhan`
--

INSERT INTO `ytidc_fenzhan` (`id`, `domain`, `title`, `subtitle`, `description`, `notice`, `admin`, `password`, `user`, `status`) VALUES
(1, 'www.baidu.com', '默认站点', '专业的云服务供应商', '这是云塔IDC系统的测试站点', '你好，世界！', 'admin', '123456', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ytidc_grade`
--

CREATE TABLE `ytidc_grade` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `weight` int(11) NOT NULL,
  `need_paid` decimal(9,2) NOT NULL,
  `need_money` decimal(9,2) NOT NULL,
  `need_save` decimal(9,2) NOT NULL,
  `default` int(11) NOT NULL,
  `price` mediumtext NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ytidc_grade`
--

INSERT INTO `ytidc_grade` (`id`, `name`, `weight`, `need_paid`, `need_money`, `need_save`, `default`, `price`, `status`) VALUES
(1, '默认价格组', 1, '0.00', '0.00', '0.00', 1, '', 1),

-- --------------------------------------------------------

--
-- 表的结构 `ytidc_notice`
--

CREATE TABLE `ytidc_notice` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ytidc_notice`
--

-- --------------------------------------------------------

--
-- 表的结构 `ytidc_order`
--

CREATE TABLE `ytidc_order` (
  `orderid` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `money` decimal(9,2) NOT NULL,
  `action` varchar(256) NOT NULL,
  `user` int(11) NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ytidc_order`
--

-- --------------------------------------------------------

--
-- 表的结构 `ytidc_product`
--

CREATE TABLE `ytidc_product` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `type` int(11) NOT NULL,
  `server` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `configoption` text NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ytidc_product`
-- --------------------------------------------------------

--
-- 表的结构 `ytidc_server`
--

CREATE TABLE `ytidc_server` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `serverip` varchar(256) NOT NULL,
  `serverdomain` varchar(256) NOT NULL,
  `serverdns1` varchar(256) NOT NULL,
  `serverdns2` varchar(256) NOT NULL,
  `serverusername` varchar(256) NOT NULL,
  `serverpassword` varchar(256) NOT NULL,
  `serveraccesshash` varchar(256) NOT NULL,
  `servercpanel` varchar(256) NOT NULL,
  `serverport` int(11) NOT NULL,
  `plugin` varchar(256) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ytidc_server`
--

-- --------------------------------------------------------

--
-- 表的结构 `ytidc_service`
--

CREATE TABLE `ytidc_service` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `enddate` date NOT NULL,
  `product` int(11) NOT NULL,
  `configoption` text NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ytidc_type`
--

CREATE TABLE `ytidc_type` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `weight` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ytidc_type`
--

-- --------------------------------------------------------

--
-- 表的结构 `ytidc_user`
--

CREATE TABLE `ytidc_user` (
  `id` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `money` decimal(9,2) NOT NULL,
  `grade` int(11) NOT NULL,
  `invite` int(11) NOT NULL,
  `site` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ytidc_user`
--

-- --------------------------------------------------------

--
-- 表的结构 `ytidc_worder`
--

CREATE TABLE `ytidc_worder` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `reply` text NOT NULL,
  `user` int(11) NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ytidc_worder`

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ytidc_fenzhan`
--
ALTER TABLE `ytidc_fenzhan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ytidc_grade`
--
ALTER TABLE `ytidc_grade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ytidc_notice`
--
ALTER TABLE `ytidc_notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ytidc_order`
--
ALTER TABLE `ytidc_order`
  ADD PRIMARY KEY (`orderid`);

--
-- Indexes for table `ytidc_product`
--
ALTER TABLE `ytidc_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ytidc_server`
--
ALTER TABLE `ytidc_server`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ytidc_service`
--
ALTER TABLE `ytidc_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ytidc_type`
--
ALTER TABLE `ytidc_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ytidc_user`
--
ALTER TABLE `ytidc_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ytidc_worder`
--
ALTER TABLE `ytidc_worder`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ytidc_fenzhan`
--
ALTER TABLE `ytidc_fenzhan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `ytidc_grade`
--
ALTER TABLE `ytidc_grade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `ytidc_notice`
--
ALTER TABLE `ytidc_notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- 使用表AUTO_INCREMENT `ytidc_product`
--
ALTER TABLE `ytidc_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- 使用表AUTO_INCREMENT `ytidc_server`
--
ALTER TABLE `ytidc_server`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- 使用表AUTO_INCREMENT `ytidc_service`
--
ALTER TABLE `ytidc_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- 使用表AUTO_INCREMENT `ytidc_type`
--
ALTER TABLE `ytidc_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- 使用表AUTO_INCREMENT `ytidc_user`
--
ALTER TABLE `ytidc_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- 使用表AUTO_INCREMENT `ytidc_worder`
--
ALTER TABLE `ytidc_worder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
