<?php

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

function find_include_file($content){
	preg_match_all("/\[include\[(.*)\]\]/U", $content, $return);
	return $return;
}

function find_list_html($keyword, $content){
	preg_match_all("/\[{$keyword}\](.*)\[\/{$keyword}\]/U", $content, $return);
	return $return;
}

?>