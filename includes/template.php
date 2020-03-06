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
	preg_match_all("/\[{$keyword}\](.*?)\[\/{$keyword}\]/is", $content, $return);
	return $return;
}

function set_template_include_file($template, $template_name){
	if(empty($template) || empty($template_name)){
		return '模板引进参数不足';
	}else{
		$include_file = find_include_file($template);
		foreach($include_file[1] as $k => $v){
			if(file_exists(ROOT."/templates/".$template_name."/".$v)){
				$replace = file_get_contents(ROOT."/templates/".$template_name."/".$v);
				$template = str_replace("[include[{$v}]]", $replace, $template);
			}
		}
		return $template;
	}
}

function set_template($template, $template_name, $template_code){
	$template = set_template_include_file($template, $template_name);
	$template = template_code_replace($template, $template_code);
	return $template;
}

?>