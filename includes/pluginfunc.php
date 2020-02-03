<?php

function Plugins_Update_Service($updates, $serviceid){
	global $DB;
	$serviceid = daddslashes($serviceid);
	if(empty($updates) || empty($serviceid)){
		return false;
	}
	foreach($updates as $k => $v){
		$updatekey = daddslashes($k);
		$updatevalue = daddslashes($v);
		$DB->query("UPDATE `ytidc_service` SET `{$updatekey}` = '{$updatevalue}' WHERE `id` = '{$serviceid}'");
		if($DB-error){
			return false;
		}
	}
	return true;
}

function Plugins_Finish_Order($orderid){
	global $DB;
	$orderid = daddslashes($orderid);
	if(empty($orderid)){
		return false;
	}
	$order = $DB->query("SELECT * FROM `ytidc_order` WHERE `oredrid`='{$orderid}'")->fetch_assoc();
	$user = $DB->query("SELECT * FROM `ytidc_user`WHERE `id`='{$order['user']}'")->fetch_assoc();
	if($DB->error){
		return false;
	}
	if($order['status'] == "待支付"){
		$new_money = $user['money'] + $order['money'];
		$DB->query("UPDATE `ytidc_user` SET `money`='{$new_money}' WHERE `id`='{$order['user']}'");
		$DB->query("UPDATE `ytidc_order` SET `status`='已完成' WHERE `orderid`='{$orderid}'");
		if($DB->error){
			return false;
		}else{
			return true;
		}
	}else{
		return false;
	}
}

function Plugins_Get_UserData($userid){
	$userid = daddslashes($userid);
	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$userid}'")->fetch_assoc();
	if($DB->error){
		return false;
	}else{
		return $user;
	}
}

?>