INSERT INTO `ytidc_config`(`k`, `v`) VALUES ('template_mobile','default');
INSERT INTO `ytidc_config`(`k`, `v`) VALUES ('crondate','2020-01-30');
INSERT INTO `ytidc_config`(`k`, `v`) VALUES ('cloud_get_news','1');
INSERT INTO `ytidc_config`(`k`, `v`) VALUES ('cloud_pay_vertify','1');

CREATE TABLE `ytidc_payplugin` (
  `id` int(11) NOT NULL,
  `displayname` varchar(256) NOT NULL,
  `gateway` varchar(256) NOT NULL,
  `fee` decimal(9,2) NOT NULL,
  `configoption` text NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `ytidc_payplugin`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ytidc_payplugin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;