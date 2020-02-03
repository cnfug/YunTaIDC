<?php

include("../includes/common.php");
set_time_limit(3600);
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
if(empty($params['username']) || empty($params['password']) || empty($params['product']) || empty($params['time'])){
  	@header("Location: ./msg.php?msg=参数不足够，请勿为空！");
  	exit;
}
if($DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$params['username']}'")->num_rows != 0){
  	@header("Location: ./msg.php?msg=服务器用户名已被占用");
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
$pdis = json_decode(url_decode($product['time']),true);
foreach($pdis as $k => $v){
	if($v['name'] == $params['time']){
		$dis = array(
			'name' => $v['name'],
			'discount' => $v['discount'],
			'day' => $v['day'],
			'remark' => $v['remark'],
		);
	}
}
if(empty($dis)){
	@header("Location: ./msg.php?msg=服务器周期设置错误");
  	exit;
}
if(empty($params['promo_code'])){
	$price = $price[$product['id']] * $dis['discount'];
}else{
	$price = $price[$product['id']] * $dis['discount'];
	$promo = $DB->query("SELECT * FROM `ytidc_promo` WHERE `code`='{$params['promo_code']}'");
	if($promo->num_rows != 1){
	  	@header("Location: ./msg.php?msg=优惠码不存在！");
	  	exit;
	}else{
		$promo = $promo->fetch_assoc();
		if($promo['product'] != $product['id']){
			@header("Location: ./msg.php?msg=优惠码不能开通本产品！");
	  		exit;
		}
		$price = $price - $promo['price'];
	}
}
if(!check_price($price, true)){
  	@header("Location: ./msg.php?msg=价格设置错误，请联系上级进行管理！");
  	exit;
}
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
  	$orderid = date('YmdHis').rand(1000, 99999);
  	$DB->query("INSERT INTO `ytidc_order`(`orderid`, `description`, `money`, `action`, `user`, `status`) VALUES ('{$orderid}','开通服务','{$price}','扣款','{$user['id']}','已完成')");
}else{
  	@header("Location: ./msg.php?msg=用户余额不足");
  	exit;
}
$date = date('Y-m-d',strtotime("+{$dis[day]} days", time()));
$DB->query("INSERT INTO `ytidc_service` (`userid`, `username`, `password`, `enddate`, `product`, `configoption`, `status`) VALUES ('{$user['id']}', '{$params['username']}', '{$params['password']}', '{$date}', '{$product['id']}', '' ,'等待审核')");
$serviceid = $DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$params['username']}' AND `password`='{$params['password']}'")->fetch_assoc();
$serviceid = $serviceid['id'];
$plugin = "../plugins/server/".$server['plugin']."/main.php";
if(!is_file($plugin) || !file_exists($plugin)){
  	@header("Location: ./msg.php?msg=服务器插件不存在");
  	exit;
}
include($plugin);
$postdata = array(
  	'service' => array(
      	'username' => $params['username'],
       	'password' => $params['password'],
      	'time' => $dis,
    ),
  	'product' => $product,
  	'server' => $server,
);
$function = $server['plugin']."_CreateService";
$return = $function($postdata);

if($return['status'] != "success"){
  	@header("Location: ./msg.php?msg={$return['msg']}");
  	exit;
}else{
	$DB->query("UPDATE `ytidc_service` SET `username`='{$return['username']}',`password`='{$return['password']}',`enddate`='{$return['enddate']}',`configoption`='{$return['configoption']}',`status`='激活' WHERE `id`='{$serviceid}'");
	$dberror = $DB->error;
  	@header("Location: ./msg.php?msg=开通成功");
  	exit;
}

?>