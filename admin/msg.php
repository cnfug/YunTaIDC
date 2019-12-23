<?php

include("../includes/common.php");

$domain = $_SERVER['HTTP_HOST'];
$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$site['user']}'")->fetch_assoc();
if(empty($_SESSION['fzadmin']) || empty($_SESSION['fzkey'])){
  	@header("Location: ./login.php");
  	exit;
}
$fzadmin = daddslashes($_SESSION['fzadmin']);
$fzkey = daddslashes($_SESSION['fzkey']);
if($fzadmin != $site['admin'] && $fzkey != md5($_SERVER['HTTP_HOST'].$site['password']."fz")){
  	@header("Location: ./login.php");
  	exit;
}
$msg = daddslashes($_GET['msg']);
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'user' => $user,
	'msg' => $msg,
	'template_file_path' => '../templates/'.$conf['template'],
);
$template = file_get_contents("../templates/".$conf['template']."/admin_msg.template");
$include_file = find_include_file($template);
foreach($include_file[1] as $k => $v){
		if(file_exists("../templates/".$conf['template']."/".$v)){
			$replace = file_get_contents("../templates/".$conf['template']."/".$v);
			$template = str_replace("[include[{$v}]]", $replace, $template);
		}
		
}
$template = template_code_replace($template, $template_code);
echo $template;