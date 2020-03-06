<?php

include("../includes/common.php");
if(!empty($_SESSION['ytidc_user']) && !empty($_SESSION['ytidc_token'])){
	$username = daddslashes($_SESSION['ytidc_user']);
  	$userkey = daddslashes($_SESSION['ytidc_token']);
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}'");
  	if($user->num_rows == 1){
    	$user = $user->fetch_assoc();
      	$userkey1 = md5($_SERVER['HTTP_HOST'].$user['password']);
      	if($userkey == $userkey1){
      		@header("Location: ./index.php");
      		exit;
      	}
    }
}
if(!empty($_POST['username']) && !empty($_POST['password'])){
    $username = daddslashes($_POST['username']);
    $password = base64_encode(daddslashes($_POST['password']));
    $result = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}' and `password`='{$password}'");
    if($result->num_rows != 1){
        exit('账号密码错误！<a href="./login.php">点我重新登陆</a>');
    }else{
        $_SESSION['ytidc_user'] = $username;
        $_SESSION['ytidc_token'] = md5($_SERVER['HTTP_HOST'].$password);
        @header("Location: ./index.php");
        exit;
    }
}

$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
);
$template = file_get_contents("../templates/".$template_name."/user_login.template");
echo set_template($template, $template_name, $template_code);

?>