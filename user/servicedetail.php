<?php

include("../includes/common.php");
if(empty($_SESSION['ytidc_user']) && empty($_SESSION['ytidc_pass'])){
  	@header("Location: ./login.php");
     exit;
}else{
  	$username = daddslashes($_SESSION['ytidc_user']);
  	$password = daddslashes($_SESSION['ytidc_pass']);
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}' AND `password`='{$password}'");
  	if($user->num_rows != 1){
      	@header("Location: ./login.php");
      	exit;
    }else{
      	$user = $user->fetch_assoc();
    }
}
$id = daddslashes($_GET['id']);
if(empty($id) || $DB->query("SELECT * FROM `ytidc_service` WHERE `id`='{$id}' AND `userid`='{$user['id']}'")->num_rows != 1){
  	@header("Location: ./service.php");
  	exit;
}
$row = $DB->query("SELECT * FROM `ytidc_service` WHERE `id`='{$id}' AND `userid`='{$user['id']}'")->fetch_assoc();
$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$row['id']}'")->fetch_assoc();
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'")->fetch_assoc();

$title = "服务管理";
include("./head.php");
?>
            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title"><?=$product['name']?></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-body">
                                  登陆面板：<a href="<?=$server['servercpanel']?>" class="btn btn-success">前往登陆</a><br>
                                  服务账号：<?=$row['username']?><br>
                                  服务密码：<?=$row['password']?><br>
                                  到期时间：<?=$row['enddate']?><br>
                                  产品名称：<?=$product['name']?><br>
                                  <form action="renewservice.php" method="POST">
                                    	<input type="hidden" name="id" value="<?=$row['id']?>">
                                    	续费：<input type="number" name="time" placeholder="续费月数" value="1" minvalue="1">月     <button type="submit" class="btn btn-danger">续费</button>
                                  </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
  
  include("./foot.php");
                          
                          ?>