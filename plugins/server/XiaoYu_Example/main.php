<?php

function XiaoYu_Example_CreateService($params){
	return array(
		'status' => 'success',
		'username' => $params['service']['username'],
		'password' => $params['service']['password'],
        'enddate' => date('Y-m-d', strtotime("+{$params['service']['time']['day']} days", time())),
        'configoption' => '无任何特殊配置',
	);
}

function XiaoYu_Example_RenewService($params){
	return array(
      	'status' => 'success',
      	'enddate' => date('Y-m-d', strtotime("+{$params['data']['time']['day']} days", strtotime($params['service']['enddate']))),
    );
}

function XiaoYu_example_DeleteService($params){
	return array(
		'status' => 'success',
		'msg' => '删除成功',
	);
}