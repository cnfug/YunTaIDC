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

function PackConfig($configoption){
	$configoption = json_decode($configoption, true);
	foreach($configoption as $k => $v){
		$configoption2 = $configoption2 . "{$k}:{$v},\r\n";
	}
	return $configoption2;
}

function ChangeTemplateContent($params, $template){
  	foreach($params as $k => $v){
   		$template = str_replace($k, $v, $template);
    }
  	return $template;
}


?>