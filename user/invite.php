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
$title = "邀请记录";
include("./head.php");
$result = $DB->query("SELECT * FROM `ytidc_user` WHERE `invite`='{$user['id']}'");
$invite_template = file_get_contents("../templates/".$template_name."/user_invite_list.template");
while($row = $result->fetch_assoc()){
	$invite_template_code = array(
		'id' => $row['id'],
		'username' => $row['username'],
	);
	$invite_template_new = $invite_template_new . template_code_replace($invite_template, $invite_template_code);
}
$template = file_get_contents("../templates/".$template_name."/user_invite.template");
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
	'invite' => $invite_template_new,
	'invite_link' => $conf['http']."://".$_SERVER['HTTP_HOST']."/user/register.php?code=".$user['id'],
);
$template = template_code_replace($template, $template_code);
echo $template;

?>