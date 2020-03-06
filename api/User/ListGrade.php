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
		$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `status`='1'");
		while($row = $grade->fetch_assoc()){
			$data[$row['id']] = array(
				'name' => $row['name'],
				'need_save' => $row['need_save'],
				'need_paid' => $row['need_paid'],
				'need_money' => $row['need_money'],
				'default' => $row['default']
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