<?php

include("../includes/common.php");
if(!empty($_GET['code'])){
  	$code = daddslashes($_GET['code']);
  	$result = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$code}'");
  	if($result->num_rows == 1){
      	$_SESSION['invite'] = $code;
    }
}else{
	$_SESSION['invite'] = $site['user'];
}
if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email'])){
    $username = daddslashes($_POST['username']);
    $password = daddslashes($_POST['password']);
    $password = base64_encode($password);
  	$email = daddslashes($_POST['email']);
    $invite = $_SESSION['invite'];
  	$domain = $_SERVER['HTTP_HOST'];
  	$site = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `domain`='{$domain}'")->fetch_assoc();
  	$site = $site['id'];
  	$DB->query("INSERT INTO `ytidc_user` (`username`, `password`, `email`, `money`, `grade`, `invite`, `site`, `status`) VALUE ('{$username}', '{$password}', '{$email}', '0.00', '{$conf['defaultgrade']}', '{$invite}', '{$site}', '1')");
  	@header("Location: ./login.php");
  	exit();
}

$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
);
$template = file_get_contents("../templates/".$template_name."/user_register.template");
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
