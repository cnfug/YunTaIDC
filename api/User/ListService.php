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
		$service = $DB->query("SELECT * FROM `ytidc_service` WHERE `userid`='{$user['id']}'");
		$product1 = $DB->query("SELECT * FROM `ytidc_product`");
		while($row = $product1->fetch_assoc()){
			$product[$row['id']] = $row['name'];
		}
		while($row = $service->fetch_assoc()){
			$data[$row['id']] = array(
				'username' => $row['username'],
				'password' => base64_decode($row['password']),
				'enddate' => $row['enddate'],
				'product' => $product[$row['product']],
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