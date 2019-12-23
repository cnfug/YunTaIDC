<?php

include("../includes/common.php");
$domain = $_SERVER['HTTP_HOST'];
$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$row['user']}'")->fetch_assoc();
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
$title = "管理后台";
$result = $DB->query("SELECT * FROM `ytidc_notice` WHERE `site`='{$site['id']}'");
$notice_template = file_get_contents("../templates/".$conf['template']."/admin_notice_list.template");
while($row = $result->fetch_assoc()){
		$notice_template_code = array(
			'title' => $row['title'],
			'id' => $row['id'],
			'date' => $row['date'],
		);
		$notice_template_new = $template_code_new . template_code_replace($notice_template, $notice_template_code);
}
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'user' => $user,
	'notice' => $notice_template_new,
	'template_file_path' => '../templates/'.$conf['template'],
);
$template = file_get_contents("../templates/".$conf['template']."/admin_notice.template");
$include_file = find_include_file($template);
foreach($include_file[1] as $k => $v){
		if(file_exists("../templates/".$conf['template']."/".$v)){
			$replace = file_get_contents("../templates/".$conf['template']."/".$v);
			$template = str_replace("[include[{$v}]]", $replace, $template);
		}
		
}
$template = template_code_replace($template, $template_code);
echo $template;