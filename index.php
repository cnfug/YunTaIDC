<?php

include("./includes/common.php");
$template = file_get_contents("./templates/".$conf['template']."/index.template");

$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => './templates/'.$conf['template'],
);
$template = template_code_replace($template, $template_code);
echo $template;

?>