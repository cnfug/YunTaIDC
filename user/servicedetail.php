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
$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$row['product']}'")->fetch_assoc();
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'")->fetch_assoc();
$plugin = "../plugins/server/".$server['plugin']."/main.php";
if(is_file($plugin) && file_exists($plugin)){
	include($plugin);
	$function = $server['plugin']."_LoginService";
	$postdata = array(
		'service' => $row,
		'product' => $product,
		'server' => $server,
	);
	$serviceloginlink = $function($postdata);
}else{
	$serviceloginlink = "该服务没有任何控制面板或对接出bug，请联系管理员！";
}
$template = file_get_contents("../templates/".$template_name."/user_service_detail.template");
$time_template = file_get_contents("../templates/".$template_name."/user_cart_time.template");
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
		'price' => $price[$product['id']] * $v['discount'],
	);
	$time_template_new = $time_template_new . template_code_replace($time_template, $time_template_code);
}
$include_file = find_include_file($template);
foreach($include_file[1] as $k => $v){
		if(file_exists("../templates/".$template_name."/".$v)){
			$replace = file_get_contents("../templates/".$template_name."/".$v);
			$template = str_replace("[include[{$v}]]", $replace, $template);
		}
		
}
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
$template = template_code_replace($template, $template_code);
echo $template;
?>