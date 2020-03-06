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
$template = file_get_contents("../templates/".$template_name."/user_pay.template");
$gateway = $DB->query("SELECT * FROM `ytidc_payplugin` WHERE `status`='1'");
$gateway_template = find_list_html("支付通道列表", $template);
while($row = $gateway->fetch_assoc()){
	$gateway_template_code = array(
		'gateway' => $row['gateway'],
		'displayname' => $row['displayname'],
		'fee' => $row['fee'],
	);
	$gateway_template_new = $gateway_template_new . template_code_replace($gateway_template[1][0], $gateway_template_code);
}
$template = str_replace($gateway_template[0][0], $gateway_template_new, $template);
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
	'user' => $user,
);
echo set_template($template, $template_name, $template_code);

?>