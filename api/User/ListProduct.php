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
		$product1 = $DB->query("SELECT * FROM `ytidc_product` WHERE `hidden`='0' AND `status`='1'");
		$type1 = $DB->query("SELECT * FROM `ytidc_type`");
		while($row = $type1->fetch_assoc()){
			$type[$row['id']] = $row['name'];
		}
		while($row = $product1->fetch_assoc()){
			$time = json_decode(url_decode($row['time']), 1);
			$data[$row['id']] = array(
				'name' => $row['name'],
				'description' => $row['description'],
				'type' => $type[$row['type']],
				'time' => $time,
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