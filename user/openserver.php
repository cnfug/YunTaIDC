<?php

include("../includes/common.php");
set_time_limit(3600);
if(empty($_SESSION['ytidc_user']) || empty($_SESSION['ytidc_token'])){
  	@header("Location: ./login.php");
     exit;
}else{
  	$username = daddslashes($_SESSION['ytidc_user']);
  	$userkey = daddslashes($_SESSION['ytidc_token']);
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}'");
  	if($user->num_rows != 1){
      	@header("Location: ./login.php");
      	exit;
    }else{
    	$user = $user->fetch_assoc();
      	$userkey1 = md5($_SERVER['HTTP_HOST'].$user['password']);
      	if($userkey != $userkey1){
      		@header("Location: ./login.php");
      		exit;
      	}
    }
}

foreach($_POST as $k => $v){
  	$params[$k] = daddslashes($v);
}
if(empty($params['username']) || empty($params['password']) || empty($params['product']) || empty($params['time'])){
  	@header("Location: ./msg.php?msg=参数不足够，请勿为空！");
  	exit;
}
if($DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$params['username']}'")->num_rows != 0){
  	@header("Location: ./msg.php?msg=服务器用户名已被占用");
  	exit;
}
$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$params['product']}'");
if($product->num_rows != 1){
	@header("Location: ./msg.php?msg=产品不存在");
	exit;
}else{
	$product = $product->fetch_assoc();
}
if($product['status'] != 1){
	@header("Location: ./msg.php?msg=该产品暂时不允许开通");
	exit;
}
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'");
if($server->num_rows != 1){
	@header("Location: ./msg.php?msg=服务器不存在");
	exit;
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
	if($v['name'] == $params['time']){
		$dis = array(
			'name' => $v['name'],
			'discount' => $v['discount'],
			'day' => $v['day'],
			'remark' => $v['remark'],
		);
	}
}
if(empty($dis)){
	@header("Location: ./msg.php?msg=服务器周期设置错误");
  	exit;
}
if(empty($params['promo_code'])){
	$price = $price[$product['id']] * $dis['discount'];
}else{
	$price = $price[$product['id']] * $dis['discount'];
	$promo = $DB->query("SELECT * FROM `ytidc_promo` WHERE `code`='{$params['promo_code']}'");
	if($promo->num_rows != 1){
	  	@header("Location: ./msg.php?msg=优惠码不存在！");
	  	exit;
	}else{
		$promo = $promo->fetch_assoc();
		if($promo['product'] != $product['id']){
			@header("Location: ./msg.php?msg=优惠码不能开通本产品！");
	  		exit;
		}
		if($promo['status'] != 1){
			@header("Location: ./msg.php?msg=优惠码已过期或者被下架");
			exit;
		}
		if($promo['diali'] != 1 && $grade['default'] != 1){
			@header("Location: ./msg.php?msg=该优惠码仅支持默认价格组用户使用！");
			exit;
		}
		$price = $price - $promo['price'];
	}
}
if(!check_price($price, true)){
  	@header("Location: ./msg.php?msg=价格设置错误，请联系上级进行管理！");
  	exit;
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
  	@header("Location: ./msg.php?msg=用户余额不足");
  	exit;
}

$date = date('Y-m-d',strtotime("+{$dis[day]} days", time()));
$service_password = base64_encode($params['password']);
$buydate = date("Y-m-d");
$DB->query("INSERT INTO `ytidc_service` (`userid`, `username`, `password`, `buydate`, `enddate`, `product`, `promo_code`, `configoption`, `status`) VALUES ('{$user['id']}', '{$params['username']}', '{$service_password}', '{$buydate}', '{$date}', '{$product['id']}', '{$params['promo_code']}', '' ,'等待审核')");
$serviceid = $DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$params['username']}' AND `password`='{$service_password}'")->fetch_assoc();
$serviceid = $serviceid['id'];
$plugin = "../plugins/server/".$server['plugin']."/main.php";
if(!is_file($plugin) || !file_exists($plugin)){
  	@header("Location: ./msg.php?msg=服务器插件不存在");
  	exit;
}
include($plugin);
$postdata = array(
  	'service' => array(
      	'username' => $params['username'],
       	'password' => $params['password'],
      	'time' => $dis,
    ),
  	'product' => $product,
  	'server' => $server,
);
$function = $server['plugin']."_CreateService";
$return = $function($postdata);

if($return['status'] != "success"){
  	@header("Location: ./msg.php?msg={$return['msg']}");
  	exit;
}else{
	$new_password = base64_encode($return['password']);
	$DB->query("UPDATE `ytidc_service` SET `username`='{$return['username']}',`password`='{$new_password}',`enddate`='{$return['enddate']}',`configoption`='{$return['configoption']}',`status`='激活' WHERE `id`='{$serviceid}'");
	$dberror = $DB->error;
  	@header("Location: ./msg.php?msg=开通成功");
  	exit;
}

?>