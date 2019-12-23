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
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./notice.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_notice` WHERE `id`='{$id}' AND `site`='{$site['id']}'");
  	@header("Location: ./msg.php?msg=删除成功");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$$k = daddslashes($v);
    }
  	$date = date('Y-m-d');
  	$DB->query("UPDATE `ytidc_notice` SET `title`='{$title}', `content`='{$content}', `date`='{$date}' WHERE `id`='{$id}' AND `site`='{$site['id']}'");
  	@header("Location: ./msg.php?msg=修改成功");
  	exit;
}
$row = $DB->query("SELECT * FROM `ytidc_notice` WHERE `id`='{$id}' AND `site`='{$site['id']}'")->fetch_assoc();
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'user' => $user,
	'notice' => $row,
	'template_file_path' => '../templates/'.$conf['template'],
);
$template = file_get_contents("../templates/".$conf['template']."/admin_editnotice.template");
$include_file = find_include_file($template);
foreach($include_file[1] as $k => $v){
		if(file_exists("../templates/".$conf['template']."/".$v)){
			$replace = file_get_contents("../templates/".$conf['template']."/".$v);
			$template = str_replace("[include[{$v}]]", $replace, $template);
		}
		
}
$template = template_code_replace($template, $template_code);
echo $template;
?>