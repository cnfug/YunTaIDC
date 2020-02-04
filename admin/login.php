<?php

include("../includes/common.php");
$domain = $_SERVER['HTTP_HOST'];
if(!empty($_POST['admin']) && !empty($_POST['password'])){
    $admin = daddslashes($_POST['admin']);
    $password = daddslashes($_POST['password']);
    if($admin != $site['admin'] && $password != $site['password']){
        exit('登陆错误，请前往正确的站点后台登陆！');
    }else{
        $_SESSION['fzadmin'] = $admin;
        $_SESSION['fzkey'] = md5($domain.$password."fz");
        @header("Location: ./index.php");
        exit;
    }
}

$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
);
$template = file_get_contents("../templates/".$template_name."/admin_login.template");
$include_file = find_include_file($template);
foreach($include_file[1] as $k => $v){
		if(file_exists("../templates/".$template_name."/".$v)){
			$replace = file_get_contents("../templates/".$template_name."/".$v);
			$template = str_replace("[include[{$v}]]", $replace, $template);
		}
		
}
$template = template_code_replace($template, $template_code);
echo $template;

?>