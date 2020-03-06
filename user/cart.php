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
$template = file_get_contents("../templates/".$template_name."/user_cart.template");
$time_template = find_list_html("周期列表", $template);
foreach($pdis as $k => $v){
	$time_template_code = array(
		'name' => $v['name'],
		'price' => $price[$row['id']] * $v['discount'],
	);
	$time_template_new = $time_template_new . template_code_replace($time_template[1][0], $time_template_code);
}
$template = str_replace($time_template[1][0], $time_template_new, $template);
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
	'user' => $user,
	'product' => array(
		'id' => $row['id'],
		'name' => $row['name'],
		'description' => $row['description'],
	),
);
echo set_template($template, $template_name, $template_code);
?>