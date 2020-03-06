<?php

include("../../includes/common.php");

header("Content-type: text/json");
foreach($_GET as $k => $v){
	$params[$k] = daddslashes($v);
}
if(empty($params['ytidc_user']) && empty($params['ytidc_pass'])){
  	if($user->num_rows != 1){
      	$retdata = array(
      	'ret' => 'fail',
      	'msg' => '账号密码为空',
    );
  	 exit(json_encode($retdata));
    }else{
      	$user = $user->fetch_assoc();
    }
}else{
  	$ytuser = daddslashes($params['ytidc_user']);
  	$ytpass = base64_encode(daddslashes($params['ytidc_pass']));
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$ytuser}' AND `password`='{$ytpass}'");
  	if($user->num_rows != 1){
      	$retdata = array(
      	'ret' => 'fail',
      	'msg' => '账号密码错误',
    );
  	 exit(json_encode($retdata));
    }else{
      	$user = $user->fetch_assoc();
    }
}
if(empty($params['username'])|| empty($params['time'])){
	$retdata = array(
		'status' => 'fail',
		'msg' => '参数不足！',
	);
	exit(json_encode($retdata));
}
$service = $DB->query("SELECT * FROM `ytidc_service` WHERE `username`='{$params['username']}' AND `userid`='{$user['id']}'");
if($service->num_rows != 1){
	$retdata = array(
		'status' => 'fail',
		'msg' => '该服务不存在！',
	);
	exit(json_encode($retdata));
}else{
	$service = $service->fetch_assoc();
}
$service['password'] = base64_decode($service['password']);
$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$service['product']}'");
if($product->num_rows != 1){
	$retdata = array(
		'status' => 'fail',
		'msg' => '产品不存在！',
	);
	exit(json_encode($retdata));
}else{
	$product = $product->fetch_assoc();
}
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'");
if($server->num_rows != 1){
	$retdata = array(
		'status' => 'fail',
		'msg' => '服务器不存在，联系上级处理！',
	);
	exit(json_encode($retdata));
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
			'renew' => $v['renew'],
		);
	}
}
if(empty($dis)){
	$retdata = array(
		'status' => 'fail',
		'msg' => '周期设置错误！',
	);
	exit(json_encode($retdata));
}
if($dis['renew'] == 0){
	$retdata = array(
		'status' => 'fail',
		'msg' => '该周期不允许被续费！',
	);
	exit(json_encode($retdata));
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
	$retdata = array(
		'status' => 'fail',
		'msg' => '价格设置错误，联系上级处理！',
	);
	exit(json_encode($retdata));
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
	$retdata = array(
		'status' => 'fail',
		'msg' => '用户余额不足！',
	);
	exit(json_encode($retdata));
}
$plugin = ROOT."/plugins/server/".$server['plugin']."/main.php";
if(!is_file($plugin) || !file_exists($plugin)){
	$retdata = array(
		'status' => 'fail',
		'msg' => '服务器所属插件不存在！',
	);
	exit(json_encode($retdata));
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
	$retdata = array(
		'status' => 'fail',
		'msg' => $return['msg'],
	);
	exit(json_encode($retdata));
}else{
  	$DB->query("UPDATE `ytidc_service` SET `enddate`='{$return['enddate']}' WHERE `id`='{$service['id']}'");
	$retdata = array(
		'status' => 'success',
		'msg' => '续费成功！',
		'enddate' => $return['enddate'],
	);
	exit(json_encode($retdata));
}
?>