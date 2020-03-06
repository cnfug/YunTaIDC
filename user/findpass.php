<?php

include("../includes/common.php");
if(!empty($_SESSION['ytidc_user']) && !empty($_SESSION['ytidc_token'])){
	$username = daddslashes($_SESSION['ytidc_user']);
  	$userkey = daddslashes($_SESSION['ytidc_token']);
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}'");
  	if($user->num_rows == 1){
    	$user = $user->fetch_assoc();
      	$userkey1 = md5($_SERVER['HTTP_HOST'].$user['password']);
      	if($userkey == $userkey1){
      		@header("Location: ./index.php");
      		exit;
      	}
    }
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once(ROOT.'PHPMailer/src/PHPMailer.php');
require_once(ROOT.'PHPMailer/src/SMTP.php');
if(!empty($_POST['username'])){
	$username = daddslashes($_POST['username']);
    $user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}'");
    if($user->num_rows != 1){
    	exit('该用户不存在');
    }else{
    	$user = $user->fetch_assoc();

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
				$user['password'] = base64_decode($user['password']);
			    //Content
			    $mail->isHTML(true);                                  // 将电子邮件格式设置为HTML
			    $mail->Subject = '找回用户密码';                       // 邮件主题，即标题
			    $mail->Body    = "亲爱的{$user['username']}您好：<br /><br />
			    				  您的账号刚才在本站点进行了找回密码的操作，您的密码为：{$user['password']}。若非您本人操作则无需理会本邮件，谢谢您的支持！<br /><br /><br />
			    				  {$site['title']}<br />
			    				  {$site['domain']}";    //邮件内容
			
			    $mail->send();
			    exit('发送成功！<a href="/">点击返回</a>');
			} catch (Exception $e) {
			    exit('发送失败: '. $mail->ErrorInfo);
			}	
    }
}
$template_code = array(
	'site' => $site,
	'config' => $conf,
	'template_file_path' => '../templates/'.$template_name,
);
$template = file_get_contents("../templates/".$template_name."/user_findpass.template");
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