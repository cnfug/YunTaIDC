<?php

include("./includes/common.php");
$template = file_get_contents("./templates/".$template_name."/index.template");
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => './templates/'.$template_name,
);
echo set_template($template, $template_name, $template_code);

?>