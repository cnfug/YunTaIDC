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
if($DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `user`='{$user['id']}'")->num_rows >= 1){
	@header("Location: ./msg.php?msg=你已开通分站，无需再次开通！");
	exit;
}
if(!empty($_POST['domain']) && !empty($_POST['title'])){
  	$domain = daddslashes($_POST['domain']) . '.' . $conf['sitedomain'];
  	$title = daddslashes($_POST['title']);
  	$admin = daddslashes($_POST['admin']);
  	$password = daddslashes($_POST['password']);
  	$description = daddslashes($_POST['description']);
  	$new_money = $user['money'] - $conf['siteprice'];
  	if($new_money < 0){
      	@header("Location: ./msg.php?msg=余额不足");
      	exit;
    }else{
    	$orderid = date('YmdHis').rand(1000, 99999);
    	$DB->query("INSERT INTO `ytidc_order`(`orderid`, `description`, `money`, `action`, `user`, `status`) VALUES ('{$orderid}','开通分站','{$conf['siteprice']}','扣款','{$user['id']}','已完成')");
    }
  	$DB->query("UPDATE `ytidc_user` SET `money`='{$new_money}' WHERE `username`='{$user['username']}'");
  	$DB->query("INSERT INTO `ytidc_fenzhan`(`domain`, `title`, `subtitle`, `description`, `keywords`, `notice`, `user`, `status`) VALUES ('{$domain}','{$title}','企业级云服务器','{$description}','','{$conf['notice']}','{$user['id']}','1')");
	@header("Location: ./msg.php?msg=开通成功！");
  	exit;
}
$template = file_get_contents("../templates/".$template_name."/user_site.template");
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
	'user' => $user,
);
echo set_template($template, $template_name, $template_code);
?>