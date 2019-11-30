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
$title = "产品订购确认";
$id = daddslashes($_GET['id']);
$row = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$id}'")->fetch_assoc();
if($user['grade'] == "0" || $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->num_rows != 1){
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
}else{
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$user['grade']}'")->fetch_assoc();
}
$price = json_decode($grade['price'], true);
include("./head.php");
?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">产品订购确认</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="title">开通服务</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="./openserver.php">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务账号</label>
                                            <input name="username" type="text" class="form-control" id="title" placeholder="服务账号">
                                            <input name="product" type="hidden" class="form-control" id="title" value="<?=$id?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务密码</label>
                                            <input name="password" type="text" class="form-control" id="title" placeholder="服务密码">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">购买时长(1为<?=$row['time']?>天/<?=$price[$id]?>元)</label>
                                            <input name="time" type="number" class="form-control" id="title" placeholder="购买时长">
                                        </div>
                                        <button type="submit" class="btn btn-default">确认</button>
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