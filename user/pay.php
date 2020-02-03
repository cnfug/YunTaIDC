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
$template = file_get_contents("../templates/".$template_name."/user_pay.template");
$gateway_template = file_get_contents("../templates/".$template_name."/user_pay_gateway.template");
$gateway = $DB->query("SELECT * FROM `ytidc_payplugin` WHERE `status`='1'");
while($gateway_info = $gateway->fetch_assoc()){
	$gateway_template_code = array(
		'gateway' => $gateway_info['gateway'],
		'displayname' => $gateway_info['displayname'],
		'fee' => $gateway_info['fee'],
	);
	$gateway_template_new = $gateway_template_new . template_code_replace($gateway_template, $gateway_template_code);
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
	'user' => $user,
	'gateway' => $gateway_template_new,
);
$template = template_code_replace($template, $template_code);
echo $template;

?>