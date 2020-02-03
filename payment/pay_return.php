<?php

include("../includes/common.php");
if(empty($_POST)){
	$returndata = $_GET;
}else{
	$returndata = $_POST;
}

foreach($returndata as $k => $v){
	$params[$k] = daddslashes($v);
}
$gateway_plugin = $params['yunta_gateway'];
unset($params['yunta_gateway']);
$plugin = $DB->query("SELECT * FROM `ytidc_payplugin` WHERE `gateway`='{$gateway_plugin}'")->fetch_assoc();
$configoption = json_decode($plugin['configoption'], true);
$gateway_plugin_return = ROOT."/plugins/payment/".$gateway_plugin."/main.php";
require_once($gateway_plugin_return);
$function = $gateway_plugin."_ProcessReturn";
$result = $function($params, $configoption);
if(!empty($result['orderid']) && $result['status'] == 'success' && $DB->query("SELECT * FROM `ytidc_order` WHERE `orderid`='{$result['orderid']}'")->num_rows == 1){
	$order = $DB->query("SELECT * FROM `ytidc_order` WHERE `orderid`='{$result['orderid']}'")->fetch_assoc();
	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$order['user']}'")->fetch_assoc();
	if($order['status'] != "待支付"){
		exit('该订单已经被完成！<a href="/user">点击返回用户中心</a>');
	}else{
		$DB->query("UPDATE `ytidc_order` SET `status`='已完成' WHERE `orderid`='{$result['orderid']}'");
		$addmoney = $order['money'] * $plugin['fee'] / 100;
		$newmoney = $user['money'] + $addmoney;
		$DB->query("UPDATE `ytidc_user` SET `money`='{$newmoney}' WHERE `id`='{$order['user']}'");
		exit('充值成功！<a href="/user">点击返回</a>');
	}
}else{
	exit("订单验证失败！返回内容如下：\r\n".json_encode($result));
}

?>