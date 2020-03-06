<?php

include("../../includes/common.php");
foreach($_GET as $k => $v){
	$params[$k] = daddslashes($v);
}
$params['VPSpassword'] = base64_encode($params['VPSpassword']);
if($DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$params['vpsname']}' AND `password`='{$params['VPSpassword']}'")->num_rows != 1){
	exit('账号密码错误！');
}
$service = $DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$params['vpsname']}' AND `password`='{$params['VPSpassword']}'")->fetch_assoc();
$service['password'] = base64_decode($service['password']);
$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$service['product']}'")->fetch_assoc();
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'")->fetch_assoc();

$plugin = ROOT."/plugins/server/".$server['plugin']."/main.php";
if(!file_exists($plugin)){
	exit('该产品不支持登陆');
}
include($plugin);
$function = $server['plugin']."_LoginService";
if(!function_exists($function)){
	exit('该产品不支持登陆');
}else{
	$postdata = array(
		'service' => $service,
		'product' => $product,
		'server' => $server,
	);
	$return = $function($postdata);
	echo $return;
}

?>