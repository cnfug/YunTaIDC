<?php

include("../includes/common.php");

$domain = $_SERVER['HTTP_HOST'];
$row = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `domain`='{$domain}'")->fetch_assoc();
$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$row['user']}'")->fetch_assoc();
if(empty($_SESSION['fzadmin']) || empty($_SESSION['fzkey'])){
  	@header("Location: ./login.php");
  	exit;
}
$fzadmin = daddslashes($_SESSION['fzadmin']);
$fzkey = daddslashes($_SESSION['fzkey']);
if($fzadmin != $row['admin'] && $fzkey != md5($_SERVER['HTTP_HOST'].$row['password']."fz")){
  	@header("Location: ./login.php");
  	exit;
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