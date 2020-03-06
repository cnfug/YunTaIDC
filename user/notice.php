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
$result = $DB->query("SELECT * FROM `ytidc_notice` WHERE `site`='0'");
$template = file_get_contents("../templates/".$template_name."/user_notice.template");
$notice_template = find_list_html("公告列表", $template);
while($row = $result->fetch_assoc()){
	$notice_template_code = array(
		'id' => $row['id'],
		'title' => $row['title'],
	);
	$notice_template_new = $notice_template_new . template_code_replace($notice_template[1][0], $notice_template_code);
}
$result = $DB->query("SELECT * FROM `ytidc_notice` WHERE `site`='{$site['id']}'");
while($row = $result->fetch_assoc()){
	$notice_template_code = array(
		'id' => $row['id'],
		'title' => $row['title'],
	);
	$notice_template_new = $notice_template_new . template_code_replace($notice_template[1][0], $notice_template_code);
}
$template = str_replace($notice_template[0][0], $notice_template_new, $template);
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
	'user' => $user,
);
echo set_template($template, $template_name, $template_code);