<?php

//星外对接
//作责：地狱筱雨
//邮箱：2031464675@qq.com
//赞助：地狱云主机idc.netech.cc

function XiaoYu_XingWaiVPS_GETSESSION($params){
  	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $params['url']); 
	// 获取头部信息 
	curl_setopt($ch, CURLOPT_HEADER, 1); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params['data']));
	$content = curl_exec($ch);  
	curl_close($ch); 
	// 解析http数据流 
	list($header, $body) = explode("\r\n\r\n", $content); 
	// 解析cookie
	preg_match("/set\-cookie:([^\r\n]*)/i", $header, $matches); 
	$cookie = explode(';', $matches[1]);
	$cookies = $cookie[0];
  	return $cookies;
}

function XiaoYu_XingWaiVPS_POSTDATA($params){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $params['url']);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HEADER,1);
  	curl_setopt($ch, CURLOPT_TIMEOUT,600);   //只需要设置一个秒的数量就可以   
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_COOKIE,$params['cookie']);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params['data']));
	$content = curl_exec($ch);
	curl_close($ch);
	return iconv('GB2312', 'UTF-8', $content);
}

function XiaoYu_XingWaiVPS_GETDATA($params){
  	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $params['url']."?".http_build_query($params['data']));
	curl_setopt($ch, CURLOPT_HEADER,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_COOKIE,$params['cookie']);
  	$content = curl_exec($ch);
  	curl_close($ch);
  	return iconv('GB2312', 'UTF-8', $content);
}

function XiaoYu_XingWaiVPS_CreateService($params){
	$cookie = XiaoYu_XingWaiVPS_GETSESSION(array('url' => $params['server']['ip']."/user/userlogin.asp", 'data' => array('username' => $params['server']['username'], 'password'=>$params['server']['password'])));
	if(empty($cookie)){
		return array(
			'ret' => 'fail',
			'msg' => 'Cookie获取失败',
		);
	}
	$XiaoYu_XingWaiVPS_POSTDATA = array(
		'url' => $params['server']['ip'] . "/user/selfvps2.asp",
		'cookie' => $cookie,
		'data' => array(
			'vpsname' => $params['service']['username'],
			'vpspassword' => $params['service']['password'],
			'year' => $params['service']['time'] / 10,
			'id' => $params['configoption']['productid'],
			'ServerlistID' => $params['configoption']['serverlistid'],
		),
	);
	$content = XiaoYu_XingWaiVPS_POSTDATA($XiaoYu_XingWaiVPS_POSTDATA);
	if(strstr($content, "云服务器开通成功")){
		@preg_match('/id=(.*)&/iU', $content, $itemid);
		return array(
			'ret' => 'success',
			'username' => $params['service']['username'],
			'password' => $params['service']['password'],
			'enddate' => date('Y-m-d',strtotime("+{$params['service']['time']} months", time())),
			'configoption' => $itemid[1],
		);
	}else{
		@preg_match('/e=(.*)\\n/iU', $content, $errmsg);
		return array(
			'ret' => 'fail',
			'msg' => $errmsg[1],
		);
	}
}

function XiaoYu_XingWaiVPS_RenewService($params){
	$cookie = XiaoYu_XingWaiVPS_GETSESSION(array('url' => $params['server']['ip']."/user/userlogin.asp", 'data' => array('username' => $params['server']['username'], 'password'=>$params['server']['password'])));
	if(empty($cookie)){
		return array(
			'ret' => 'fail',
			'msg' => 'Cookie获取失败',
		);
	}
  	$XiaoYu_XingWaiVPS_GETDATA = array(
      	'url' => $params['server']['ip'] . "/user/vpsadm2.asp",
      	'cookie' => $cookie,
      	'data' => array(
          	'id' => $params['service']['configoption'],
          	'go' => 'c',
        ),
    );
  	XiaoYu_XingWaiVPS_GETDATA($XiaoYu_XingWaiVPS_GETDATA);
	$year = $params['data']['time'] / 10;
	$XiaoYu_XingWaiVPS_POSTDATA = array(
		'url' => $params['serverhostname'] . "/user/vpsadmrepay2.asp",
		'cookie' => $cookie,
		'data' => array(
			'id' => $params['domain'],
			'year' => $year,
		),
	);
	$content = XiaoYu_XingWaiVPS_POSTDATA($XiaoYu_XingWaiVPS_POSTDATA);/*
	if(empty($content)){
		return '服务器返回空白';
	}
	if(strstr($content, '服务器延期成功')){
		return '成功';
	}else{
		@preg_match('/e=(.*)\\n/iU', $content, $errmsg);
        return $errmsg[1];
	}*/
  return array(
  	'ret' => 'success',
  	'enddate' => date('Y-m-d', strtotime("+{$params['data']['time']} months", strtotime($params['service']['enddate']))),
  );
}


?>