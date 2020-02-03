<?php

include("../includes/common.php");
$money = daddslashes($_POST['money']);
$gateway = daddslashes($_POST['gateway']);
$user = daddslashes($_POST['user']);
$gateway_plugin = $DB->query("SELECT * FROM `ytidc_payplugin` WHERE `gateway`='{$gateway}'")->fetch_assoc();
$orderid = date('YmdHis').rand(1000,9999);
$pluginfile = ROOT."/plugins/payment/".$gateway."/main.php";
$configoption = json_decode($gateway_plugin['configoption'], true);
$DB->query("INSERT INTO `ytidc_order`(`orderid`, `description`, `money`, `action`, `user`, `status`) VALUES ('{$orderid}','余额充值',$money,'加款',$user,'待支付')");
$order = array(
	'orderid' => $orderid,
	'money' => $money,
);
include($pluginfile);
$function = $gateway."_ProcessOrder";
echo $function($configoption, $order);
?>