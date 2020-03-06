<?php

include("../includes/common.php");
if(empty($_SESSION['ytidc_user']) || empty($_SESSION['ytidc_token'])){
  	@header("Location: ./login.php");
     exit;
}else{
  	$username = daddslashes($_SESSION['ytidc_user']);
  	$userkey = daddslashes($_SESSION['ytidc_token']);
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
$id = daddslashes($_GET['id']);
if(empty($id) || $DB->query("SELECT * FROM `ytidc_service` WHERE `id`='{$id}' AND `userid`='{$user['id']}'")->num_rows != 1){
  	@header("Location: ./service.php");
  	exit;
}
$row = $DB->query("SELECT * FROM `ytidc_service` WHERE `id`='{$id}' AND `userid`='{$user['id']}'")->fetch_assoc();
if($row['status'] != '激活'){
	@header("Location: ./msg.php?msg=服务器状态：".$row['status']."，请联系上级处理！");
	exit();
}
$row['password'] = base64_decode($row['password']);
$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$row['product']}'")->fetch_assoc();
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'")->fetch_assoc();
$plugin = "../plugins/server/".$server['plugin']."/main.php";
if(is_file($plugin) && file_exists($plugin)){
	include($plugin);
	$function = $server['plugin']."_LoginService";
	if(function_exists($function)){
		$postdata = array(
			'service' => $row,
			'product' => $product,
			'server' => $server,
		);
		$serviceloginlink = $function($postdata);
	}else{
		$serviceloginlink = '该服务没有控制面板';
	}
}else{
	$serviceloginlink = "该服务没有任何控制面板或对接出bug，请联系管理员！";
}
$template = file_get_contents("../templates/".$template_name."/user_service_detail.template");
$time_template = find_list_html("周期列表", $template);
$pdis = json_decode(url_decode($product['time']), true);
if($user['grade'] == "0" || $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->num_rows != 1){
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
}else{
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->fetch_assoc();
}
$price = json_decode($grade['price'], true);
foreach($pdis as $k => $v){
	$time_template_code = array(
		'name' => $v['name'],
		'price' => $price[$row['product']] * $v['discount'],
	);
	$time_template_new = $time_template_new . template_code_replace($time_template[1][0], $time_template_code);
}
$template = str_replace($time_template[1][0], $time_template_new, $template);
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
	'service' => $row,
	'product' => $product,
	'service_loginlink' => $serviceloginlink, 
	'user' => $user,
	'time' => $time_template_new,
);
echo set_template($template, $template_name, $template_code);
?>