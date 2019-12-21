
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+08:00";

CREATE TABLE `ytidc_config` (
  `k` varchar(256) NOT NULL,
  `v` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ytidc_config` (`k`, `v`) VALUES
('epayurl', ''),
('epayid', ''),
('epaykey', ''),
('admin', 'admin'),
('password', '123456'),
('sitenotice', '分站需要通过推广获取返现，分站默认注册的用户都是你推广的，都会有提成给你，目前默认返现1%，如果想赚更多的可以进行API代理。'),
('invitepercent', '1'),
('siteprice', '10.00'),
('sitedomain', 'yunta.cc'),
('template', 'default'),
('http', 'http'),
('epay_fee_wx', '2'),
('epay_fee_qq', '2'),
('epay_fee_zfb', '2'),
('contactqq1', '123456'),
('contactqq2', '123456'),
('contactemail', '123456@qq.com');

CREATE TABLE `ytidc_fenzhan` (
  `id` int(11) NOT NULL,
  `domain` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `subtitle` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `notice` text NOT NULL,
  `admin` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `user` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

INSERT INTO `ytidc_grade` (`id`, `name`, `weight`, `need_paid`, `need_money`, `need_save`, `default`, `price`, `status`) VALUES
(1, '默认价格组', 1, '0.00', '0.00', '0.00', 1, '', 1);

CREATE TABLE `ytidc_notice` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `date` date NOT NULL,
  `site` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ytidc_order` (
  `orderid` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `money` decimal(9,2) NOT NULL,
  `action` varchar(256) NOT NULL,
  `user` int(11) NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `ytidc_product` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `type` int(11) NOT NULL,
  `server` int(11) NOT NULL,
  `time` text NOT NULL,
  `configoption` text NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ytidc_promo` (
  `id` int(11) NOT NULL,
  `code` varchar(256) NOT NULL,
  `price` decimal(9,2) NOT NULL,
  `product` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `ytidc_type` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `weight` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `ytidc_worder` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `reply` text NOT NULL,
  `user` int(11) NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `ytidc_fenzhan`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_grade`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_notice`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_order`
  ADD PRIMARY KEY (`orderid`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_grade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `ytidc_notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_server`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `ytidc_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

ALTER TABLE `ytidc_worder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;