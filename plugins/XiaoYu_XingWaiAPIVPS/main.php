<?php

//星外官方API对接
//作者：地狱筱雨
//版本：1.0

function XiaoYu_XingWaiAPIVPS_POSTDATA($params){
    $url = $params['url']."?".http_build_query($params['data']);
    $content = file_get_contents($url);
    $return = parse_str($content);
    return $return;
}

function XiaoYu_XingWaiAPIVPS_CreateService($params){
    $POSTDATA = array(
        'url' => $params['server']['serverip'],
        'data' => array(
            'userid' => $params['server']['username'],
            'userstr' => md5($params['server']['password']."7i24.com");
            'year' => $params['service']['time'] / 10,
            'idc' => $params['configoption']['idc'],
            'productid' => $params['configoption']['productid'],
            'action' => "activate",
            'attach' => '云塔IDC系统'
        );
    );
    $content = XiaoYu_XingWaiAPIVPS_POSTDATA($POSTDATA;
    if($content['ret'] == "ok"){
        return array(
            'status' => 'success',
            'username' => $content['vpsname'],
            'password' => $content['vpspassword'],
            'enddate' => $content['enddate'],
        );
    }else{
        return array(
            'status' => 'fail',
            'msg' => $content['freehosinfo'],
        );
    }
}

function XiaoYu_XingWaiAPIVPS_RenewService($params){
    $POSTDATA = array(
        'url' => $params['server']['serverip'],
        'data' => array(
            'userid' => $params['server']['username'],
            'userstr' => md5($params['server']['password']."7i24.com");
            'year' => $params['data']['time'] / 10,
            'vpsname' => $params['service']['username'],
            'action' => "renew",
            'attach' => '云塔IDC系统'
        );
    );
    $content = XiaoYu_XingWaiAPIVPS_POSTDATA($POSTDATA);
    if($content['ret'] == "ok"){
        return array(
            'status' => 'success',
            'enddate' => $content['enddate']
        );
    }else{
        return array(
            'status' => 'fail',
            'msg' => $content['freehosinfo'],
        );
    }
}