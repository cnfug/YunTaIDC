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
if(!empty($_POST['password']) && !empty($_POST['email'])){
	$password = base64_encode(daddslashes($_POST['password']));
	$email = daddslashes($_POST['email']);
    $DB->query("UPDATE `ytidc_user` SET `password`='{$password}', `email`='{$email}' WHERE `username`='{$user['username']}'");
    @header("Location: ./msg.php?msg=修改成功");
    exit;
}
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'user' => $user,
	'template_file_path' => '../templates/'.$template_name,
);
$template = file_get_contents("../templates/".$template_name."/user_info.template");
echo set_template($template, $template_name, $template_code);

?>