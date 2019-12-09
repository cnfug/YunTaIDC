<?php

//EP对接
//作者：地狱
//赞助：idc.netech.cc

function XiaoYu_Easypanel_SendData($params){
  	$content = file_get_contents($params['url']."?".http_build_query($params['get']));
  	return json_decode($content,true);
}

function XiaoYu_Easypanel_ConfigOption(){
	return array(
		'productid' => 'EP后台创建产品的产品ID',
	);
}

function XiaoYu_Easypanel_LoginService($params){
	return '<form action="http://'.$params['server']['serverip'].':3312/vhost/index.php?c=session&a=login" method="POST"><input type="hidden" name="username" value="'.$params['service']['username'].'"><input type="hidden" name="passwd" value="'.$params['service']['password'].'"><button type="submit">点击登陆</button></form>';
}

function XiaoYu_Easypanel_CreateService($params){
  	$configoption = json_decode($params['product']['configoption'], true);
  	$random = rand(1000,9999);
  	$data = array(
      	'url' => 'http://'.$params['server']['serverip'].':3312/api/index.php',
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
          	'configoption' => '无任何特殊配置',
        );
      	return $ret;
    }else{
    	if($return['msg'] == ""){
    		$return['msg'] == "Easypanel面板返回空白";
    	}
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
      	'url' => "http://".$params['server']['serverip'].':3312/api/index.php',
      	'get' => array('c' => 'whm',
                      'a' => 'del_vh',
                      'r' => $random,
                      's' => md5('del_vh'.$params['server']['serveraccesshash'].$random),
                      'json' => '1',
                      'name' => $params['service']['username'],
        ),
    );
  	$ret = XiaoYu_Easypanel_SendData($data);
  	if($ret['result'] == 200){
   		return array(
   			'status' => 'success',
   			'msg' => '删除成功',
   		);
    }else{
        return array(
   			'status' => 'fail',
   			'msg' => $data['url']."?".http_build_query($data['get']),
   		);
    }
}

?>