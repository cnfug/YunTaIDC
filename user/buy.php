<?php

include("../includes/common.php");
if(!empty($_GET['type'])){
  	$type = daddslashes($_GET['type']);
  	$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `type`='{$type}' AND `hidden`='0' ORDER BY `weight` DESC");
}else{
  	$type = $DB->query("SELECT * FROM `ytidc_type` ORDER BY `weight` DESC")->fetch_assoc();
  	$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `type`='{$type['id']}' AND `hidden`='0' ORDER BY `weight` DESC");
}
if(!empty($_SESSION['ytidc_user']) && !empty($_SESSION['ytidc_adminkey'])){
  	$username = daddslashes($_SESSION['ytidc_user']);
  	$userkey = daddslashes($_SESSION['ytidc_adminkey']);
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}'")->fetch_assoc();
}
$grade = $user['grade'];
if($grade == 0){
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` ORDER BY `weight`")->fetch_assoc();
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
$template = file_get_contents("../templates/".$template_name."/user_buy.template");
$type = $DB->query("SELECT * FROM `ytidc_type` WHERE `status`='1' ORDER BY `weight` DESC");
$type_template = find_list_html("分类列表", $template);
while($row = $type->fetch_assoc()){
	$type_template_code = array(
		'name' => $row['name'],
		'id' => $row['id'],
	);
	$type_template_new = $type_template_new . template_code_replace($type_template[1][0], $type_template_code);
}
$template = str_replace($type_template[0][0], $type_template_new, $template);
$product_template = find_list_html("产品列表", $template);
while($row = $product->fetch_assoc()){
	$product_template_code = array(
		'name' => $row['name'],
		'id' => $row['id'],
		'price' => $price[$row['id']],
		'description' => $row['description'],
	);
	$product_template_new = $product_template_new . template_code_replace($product_template[1][0], $product_template_code);
}
$template = str_replace($product_template[0][0], $product_template_new, $template);
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
	'user' => $user,
);
echo set_template($template, $template_name, $template_code);

?>