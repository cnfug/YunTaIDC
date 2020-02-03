<?php

include("./includes/common.php");
$custom = daddslashes($_GET['custom']);
if(empty($custom)){
	@header("Location: ./index.php");
	exit;
}else{
	$template = $custom.".template";
}
$template = file_get_contents("./custom/".$template);
$include_file = find_include_file($template);
foreach($include_file[1] as $k => $v){
		if(file_exists("./custom/".$v)){
			$replace = file_get_contents("./custom/".$v);
			$template = str_replace("[include[{$v}]]", $replace, $template);
		}
		
}
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => './custom/',
	'user' => $user,
);
$template = template_code_replace($template, $template_code);
echo $template;

?>