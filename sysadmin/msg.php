<?php

include("../includes/common.php");

$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
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