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
$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$row['product']}'")->fetch_assoc();
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'")->fetch_assoc();
$plugin = "../plugins/".$server['plugin']."/main.php";
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
$template = file_get_contents("../templates/".$conf['template']."/user_header.template").file_get_contents("../templates/".$conf['template']."/user_service_detail.template").file_get_contents("../templates/".$conf['template']."/user_footer.template");
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$conf['template'],
	'service' => $row,
	'product' => $product,
	'service_loginlink' => $serviceloginlink, 
	'user' => $user,
);
$template = template_code_replace($template, $template_code);
echo $template;
?>