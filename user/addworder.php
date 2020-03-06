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
$title = daddslashes($_POST['title']);
$content = daddslashes($_POST['content']);
if(!empty($title) && !empty($content)){
	$DB->query("INSERT INTO `ytidc_worder`(`title`, `content`, `reply`, `user`, `status`) VALUES ('{$title}','{$content}','','{$user['id']}','待回复')");
	@header("Location: ./msg.php?msg=提交成功，请等待处理！");
	exit;
}

$template = file_get_contents("../templates/".$template_name."/user_addworder.template");
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
	'user' => $user,
);
echo set_template($template, $template_name, $template_code);
?>