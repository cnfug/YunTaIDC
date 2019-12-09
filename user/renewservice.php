<?php

include("../includes/common.php");

if(empty($_SESSION['ytidc_user']) && empty($_SESSION['ytidc_pass'])){
  	@header("Location: ./login.php");
     exit;
}else{
  	$username = daddslashes($_SESSION['ytidc_user']);
  	$userkey = daddslashes($_SESSION['ytidc_adminkey']);
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}'");
  	if($user->num_rows != 1){
      	@header("Location: ./login.php");
      	exit;
    }else{
    	$user = $user->fetch_assoc();
      	$userkey1 = md5($_SERVER['HTTP_HOST'].$user['password']);
      	if($userkey != $userkey1){
      		@header("Location: ./login.php");
      		exit;
      	}
    }
}
foreach($_POST as $k => $v){
  	$params[$k] = daddslashes($v);
}
if(empty($params['id'])|| empty($params['time'])){
  	@header("Location: ./msg.php?msg=参数不足够，请勿为空！");
  	exit;
}
$service = $DB->query("SELECT * FROM `ytidc_service` WHERE `id`='{$params['id']}' AND `userid`='{$user['id']}'")->fetch_assoc();
$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$service['product']}'")->fetch_assoc();
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'")->fetch_assoc();

if($user['grade'] == "0" || $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->num_rows != 1){
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
}else{
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `grade`='{$user['grade']}'")->fetch_assoc();
}
$price = json_decode($grade['price'], true);
$price = $price[$product['id']];
$new_money = $user['money'] - $price;
if($new_money >= 0){
  	$DB->query("UPDATE `ytidc_user` SET `money`='{$new_money}' WHERE `id`='{$user['id']}'");
}else{
  	@header("Location: ./msg.php?msg=用户余额不足");
  	exit;
}
$plugin = "../plugins/".$server['plugin']."/main.php";
if(!is_file($plugin) || !file_exists($plugin)){
  	@header("Location: ./msg.php?msg=服务器插件不存在");
  	exit;
}
include($plugin);
$postdata = array(
  	'data' => array(
      	'time' => $params['time'],
      	'id' => $params['id'],
    ),
  	'service' => $service,
  	'product' => $product,
  	'server' => $server,
);
$function = $server['plugin']."_RenewService";
$return = $function($postdata);
if($return['status'] == "fail"){
  	@header("Location: ./msg.php?msg={$return['msg']}");
  	exit;
}else{
  	$DB->query("UPDATE `ytidc_service` SET `enddate`='{$return['enddate']}' WHERE `id`='{$service['id']}'");
  	@header("Location: ./msg.php?msg=续费成功");
  	exit;
}
?>