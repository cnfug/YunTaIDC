<?php

include("../includes/common.php");
header("Content-type: text/json");

foreach($_GET as $k => $v){
  	$params[$k] = daddslashes($v);
}

if(empty($params['ytidc_user']) && empty($params['ytidc_pass'])){
  	if($user->num_rows != 1){
      	$retdata = array(
      	'ret' => 'fail',
      	'msg' => '账号密码为空',
    );
  	 exit(json_encode($retdata));
    }else{
      	$user = $user->fetch_assoc();
    }
}else{
  	$ytuser = daddslashes($params['ytidc_user']);
  	$ytpass = daddslashes($params['ytidc_pass']);
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$ytuser}' AND `password`='{$ytpass}'");
  	if($user->num_rows != 1){
      	$retdata = array(
      	'ret' => 'fail',
      	'msg' => '账号密码错误',
    );
  	 exit(json_encode($retdata));
    }else{
      	$user = $user->fetch_assoc();
    }
}

if(empty($params['username']) || empty($params['password']) || empty($params['product']) || empty($params['time'])){
  	$retdata = array(
      	'ret' => 'fail',
      	'msg' => '参数不足',
    );
  	 exit(json_encode($retdata));
}
if($DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$parmas['username']}'")->num_rows != 0){
  	$retdata = array(
      	'ret' => 'fail',
      	'msg' => '服务账号已被占用',
    );
  	 exit(json_encode($retdata));
}
$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$params['product']}'")->fetch_assoc();
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'")->fetch_assoc();

if($user['grade'] == "0" || $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->num_rows != 1){
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
}else{
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->fetch_assoc();
}
$price = json_decode($grade['price'], true);
$price = $price[$product['id']];
$new_money = $user['money'] - $price;
if($new_money >= 0){
  	$DB->query("UPDATE `ytidc_user` SET `money`='{$new_money}' WHERE `id`='{$user['id']}'");
}else{
  	$retdata = array(
      	'ret' => 'fail',
      	'msg' => '用户余额不足',
    );
  	 exit(json_encode($retdata));
}
$date = date('Y-m-d');
$DB->query("INSERT INTO `ytidc_service`(`userid`, `username`, `password`, `enddate`, `product`, `configoption`, `status`) VALUES ('{$user['id']}','{$params['username']}','{$params['password']}','{$date}','{$product['id']}','','1')");
$serviceid = $DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$params['username']}' AND `password`='{$params['password']}'")->fetch_assoc();
$serviceid = $serviceid['id'];
$plugin = "../plugins/".$server['plugin']."/main.php";
if(!is_file($plugin) || !file_exists($plugin)){
  	$retdata = array(
      	'ret' => 'fail',
      	'msg' => '服务器插件不存在',
    );
  	 exit(json_encode($retdata));
}
include($plugin);
$postdata = array(
  	'service' => array(
      	'username' => $params['username'],
       	'password' => $params['password'],
      	'time' => $params['time'],
    ),
  	'product' => $product,
  	'server' => $server,
);
$function = $server['plugin']."_CreateService";
$return = $function($postdata);

if($return['status'] == "fail"){
  	$retdata = array(
      	'ret' => 'fail',
      	'msg' => $return['msg'],
    );
  	 exit(json_encode($retdata));
}else{
  	$DB->query("UPDATE `ytidc_service` SET `username`='{$return['username']}', `password`='{$return['password']}', `enddate`='{$return['enddate']}', `configoption`='{$return['configoption']}', `status`='激活' WHERE `id`='{$serviceid}'");
  	$retdata = array(
      	'ret' => 'success',
      	'msg' => '开通成功',
      	'username' => $return['username'],
      	'password' => $return['password'],
      	'enddate' => $return['enddate'],
    );
  	 exit(json_encode($retdata));
}

?>