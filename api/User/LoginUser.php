<?php

include("../../includes/common.php");
header("Content-type: text/json");
$params = daddslashes($_GET);
if(empty($params['username']) || empty($params['password'])){
	$returndata = array(
		'status' => 'fail',
		'msg' => '账号或密码为空！',
	);
	exit(json_encode($returndata));
}else{
	$params['password'] = base64_encode($params['password']);
	$result = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$params['username']}' AND `password`='{$params['password']}'");
	if($result->num_rows != 1){
		$returndata = array(
			'status' => 'fail',
			'msg' => '账号密码错误！',
		);
		exit(json_encode($returndata));
	}else{
		$user = $result->fetch_assoc();
		$data = array(
			'username' => $user['username'],
			'password' => base64_decode($user['password']),
			'email' => $user['email'],
			'grade' => $user['grade'],
			'money' => $user['money'],
		);
		$returndata = array(
			'status' => 'success',
			'msg' => '登陆成功！',
			'data' => $data,
		);
		exit(json_encode($returndata));
	}
}
?>