<?php

include("./includes/common.php");
$date = date('Y-m-d');
//清除到期服务
$result = $DB->query("SELECT * FROM `ytidc_service` WHERE `enddate`='{$date}'");
while($row = $result->fetch_assoc()){
  	$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$row['product']}'")->fetch_assoc();
  	$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'")->fetch_assoc();
  	$plugin = "./plugins/".$server['plugin']."/main.php";
  	if(!is_file($plugin) || !file_exists($plugin)){
      	$DB->query("DELETE * FROM `ytidc_service` WHERE `id`='{$row['id']}'");
    }else{
      	include($plugin);
      	$function = $server['plugin']."_DeleteService";
      	$postdata = array(
          	'service' => $row,
          	'server' => $server,
          	'product' => $product,
        );
      	$return = $function($postdata);
      	$error_log = file_get_contents("./service_error.log");
      	$error_log = $error_log . "\r\n". $return['status'] . ":" . $return['msg'];
      	file_put_contents("./service_error.log", $error_log);
      	$DB->query("DELETE FROM `ytidc_service` WHERE `id`='{$row['id']}'");
    }
  	exit('OK');
}

?>