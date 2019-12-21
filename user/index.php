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

$servicecount = $DB->query("SELECT * FROM `ytidc_service` WHERE `userid`='{$user['id']}'")->num_rows;
$wordercount = $DB->query("SELECT * FROM `ytidc_worder` WHERE `user`='{$user['id']}'")->num_rows;
$invitecount = $DB->query("SELECT * FROM `ytidc_user` WHERE `invite`='{$user['id']}'")->num_rows;
$noticecount = $DB->query("SELECT * FROM `ytidc_notice`")->num_rows;
$template = file_get_contents("../templates/".$conf['template']."/user_index.template");
$include_file = find_include_file($template);
foreach($include_file[1] as $k => $v){
		if(file_exists("../templates/".$conf['template']."/".$v)){
			$replace = file_get_contents("../templates/".$conf['template']."/".$v);
			$template = str_replace("[include[{$v}]]", $replace, $template);
		}
		
}
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$conf['template'],
	'data' => array(
		'invitecount' => $invitecount,
		'noticecount' => $noticecount,
		'servicecount' => $servicecount,
		'wordercount' => $wordercount,
	),
	'user' => $user,
);
$template = template_code_replace($template, $template_code);
echo $template;
?>