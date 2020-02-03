<?php

include("../includes/common.php");
header("Content-type: text/json");
foreach($_GET as $k => $v){
  	$params[$k] = daddslashes($v);
}

if(empty($params['ytidc_user']) && empty($params['ytidc_pass'])){
  	$retdata = array(
      	'ret' => 'fail',
      	'msg' => '账号密码为空',
    );
  	 exit(json_encode($retdata));
}else{
  	$ytuser = $params['ytidc_user'];
  	$ytpass = base64_encode(daddslashes($params['ytidc_pass']));
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
if(empty($params['username'])|| empty($params['time'])){
  	$retdata = array(
      	'ret' => 'fail',
      	'msg' => '参数为空',
    );
  	 exit(json_encode($retdata));
}
$service = $DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$params['username']}' AND `userid`='{$user['id']}'")->fetch_assoc();
$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$service['product']}'")->fetch_assoc();
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'")->fetch_assoc();

if($user['grade'] == "0" || $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->num_rows != 1){
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
}else{
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->fetch_assoc();
}
$price = json_decode($grade['price'], true);
$pdis = json_decode(url_decode($product['time']),true);
foreach($pdis as $k => $v){
	if($v['name'] == $params['time']){
		$dis = array(
			'name' => $v['name'],
			'discount' => $v['discount'],
			'day' => $v['day'],
			'remark' => $v['remark'],
		);
	}
}
if(empty($dis)){
	$retdata = array(
      	'ret' => 'fail',
      	'msg' => '周期设置错误',
    );
  	 exit(json_encode($retdata));
}
$price = $price[$product['id']] * $dis['discount'];
if(!check_price($price, true)){
  	$retdata = array(
      	'ret' => 'fail',
      	'msg' => '价格错误，请联系上级处理！',
    );
  	 exit(json_encode($retdata));
}
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
$plugin = "../plugins/server/".$server['plugin']."/main.php";
if(!is_file($plugin) || !file_exists($plugin)){
  	$retdata = array(
      	'ret' => 'fail',
      	'msg' => '服务器插件不存在',
    );
  	 exit(json_encode($retdata));
}
include($plugin);
$postdata = array(
  	'data' => array(
      	'time' => $dis,
      	'username' => $params['username'],
    ),
  	'service' => $service,
  	'product' => $product,
  	'server' => $server,
);
$function = $server['plugin']."_RenewService";
$return = $function($postdata);
if($return['status'] == "fail"){
  	$retdata = array(
      	'ret' => 'fail',
      	'msg' => $return['msg'],
    );
  	 exit(json_encode($retdata));
}else{
  	$retdata = array(
      	'ret' => 'success',
      	'enddate' => $return['enddate'],
      	'msg' => '续费成功！',
    );
    $DB->query("UPDATE `ytidc_service` SET `enddate`='{$return['enddate']}' WHERE `id`='{$service['id']}'");
  	 exit(json_encode($retdata));
}
?>