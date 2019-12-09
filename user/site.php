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
if(!empty($_POST['domain']) && !empty($_POST['name']) && !empty($_POST['admin']) && !empty($_POST['password'])){
  	$domain = daddslashes($_POST['domain']);
  	$name = daddslashes($_POST['name']);
  	$admin = daddslashes($_POST['admin']);
  	$password = daddslashes($_POST['password']);
  	$description = daddslashes($_POST['description']);
  	$new_money = $user['money'] - $conf['siteprice'];
  	if($new_money < 0){
      	@header("Location: ./msg.php?msg=余额不足");
      	exit;
    }
  	$DB->query("UPDATE `ytidc_user` SET `money`='{$new_money}' WHERE `username`='{$user['username']}'");
  	$DB->query("INSERT INTO `ytidc_fenzhan`(`domain`, `name`, `description`, `notice`, `admin`, `password`, `user`, `status`) VALUES ('{$domain}.{$conf['sitedomain']}','{$name}','{$description}','{$conf['sitenotice']}','{$admin}','{$password}','{$user['id']}','1')");
	@header("Location: ./msg.php?msg=开通成功！");
  	exit;
}
$template = file_get_contents("../templates/".$conf['template']."/user_header.template").file_get_contents("../templates/".$conf['template']."/user_site.template").file_get_contents("../templates/".$conf['template']."/user_footer.template");
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$conf['template'],
	'user' => $user,
);
$template = template_code_replace($template, $template_code);
echo $template;
?>