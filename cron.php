<?php

include("./includes/common.php");
$date = date('Y-m-d');
//清除到期服务
$result = $DB->query("SELECT * FROM `ytidc_service` WHERE `enddate`='{$date}'");
$DB->query("UPDATE `ytidc_config` SET `v`='{$date}' WHERE `k`='crondate'");
while($row = $result->fetch_assoc()){
  	$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$row['product']}'")->fetch_assoc();
  	$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'")->fetch_assoc();
  	$plugin = "./plugins/server/".$server['plugin']."/main.php";
  	if(!is_file($plugin) || !file_exists($plugin)){
      	$DB->query("DELETE FROM `ytidc_service` WHERE `id`='{$row['id']}'");
    }else{
      	include($plugin);
      	$function = $server['plugin']."_DeleteService";
      	$postdata = array(
          	'service' => $row,
          	'server' => $server,
          	'product' => $product,
        );
      	$return = $function($postdata);
      	$error_log = file_get_contents(ROOT."logs/cron_error.log");
      	$error_log = $error_log . "\r\n". $return['status'] . ":" . $return['msg'];
      	file_put_contents(ROOT."logs/cron_error.log", $error_log);
      	$DB->query("DELETE FROM `ytidc_service` WHERE `id`='{$row['id']}'");
    }
}

$date = date("Y-m-d", strtotime("+{$conf['mail_alert']} days", time()));
$result = $DB->query("SELECT * FROM `ytidc_service` WHERE `enddate`='{$date}'");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once(ROOT.'PHPMailer/src/PHPMailer.php');
require_once(ROOT.'PHPMailer/src/SMTP.php');
while($row = $result->fetch_assoc()){
	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$row['userid']}'")->fetch_assoc();
	$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$row['product']}'")->fetch_assoc();
	if($user['site'] != 0){
		$site = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `id`='{$user['site']}'");
		if($site->num_rows != 1){
			$site = $conf;
			$site['domain'] = $_SERVER['HTTP_HOST'];
		}else{
			$site = $site->fetch_assoc();
		}
	}else{
		$site = $conf;
		$site['domain'] = $_SERVER['HTTP_HOST'];
	}
	$mail = new PHPMailer(true);
	try{
		$mail->SMTPDebug = 0;                                 // 启用详细的调试输出
	    $mail->isSMTP();                                      // 设置邮件使用SMTP
	    $mail->Host = $conf['smtp_host'];                         // 指定主要和备份SMTP服务器
	    $mail->SMTPAuth = true;                               // 启用SMTP验证
	    $mail->Username = $conf['smtp_user'];                   // SMTP用户名
	    $mail->Password = $conf['smtp_pass'];					// SMTP密码
	    if($conf['smtp_secure'] != 0){
			$mail->SMTPSecure = $conf['smtp_secure'];
	    }                            // 启用TLS加密，`ssl`也接受
	    $mail->Port = $conf['smtp_port'];                                    // TCP端口连接
	
	    //收件人
	    $mail->setFrom($conf['smtp_user'], '云塔IDC财务管理系统');// 设置发送人信息(参数1：发送人邮箱，参数2：发送人名称)
	    $mail->addAddress($user['email']);     // 添加收件人
	
	    //Content
	    $mail->isHTML(true);                                  // 将电子邮件格式设置为HTML
	    $mail->Subject = '产品续费提醒';                       // 邮件主题，即标题
	    $mail->Body    = "亲爱的{$user['username']}您好：<br /><br />
	    				  您的服务{$row['username']}产品{$product['name']}将在{$conf['mail_alert']}天内到期，为避免数据丢失，请尽快前往续费！<br /><br /><br />
	    				  {$site['title']}<br />
	    				  {$site['domain']}";    //邮件内容
	
	    $mail->send();
	    echo '发送成功';
	} catch (Exception $e) {
	    echo '发送失败: ', $mail->ErrorInfo;
	}	
}

exit('OK');
?>