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
if(!empty($_GET['code'])){
  	$code = daddslashes($_GET['code']);
  	$result = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$code}'");
  	if($result->num_rows == 1){
      	$_SESSION['invite'] = $code;
    }
}else{
	$_SESSION['invite'] = $site['user'];
}
if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email'])){
    $username = daddslashes($_POST['username']);
    if($DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}'")->num_rows != 0){
    	exit('该用户名已经被注册！<a href="./register.php">点击返回</a>');
    }
    $password = daddslashes($_POST['password']);
    $password = base64_encode($password);
  	$email = daddslashes($_POST['email']);
    if($DB->query("SELECT * FROM `ytidc_user` WHERE `email`='{$email}'")->num_rows != 0){
    	exit('该邮箱已经被使用！<a href="./register.php">点击返回</a>');
    }
    $invite = $_SESSION['invite'];
  	$domain = $_SERVER['HTTP_HOST'];
  	$site = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `domain`='{$domain}'")->fetch_assoc();
  	$site = $site['id'];
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'");
  	if($grade->num_rows == 1){
  		$grade = $grade->fetch_assoc();
  	}else{
  		$grade = $DB->query("SELECT * FROM `ytidc_grade`");
  		if($grade->num_rows >= 1){
  			$grade = $grade->fetch_assoc();
  		}else{
  			exit('站点尚未配置价格组！联系站长处理！');
  		}
  	}
  	$DB->query("INSERT INTO `ytidc_user` (`username`, `password`, `email`, `money`, `grade`, `invite`, `site`, `status`) VALUE ('{$username}', '{$password}', '{$email}', '0.00', '{$grade['id']}', '{$invite}', '{$site}', '1')");
  	@header("Location: ./login.php");
  	exit();
}

$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
);
$template = file_get_contents("../templates/".$template_name."/user_register.template");
echo set_template($template, $template_name, $template_code);

?>
