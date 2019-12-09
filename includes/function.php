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
	$return = md5('dyidc_'.$string);
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

function template_code_replace($template, $template_code){
	foreach($template_code as $k1 => $v1){
		if(is_array($v1)){
			foreach($v1 as $k2 => $v2){
				$k3 = "[".$k1."[".$k2."]]";
				$template = str_replace($k3, $v2, $template);
			}
		}else{
			$k2 = "[".$k1."]";
			$template = str_replace($k2, $v1, $template);
		}
	}
	return $template;
}


?>