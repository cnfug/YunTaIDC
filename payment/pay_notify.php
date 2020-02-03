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
$function = $gateway_plugin."_ProcessNotify";
$result = $function($params, $configoption);
if(!empty($result['orderid']) && $result['status'] == 'success' && $DB->query("SELECT * FROM `ytidc_order` WHERE `orderid`='{$result['orderid']}'")->num_rows == 1){
	$order = $DB->query("SELECT * FROM `ytidc_order` WHERE `orderid`='{$result['orderid']}'")->fetch_assoc();
	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$order['user']}'")->fetch_assoc();
	if($order['status'] != "待支付"){
		exit('success');
	}else{
		$DB->query("UPDATE `ytidc_order` SET `status`='已完成' WHERE `orderid`='{$result['orderid']}'");
		$addmoney = $order['money'] * $plugin['fee'] / 100;
		$newmoney = $user['money'] + $addmoney;
		$DB->query("UPDATE `ytidc_user` SET `money`='{$newmoney}' WHERE `id`='{$order['user']}'");
		exit('success');
	}
}else{
	exit('fail');
}

?>