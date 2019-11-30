<?php

include("../includes/common.php");
if(empty($_SESSION['ytidc_user']) && empty($_SESSION['ytidc_pass'])){
  	@header("Location: ./login.php");
     exit;
}else{
  	$username = daddslashes($_SESSION['ytidc_user']);
  	$password = daddslashes($_SESSION['ytidc_pass']);
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}' AND `password`='{$password}'");
  	if($user->num_rows != 1){
      	@header("Location: ./login.php");
      	exit;
    }else{
      	$user = $user->fetch_assoc();
    }
}

foreach($_POST as $k => $v){
  	$params[$k] = daddslashes($v);
}
if(empty($params['username']) || empty($params['password']) || empty($params['product']) || empty($params['time'])){
  	@header("Location: ./msg.php?msg=参数不足够，请勿为空！");
  	exit;
}
if($DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$parmas['username']}'")->num_rows != 0){
  	@header("Location: ./msg.php?msg=用户名已被占用");
  	exit;
}
$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$params['product']}'")->fetch_assoc();
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'")->fetch_assoc();

if($user['grade'] == "0" || $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->num_rows != 1){
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
}else{
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->fetch_assoc();
}
$price = json_decode($grade['price'], true);
$price = $price[$product['id']];
if(!empty($user['invite']) && $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$user['invite']}'")->num_rows == 1){
	//邀请奖励
	$invite = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$user['invite']}'")->fetch_assoc();
	$giftmoney = $price * $conf['invitepercent'] / 100;
	$invitemoney = $invite['money'] + $giftmoney;
	$DB->query("UPDATE `ytidc_user` SET `money`='{$invitemoney}' WHERE `id`='{$invite['money']}'");
}
$new_money = $user['money'] - $price;
if($new_money >= 0){
  	$DB->query("UPDATE `ytidc_user` SET `money`='{$new_money}' WHERE `id`='{$user['id']}'");
}else{
  	@header("Location: ./msg.php?msg=用户余额不足");
  	exit;
}
$date = date('Y-m-d');
$DB->query("INSERT INTO `ytidc_service`(`userid`, `username`, `password`, `enddate`, `product`, `configoption`, `status`) VALUES ('{$user['id']}','$params['username']','{$params['password']}','{$date}','{$product['id']}','','等待审核')");
$serviceid = $DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$params['username']}' AND `password`='{$params['passowrd']}'")->fetch_assoc();
$serviceid = $serviceid['id'];
$plugin = "../plugins/".$server['plugin']."/main.php";
if(!is_file($plugin) || !file_exists($plugin)){
  	@header("Location: ./msg.php?msg=服务器插件不存在");
  	exit;
}
include($plugin);
$postdata = array(
  	'service' => array(
      	'username' => $params['username'],
       	'password' => $params['password'],
      	'time' => $params['time'],
    ),
  	'product' => $product,
  	'server' => $server,
);
$function = $server['plugin']."_CreateService";
$return = $function($postdata);

if($return['status'] == "fail"){
  	@header("Location: ./msg.php?msg={$return['msg']}");
  	exit;
}else{
	$DB->query("UPDATE `ytidc_service` SET `username`='{$return['password']}', `password`='{$return['password']}', `enddate`='{$return['enddate']}', `configoption`='{$return['configoption']}', `status`='激活' WHERE `id`='$serviceid'");
  	@header("Location: ./msg.php?msg=开通成功");
  	exit;
}

?>