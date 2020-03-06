SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `ytidc_config` (
  `k` varchar(256) NOT NULL,
  `v` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ytidc_config` (`k`, `v`) VALUES
('admin', 'admin'),
('password', '123456'),
('sitenotice', '分站需要通过推广获取返现，分站默认注册的用户都是你推广的，都会有提成给你，目前默认返现1%，如果想赚更多的可以进行API代理。'),
('invitepercent', '1'),
('siteprice', '1.00'),
('sitedomain', 'yunta.cc'),
('template', 'default'),
('http', 'http'),
('contactqq1', '123456'),
('contactqq2', '12345678'),
('contactemail', '123456@qq.com'),
('template_mobile', 'default'),
('cryptkey', '123123'),
('crondate', '2020-03-04'),
('cloud_get_news', '1'),
('cloud_pay_vertify', '1'),
('mainsite_title', '云塔IDC系统'),
('mainsite_subtitle', '光荣使用云塔2.3版本'),
('mainsite_description', '光荣使用云塔2.3版本'),
('mainsite_keywords', ''),
('mail_alert', '7'),
('smtp_host', ''),
('smtp_user', ''),
('smtp_pass', ''),
('smtp_port', ''),
('smtp_secure', '0');

CREATE TABLE IF NOT EXISTS `ytidc_fenzhan` (
  `id` int(11) NOT NULL,
  `domain` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `subtitle` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `notice` text NOT NULL,
  `invitepercent` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ytidc_grade` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `weight` int(11) NOT NULL,
  `need_paid` decimal(9,2) NOT NULL,
  `need_money` decimal(9,2) NOT NULL,
  `need_save` decimal(9,2) NOT NULL,
  `default` int(11) NOT NULL,
  `price` mediumtext NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ytidc_notice` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `date` date NOT NULL,
  `site` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ytidc_order` (
  `orderid` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `money` decimal(9,2) NOT NULL,
  `action` varchar(256) NOT NULL,
  `user` int(11) NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ytidc_payplugin` (
  `id` int(11) NOT NULL,
  `displayname` varchar(256) NOT NULL,
  `gateway` varchar(256) NOT NULL,
  `fee` decimal(9,2) NOT NULL,
  `configoption` text NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ytidc_product` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `type` int(11) NOT NULL,
  `server` int(11) NOT NULL,
  `time` text NOT NULL,
  `configoption` text NOT NULL,
  `weight` int(11) NOT NULL,
  `hidden` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ytidc_promo` (
  `id` int(11) NOT NULL,
  `code` varchar(256) NOT NULL,
  `price` decimal(9,2) NOT NULL,
  `product` int(11) NOT NULL,
  `renew` int(11) NOT NULL,
  `daili` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ytidc_server` (
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ytidc_service` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `buydate` date NOT NULL,
  `enddate` date NOT NULL,
  `product` int(11) NOT NULL,
  `promo_code` varchar(256) NOT NULL,
  `configoption` text NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ytidc_type` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `weight` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ytidc_user` (
  `id` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `money` decimal(9,2) NOT NULL,
  `grade` int(11) NOT NULL,
  `invite` int(11) NOT NULL,
  `site` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ytidc_worder` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `reply` text NOT NULL,
  `user` int(11) NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `ytidc_fenzhan`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_grade`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_notice`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_order`
  ADD PRIMARY KEY (`orderid`);

ALTER TABLE `ytidc_payplugin`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_product`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_promo`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_server`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_service`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_type`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_worder`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_fenzhan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `ytidc_grade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `ytidc_notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `ytidc_payplugin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `ytidc_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `ytidc_promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `ytidc_server`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `ytidc_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `ytidc_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `ytidc_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1000;
ALTER TABLE `ytidc_worder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;