<?php
include("../../includes/common.php");
function generate_random( $length = 8 ){
		// 密码字符集，可任意添加你需要的字符
		$chars = 'abcdefghijklmnopqrstuvwxyzZ0123456789';
		$password = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$password .= $chars[mt_rand(0, strlen($chars) - 1)];
		}
		return $password;
}

$params = daddslashes($_GET);
if(empty($params['userid'])){
	exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=请输入代理用户名'));
}
$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$params['userid']}'");
if($user->num_rows != 1){
	exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=代理用户不存在'));
}else{
	$user = $user->fetch_assoc();
}
$userpass = md5(base64_decode($user['password'])."7i24.com");
if($params['userstr'] != $userpass){
	exit('ret=0&err=auth-failure&freehosinfo='.$userpass);
}
switch ($params['action']){
	case 'activate':
		if(empty($params['vpsname']) || empty($params['VPSpassword'])){
			$username = generate_random(8);
			while(!$DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}'")){
				$username = generate_random(8);
			}
			$password = generate_random(8);
		}else{
			$username = $params['vpsname'];
			$password = $params['VPSpassword'];
		}
		if(empty($params['productid']) || empty($params['year'])){
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=产品不存在'));
		}
		$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$params['productid']}'");
		if($product->num_rows != 1){
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=产品不存在'));
		}else{
			$product = $product->fetch_assoc();
			if($product['status'] != 1){
				exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=产品不允许开通'));
			}
		}
		$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'");
		if($server->num_rows != 1){
		  	exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=服务器错误，联系上级处理'));
		}else{
			$server = $server->fetch_assoc();
		}
		
		if($user['grade'] == "0" || $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->num_rows != 1){
		  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
		}else{
		  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->fetch_assoc();
		}
		$price = json_decode($grade['price'], true);
		$pdis = json_decode(url_decode($product['time']),true);
		foreach($pdis as $k => $v){
			if($v['discount'] == $params['year']){
				$dis = array(
					'name' => $v['name'],
					'discount' => $v['discount'],
					'day' => $v['day'],
					'remark' => $v['remark'],
				);
			}
		}
		if(empty($dis)){
		  	exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=周期设置错误，联系上级处理'));
		}
		$price = $price[$product['id']] * $dis['discount'];
		if(!check_price($price, true)){
		  	exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=价格设置错误，联系上级处理'));
		}
		if($user['site'] != 0){
			$usersite = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `id`='{$user['site']}'");
			if($usersite->num_rows != 1){
				$usersite = $site;
			}else{
				$usersite = $usersite->fetch_assoc();
				$usersiteowner = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$usersite['user']}'");
				if($usersiteowner->num_rows == 1){
					//分站奖励
					$usersiteowner = $usersiteowner->fetch_assoc();
					$usersiteownergrade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$usersiteowner['grade']}'");
					if($usersiteownergrade->num_rows != 1){
						$usersiteownergrade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'");
						if($usersiteownergrade->num_rows != 1){
							$usersiteownergrade = $DB->query("SELECT * FROM `ytidc_grade`")->fetch_assoc();
						}else{
							$usersiteownergrade = $usersiteownergrade->fetch_assoc();
						}
					}else{
						$usersiteownergrade = $usersiteownergrade->fetch_assoc();
					}
					if($usersiteownergrade['weight'] >= $grade['weight']){
						$usersiteownerprice = json_decode($usersiteownergrade['price'] ,true);
						//总共奖励金额计算：用户价格组价格 - 分站价格组价格
						$usersiteownerprice = $usersiteownerprice[$product['id']] * $dis['discount'];
						$usersitemoneyprice = $price -  $usersiteownerprice;
						//必须要大于1块钱才会进行邀请返现，防止出现过小金额
						if($usersitemoneyprice >= 1){
							//若有邀请者，检查邀请者是否存在，分站后台设置邀请返现是否合理，防止刷余额情况
							if(!empty($user['invite']) && $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$user['invite']}'")->num_rows == 1 && $usersite['invitepercent'] < 100){
								//邀请奖励
								$invite = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$user['invite']}'")->fetch_assoc();
								//邀请者奖励金额计算：总共奖励金额 * 分站设置邀请返现比例
								$giftmoney = $usersitemoneyprice * $usersite['invitepercent'] / 100;
								//再次检查返现是否超出总计奖励金额
								if($usersitemoneyprice >= $giftmoney){
								//返现后所剩金额
									$usersitemoneyprice = $usersitemoneyprice - $giftmoney;
									$invitemoney = $invite['money'] + $giftmoney;
									$DB->query("UPDATE `ytidc_user` SET `money`='{$invitemoney}' WHERE `id`='{$invite['id']}'");
								  	$orderid = date('YmdHis').rand(1000, 99999);
								  	$DB->query("INSERT INTO `ytidc_order`(`orderid`, `description`, `money`, `action`, `user`, `status`) VALUES ('{$orderid}','邀请奖励返现','{$giftmoney}','加款','{$invite['id']}','已完成')");
								}
							}
						}
						//防止出现负数
						if($usersitemoneyprice >= 0){
							$usersiteownermoney = $usersiteowner['money'] + $usersitemoneyprice;
							$DB->query("UPDATE `ytidc_user` SET `money`='{$usersiteownermoney}' WHERE `id`='{$usersiteowner['id']}'");	
						  	$orderid = date('YmdHis').rand(1000, 99999);
						  	$DB->query("INSERT INTO `ytidc_order`(`orderid`, `description`, `money`, `action`, `user`, `status`) VALUES ('{$orderid}','下级用户开通服务返现','{$usersitemoneyprice}','加款','{$usersiteowner['id']}','已完成')");
						}
					}
				}
			}
		}else{
			if(!empty($user['invite']) && $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$user['invite']}'")->num_rows == 1 && $conf['invitepercent'] < 100){
				//邀请奖励
				$invite = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$user['invite']}'")->fetch_assoc();
				//邀请者奖励金额计算：(订单金额 - 最高等级价格) * 分站设置邀请返现比例
				$highgrade = $DB->query("SELECT * FROM `ytidc_grade` ORDER BY `weight` DESC")->fetch_assoc();
				$highgradeprice = json_decode($highgrade['price'], true);
				$highgradeprice =  $highgradeprice[$product['id']] * $dis['discount'];
				$inviteprice = $price - $highgradeprice;
				$giftmoney = $inviteprice * $conf['invitepercent'] / 100;
				//再次检查返现是否超出总计奖励金额
				if($inviteprice >= $giftmoney && $inviteprice >= 1){
					$invitemoney = $invite['money'] + $giftmoney;
					$DB->query("UPDATE `ytidc_user` SET `money`='{$invitemoney}' WHERE `id`='{$invite['id']}'");
					$orderid = date('YmdHis').rand(1000, 99999);
					$DB->query("INSERT INTO `ytidc_order`(`orderid`, `description`, `money`, `action`, `user`, `status`) VALUES ('{$orderid}','邀请奖励返现','{$giftmoney}','加款','{$invite['id']}','已完成')");
				}
			}
		}
		$new_money = $user['money'] - $price;
		if($new_money >= 0){
		  	$DB->query("UPDATE `ytidc_user` SET `money`='{$new_money}' WHERE `id`='{$user['id']}'");
		  	$orderid = date('YmdHis').rand(1000, 99999);
		  	$DB->query("INSERT INTO `ytidc_order`(`orderid`, `description`, `money`, `action`, `user`, `status`) VALUES ('{$orderid}','开通服务','{$price}','扣款','{$user['id']}','已完成')");
		}else{
		  	exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=用户余额不足'));
		}
		$date = date('Y-m-d',strtotime("+{$dis[day]} days", time()));
		$service_password = base64_encode($password);
		$DB->query("INSERT INTO `ytidc_service`(`userid`, `username`, `password`, `enddate`, `product`,`promo_code` ,`configoption`, `status`) VALUES ('{$user['id']}','{$username}','{$service_password}','{$date}','{$product['id']}','{$promo['code']}','','等待审核')");
		$serviceid = $DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$username}' AND `password`='{$service_password}'")->fetch_assoc();
		$serviceid = $serviceid['id'];
		$plugin = ROOT."/plugins/server/".$server['plugin']."/main.php";
		if(!is_file($plugin) || !file_exists($plugin)){
		  	exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=上级服务器插件设置错误'));
		}
		include($plugin);
		$postdata = array(
		  	'service' => array(
		      	'username' => $username,
		       	'password' => $password,
		      	'time' => $dis,
		    ),
		  	'product' => $product,
		  	'server' => $server,
		);
		$function = $server['plugin']."_CreateService";
		$return = $function($postdata);
		
		if($return['status'] == "fail"){
		  	exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo='.$return['msg']));
		}else{
			$return_pass = base64_encode($return['password']);
		  	$DB->query("UPDATE `ytidc_service` SET `username`='{$return['username']}', `password`='{$return_pass}', `enddate`='{$return['enddate']}', `configoption`='{$return['configoption']}', `status`='激活' WHERE `id`='{$serviceid}'");
		  	exit(iconv("UTF-8", "GB2312", 'ret=ok&vpsname='.$username.'&vpspassword='.$password.'&endtime='.$return['enddate'].'&cpurl='.$_SERVER['HTTP_HOST'].'&attach='.$params['attach']));
		}
		break;
	case 'renew':
		if(empty($params['vpsname'])|| empty($params['year'])){
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=参数不足'));
		}
		$service = $DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$params['vpsname']}' AND `userid`='{$user['id']}'");
		if($service->num_rows != 1){
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=该服务不存在'));
		}else{
			$service = $service->fetch_assoc();
		}
		$service['password'] = base64_decode($service['password']);
		$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$service['product']}'");
		if($product->num_rows != 1){
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=服务产品不存在'));
		}else{
			$product = $product->fetch_assoc();
		}
		$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'");
		if($server->num_rows != 1){
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=服务器不存在，联系上级处理'));
		}else{
			$server = $server->fetch_assoc();
		}
		
		if($user['grade'] == "0" || $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->num_rows != 1){
		  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
		}else{
		  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->fetch_assoc();
		}
		$price = json_decode($grade['price'], true);
		$pdis = json_decode(url_decode($product['time']),true);
		foreach($pdis as $k => $v){
			if($v['discount'] == $params['year']){
				$dis = array(
					'name' => $v['name'],
					'discount' => $v['discount'],
					'day' => $v['day'],
					'remark' => $v['remark'],
					'renew' => $v['renew'],
				);
			}
		}
		if(empty($dis)){
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=周期设置错误，联系上级处理'));
		}
		if($dis['renew'] == 0){
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=该周期不允许续费'));
		}
		if(empty($service['promo_code'])){
			$price = $price[$product['id']] * $dis['discount'];
		}else{
			$price = $price[$product['id']] * $dis['discount'];
			$promo = $DB->query("SELECT * FROM `ytidc_promo` WHERE `code`='{$service['promo_code']}'");
			if($promo->num_rows == 1){
				$promo = $promo->fetch_assoc();
				if($promo['renew'] == 1){
					$price = $price - $promo['price'];
				}
			}
		}
		if(!check_price($price, true)){
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=价格设置错误，联系上级处理'));
		}
		
		if($user['site'] != 0){
			$usersite = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `id`='{$user['site']}'");
			if($usersite->num_rows != 1){
				$usersite = $site;
			}else{
				$usersite = $usersite->fetch_assoc();
				$usersiteowner = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$usersite['user']}'");
				if($usersiteowner->num_rows == 1){
					//分站奖励
					$usersiteowner = $usersiteowner->fetch_assoc();
					$usersiteownergrade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$usersiteowner['grade']}'");
					if($usersiteownergrade->num_rows != 1){
						$usersiteownergrade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'");
						if($usersiteownergrade->num_rows != 1){
							$usersiteownergrade = $DB->query("SELECT * FROM `ytidc_grade`")->fetch_assoc();
						}else{
							$usersiteownergrade = $usersiteownergrade->fetch_assoc();
						}
					}else{
						$usersiteownergrade = $usersiteownergrade->fetch_assoc();
					}
					if($usersiteownergrade['weight'] >= $user['grade']){
						$usersiteownerprice = json_decode($usersiteownergrade['price'] ,true);
						//总共奖励金额计算：用户价格组价格 - 分站价格组价格
						$usersiteownerprice = $usersiteownerprice[$product['id']] * $dis['discount'];
						$usersitemoneyprice = $price -  $usersiteownerprice;
						//必须要大于1块钱才会进行邀请返现，防止出现过小金额
						if($usersitemoneyprice >= 1){
							//若有邀请者，检查邀请者是否存在，分站后台设置邀请返现是否合理，防止刷余额情况
							if(!empty($user['invite']) && $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$user['invite']}'")->num_rows == 1 && $usersite['invitepercent'] < 100){
								//邀请奖励
								$invite = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$user['invite']}'")->fetch_assoc();
								//邀请者奖励金额计算：总共奖励金额 * 分站设置邀请返现比例
								$giftmoney = $usersitemoneyprice * $usersite['invitepercent'] / 100;
								//再次检查返现是否超出总计奖励金额
								if($usersitemoneyprice >= $giftmoney){
								//返现后所剩金额
									$usersitemoneyprice = $usersitemoneyprice - $giftmoney;
									$invitemoney = $invite['money'] + $giftmoney;
									$DB->query("UPDATE `ytidc_user` SET `money`='{$invitemoney}' WHERE `id`='{$invite['id']}'");
								  	$orderid = date('YmdHis').rand(1000, 99999);
								  	$DB->query("INSERT INTO `ytidc_order`(`orderid`, `description`, `money`, `action`, `user`, `status`) VALUES ('{$orderid}','邀请奖励返现','{$giftmoney}','加款','{$invite['id']}','已完成')");
								}
							}
						}
						//防止出现负数
						if($usersitemoneyprice >= 0){
							$usersiteownermoney = $usersiteowner['money'] + $usersitemoneyprice;
							$DB->query("UPDATE `ytidc_user` SET `money`='{$usersiteownermoney}' WHERE `id`='{$usersiteowner['id']}'");	
						  	$orderid = date('YmdHis').rand(1000, 99999);
						  	$DB->query("INSERT INTO `ytidc_order`(`orderid`, `description`, `money`, `action`, `user`, `status`) VALUES ('{$orderid}','下级用户续费服务返现','{$usersitemoneyprice}','加款','{$usersiteowner['id']}','已完成')");
						}
					}
				}
			}
		}else{
			if(!empty($user['invite']) && $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$user['invite']}'")->num_rows == 1 && $conf['invitepercent'] < 100){
				//邀请奖励
				$invite = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$user['invite']}'")->fetch_assoc();
				//邀请者奖励金额计算：(订单金额 - 最高等级价格) * 分站设置邀请返现比例
				$highgrade = $DB->query("SELECT * FROM `ytidc_grade` ORDER BY `weight` DESC")->fetch_assoc();
				$highgradeprice = json_decode($highgrade['price'], true);
				$highgradeprice =  $highgradeprice[$product['id']] * $dis['discount'];
				$inviteprice = $price - $highgradeprice;
				$giftmoney = $inviteprice * $conf['invitepercent'] / 100;
				//再次检查返现是否超出总计奖励金额
				if($inviteprice >= $giftmoney && $inviteprice >= 1){
					$invitemoney = $invite['money'] + $giftmoney;
					$DB->query("UPDATE `ytidc_user` SET `money`='{$invitemoney}' WHERE `id`='{$invite['id']}'");
					$orderid = date('YmdHis').rand(1000, 99999);
					$DB->query("INSERT INTO `ytidc_order`(`orderid`, `description`, `money`, `action`, `user`, `status`) VALUES ('{$orderid}','邀请奖励返现','{$giftmoney}','加款','{$invite['id']}','已完成')");
				}
			}
		}
		$new_money = $user['money'] - $price;
		if($new_money >= 0){
		  	$DB->query("UPDATE `ytidc_user` SET `money`='{$new_money}' WHERE `id`='{$user['id']}'");
		  	$orderid = date('YmdHis').rand(1000, 99999);
		  	$DB->query("INSERT INTO `ytidc_order`(`orderid`, `description`, `money`, `action`, `user`, `status`) VALUES ('{$orderid}','续费服务','{$price}','扣款','{$user['id']}','已完成')");
		}else{
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=用户余额不足'));
		}
		$plugin = ROOT."/plugins/server/".$server['plugin']."/main.php";
		if(!is_file($plugin) || !file_exists($plugin)){
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=服务器设置错误，联系上级处理'));
		}
		include($plugin);
		$postdata = array(
		  	'data' => array(
		      	'time' => $dis,
		      	'id' => $service['id'],
		    ),
		  	'service' => $service,
		  	'product' => $product,
		  	'server' => $server,
		);
		$function = $server['plugin']."_RenewService";
		$return = $function($postdata);
		if($return['status'] != "success"){
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo='.$return['msg']));
		}else{
		  	$DB->query("UPDATE `ytidc_service` SET `enddate`='{$return['enddate']}' WHERE `id`='{$service['id']}'");
			exit(iconv("UTF-8", "GB2312", 'ret=ok&vpsname='.$params['vpsname'].'&endtime='.$return['enddate'].'&cpurl='.$_SERVER['HTTP_HOST'].'&attach='.$params['attach']));
		}
		break;
	case 'getinfo':
		if(empty($params['vpsname'])){
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=服务不存在'));
		}
		$service = $DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$params['vpsname']}'");
		if($service->num_rows != 1){
			exit(iconv("UTF-8", "GB2312", 'ret=0&err=auth-failure&freehosinfo=服务不存在'));
		}else{
			$service = $service->fetch_assoc();
			$service['password'] = base64_decode($service['password']);
			exit(iconv("UTF-8", "GB2312", 'ret=ok&vpsname='.$service['username'].'&vpspassword='.$service['password'].'&ip=无记录IP&status=正常&endtime='.$service['enddate'].'&cpurl='.$_SERVER['HTTP_HOST'].'&attach='.$params['attach']));
		}
		break;
	
}
?>