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
$template = file_get_contents("../templates/".$template_name."/user_worder.template");
$result = $DB->query("SELECT * FROM `ytidc_worder` WHERE `user`='{$user['id']}'");
$worder_template = find_list_html("服务单列表", $template);
while($row = $result->fetch_assoc()){
	$worder_template_code = array(
		'id' => $row['id'],
		'title' => $row['title'],
		'status' => $row['status'],
	);
	$worder_template_new = $worder_template_new . template_code_replace($worder_template[1][0], $worder_template_code);
}
$template = str_replace($worder_template[0][0], $worder_template_new, $template);
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
);
$template = template_code_replace($template, $template_code);
echo $template;