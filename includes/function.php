<?php

function daddslashes($string, $force = 0, $strip = FALSE) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force, $strip);
			}
		} else {
			$string = addslashes($strip ? stripslashes($string) : $string);
		}
	}
	return $string;
}

function encrypt($string){
	$return = md5('ytidc_'.$string);
	return $return;
}

function SearchArray($array, $search){
	foreach($array as $k => $v){
		if($v == $search){
			return true;
		}
	}
	return false;
}

function ExplodeConfig($configoption){
	//ax: 1,
	//bx :2,
	$configoption = explode(',', $configoption);
	foreach($configoption as $k => $v){
		$configoption2 = explode(':', $v);
      	$configoption4 = str_replace("\r\n", '', $configoption2[0]);
		$configoption3[$configoption4] = $configoption2[1];
	}
	$configoption = $configoption3;
	return json_encode($configoption);
}

function check_price($price, $zero=false){
	if($price == 0){
		if($zero == true){
			return true;
		}else{
			return false;
		}
	}
	if($price > 0){
		return true;
	}else{
		return false;
	}
	return false;
}

function get_dir($dir){
	if ($handle = opendir($dir)) {
		$array = array();
    	while (false !== ($entry = readdir($handle))) {
    	   if ($entry != "." && $entry != "..") {
        	    $array[$entry] = $entry;
        	}
    	}
    	closedir($handle);
	}
	return $array;
}

function url_encode($str) {  
    if(is_array($str)) {  
        foreach($str as $key=>$value) {  
            $str[urlencode($key)] = url_encode($value);  
        }  
    } else {  
        $str = urlencode($str);  
    }  

    return $str;  
}

function url_decode($str) {  
    if(is_array($str)) {  
        foreach($str as $key=>$value) {  
            $str[urldecode($key)] = urldecode($value);  
        }  
    } else {  
        $str = urldecode($str);  
    }  

    return $str;  
}

function ismobile() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;
    
    //此条摘自TPM智能切换模板引擎，适合TPM开发
    if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
        return true;
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
        );
        //从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;

}

?>