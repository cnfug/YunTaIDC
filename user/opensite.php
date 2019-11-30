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
if(!empty($_POST['domain']) && !empty($_POST['name']) && !empty($_POST['admin']) && !empty($_POST['password'])){
  	$domain = daddslashes($_POST['domain']);
  	$name = daddslashes($_POST['name']);
  	$admin = daddslashes($_POST['admin']);
  	$password = daddslashes($_POST['password']);
  	$description = daddslashes($_POST['description']);
  	$new_money = $user['money'] - $conf['siteprice'];
  	if($new_money < 0){
      	@header("Location: ./msg.php?msg=余额不足");
      	exit;
    }
  	$DB->query("UPDATE `ytidc_user` SET `money`='{$new_money}' WHERE `username`='{$user['username']}'");
  	$DB->query("INSERT INTO `ytidc_fenzhan`(`domain`, `name`, `description`, `notice`, `admin`, `password`, `user`, `status`) VALUES ('{$domain}.{$conf['sitedomain']}','{$name}','{$description}','{$conf['sitenotice']}','{$admin}','{$password}','{$user['id']}','1')");
	@header("Location: ./msg.php?msg=开通成功！");
  	exit;
}
$title = "开通代理分站";
include("./head.php");
?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">开通代理分站</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="title">开通分站</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="./opensite.php">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站账号</label>
                                            <input name="admin" type="text" class="form-control" id="title" placeholder="分站账号">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站密码</label>
                                            <input name="password" type="text" class="form-control" id="title" placeholder="分站密码">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站名字</label>
                                            <input name="name" type="text" class="form-control" id="title" placeholder="分站名字">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站域名</label>
                                            <input name="domain" type="text" class="form-control" id="title" placeholder="域名前缀">.<?=$conf['sitedomain']?>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站介绍</label>
                                            <textarea class="form-control" name="description"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-default">开通</button>
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