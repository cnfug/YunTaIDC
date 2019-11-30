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
$msg = daddslashes($_GET['msg']);
$title = "提示信息";
include("./head.php");
?>
            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">提示信息</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-body">
                                  <h2>提示信息：<?=$msg?></h2><br>
                                  <a href="./index.php" class="btn btn-primary">返回首页</a>
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