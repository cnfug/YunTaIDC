<?php

include("../includes/common.php");
if(!empty($_GET['type'])){
  	$type = daddslashes($_GET['type']);
  	$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `type`='{$type}'");
}else{
  	$type = $DB->query("SELECT * FROM `ytidc_type`")->fetch_assoc();
  	$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `type`='{$type['id']}'");
}
if(!empty($_SESSION['ytidc_user']) && !empty($_SESSION['ytidc_adminkey'])){
  	$username = daddslashes($_SESSION['ytidc_user']);
  	$userkey = daddslashes($_SESSION['ytidc_adminkey']);
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}'")->fetch_assoc();
}
$grade = $user['grade'];
if($grade == 0){
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
  	$price = json_decode($grade['price'], true);
}else{
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'");
  	if($grade->num_rows != 1){
      	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
  		$price = json_decode($grade['price'], true);
    }else{
      	$grade = $grade->fetch_assoc();
      	$price = json_decode($grade['price'], true);
    }
}
$type = $DB->query("SELECT * FROM `ytidc_type` WHERE `status`='1' ORDER BY `weight` DESC");
$product_template = file_get_contents("../templates/".$template_name."/user_buy_product.template");
$type_template = file_get_contents("../templates/".$template_name."/user_buy_type.template");
while($row = $type->fetch_assoc()){
	$type_template_code = array(
		'name' => $row['name'],
		'id' => $row['id'],
	);
	$type_template_new = $type_template_new . template_code_replace($type_template, $type_template_code);
}
while($row = $product->fetch_assoc()){
	$pdis = json_decode(url_decode($row['time']), true);
	$product_template_code = array(
		'name' => $row['name'],
		'id' => $row['id'],
		'description' => $row['description'],
		'price' => $price[$row['id']] * $pdis[1][discount],
		'time' => $row['time'],
	);
	$product_template_new = $product_template_new . template_code_replace($product_template, $product_template_code);
}
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
	'user' => $user,
	'product' => $product_template_new,
	'type' => $type_template_new,
);
$template = file_get_contents("../templates/".$template_name."/user_buy.template");
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