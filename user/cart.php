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
$title = "产品订购确认";
$id = daddslashes($_GET['id']);
$row = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$id}'")->fetch_assoc();
$pdis = json_decode(url_decode($row['time']), true);
if($user['grade'] == "0" || $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->num_rows != 1){
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
}else{
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->fetch_assoc();
}
$price = json_decode($grade['price'], true);
$template = file_get_contents("../templates/".$conf['template']."/user_cart.template");
$time_template = file_get_contents("../templates/".$conf['template']."/user_cart_time.template");
foreach($pdis as $k => $v){
	$time_template_code = array(
		'name' => $v['name'],
		'price' => $price[$row['id']] * $v['discount'],
	);
	$time_template_new = $time_template_new . template_code_replace($time_template, $time_template_code);
}
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
	'user' => $user,
	'product' => array(
		'id' => $row['id'],
		'name' => $row['name'],
		'description' => $row['description'],
	),
	'time' => $time_template_new,
);
$template = template_code_replace($template, $template_code);
echo $template;
?>