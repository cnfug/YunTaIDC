<?php

include("./includes/common.php");
$template = file_get_contents("./templates/".$conf['template']."/index.template");
$include_file = find_include_file($template);
foreach($include_file as $val){
		if(file_exists("./templates/".$conf['template']."/".$val[0])){
			$replace = file_get_contents("./templates/".$conf['template']."/".$val[0]);
			$template = str_replace("[include[{$val[0]}]]", $replace, $template);
		}
}
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => './templates/'.$conf['template'],
);
$template = template_code_replace($template, $template_code);
echo $template;

?>