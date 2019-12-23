<?php

include("./includes/common.php");
$template = file_get_contents("./templates/".$conf['template']."/index.template");
$include_file = find_include_file($template);
foreach($include_file[1] as $k => $v){
		if(file_exists("./templates/".$conf['template']."/".$v)){
			$replace = file_get_contents("./templates/".$conf['template']."/".$v);
			$template = str_replace("[include[{$v}]]", $replace, $template);
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