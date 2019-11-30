<?php

//EP对接
//作者：地狱
//赞助：idc.netech.cc

function XiaoYu_Easypanel_SendData($params){
  	$content = file_get_contents($params['url']."?".http_build_query($params['get']));
  	return json_decode($content,true);
}

function XiaoYu_Easypanel_CreateService($params){
  	$configoption = json_decode($params['product']['configoption'], true);
  	$random = rand(1000,9999);
  	$data = array(
      	'url' => $params['server']['serverip'].':3312/api/index.php',
      	'get' => array('c' => 'whm',
                      'a' => 'add_vh',
                      'r' => $random,
                      's' => md5('add_vh'.$params['server']['serveraccesshash'].$random),
                      'json' => '1',
                      'init' => '1',
                      'name' => $params['service']['username'],
                      'passwd' => $params['service']['password'],
                      'product_id' => $configoption['productid'],
        ),
    );
  	$return = XiaoYu_Easypanel_SendData($data);
  	if($return['result'] == 200){
      	$ret = array(
          	'status' => 'success',
          	'username' => $params['service']['username'],
          	'password' => $params['service']['password'],
          	'enddate' => date('Y-m-d', strtotime("+{$params['service']['time']} months", time())),
        );
      	return $ret;
    }else{
      	$ret = array(
          	'status' => 'fail',
          	'msg' => $return['msg'],
        );
      	return $ret;
    }
}

function XiaoYu_Easypanel_RenewService($params){
  	return array(
      	'status' => 'success',
      	'enddate' => date('Y-m-d', strtotime("+{$params['data']['time']} months", strtotime($params['service']['enddate']))),
    );
}

function XiaoYu_Easypanel_DeleteService($params){
  	$random = rand(1000,9999);
  	$data = array(
      	'url' => $params['server']['serverip'].':3312/api/index.php',
      	'get' => array('c' => 'whm',
                      'a' => 'del_vh',
                      'r' => $random,
                      's' => md5('add_vh'.$params['server']['serveraccesshash'].$random),
                      'json' => '1',
                      'name' => $params['service']['username'],
        ),
    );
  	$ret = XiaoYu_Easypanel_SendData($data);
  	if($ret['ret'] == 200){
   		return array(
          	'status' => 'success',
        );
    }else{
      	return array(
          	'ret' => $ret['ret'],
        );
    }
}

?>