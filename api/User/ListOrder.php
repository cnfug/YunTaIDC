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
		$type1 = $DB->query("SELECT * FROM `ytidc_order` WHERE `user`='{$user['id']}' ORDER BY `orderid` DESC");
		while($row = $type1->fetch_assoc()){
			$data[$row['orderid']] = array(
				'description' => $row['description'],
				'money' => $row['money'],
				'action' => $row['action'],
				'status' => $row['status'],
			);
		}
		$returndata = array(
			'status' => 'success',
			'msg' => '成功！',
			'data' => $data
		);
		exit(json_encode($returndata));
	}
}
?>