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
if(empty($id)){
  	@header("Location: ./notice.php");
  	exit;
}
$row = $DB->query("SELECT * FROM `ytidc_notice` WHERE `id`='{$id}'")->fetch_assoc();
$template = file_get_contents("../templates/".$template_name."/user_notice_detail.template");
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
	'notice' => $row,
	'user' => $user,
);
echo set_template($template, $template_name, $template_code);
?>