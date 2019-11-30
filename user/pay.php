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
$title = "用户余额充值";
include("./head.php");
?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">充值余额</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="title">充值余额</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="../epay/submit.php">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">充值金额</label>
                                            <input name="money" type="text" class="form-control" id="title" placeholder="充值金额">
                                            <input name="user" type="hidden" class="form-control" id="title" value="<?=$user['id']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">支付渠道</label><br>
                                          <div class="radio3 radio-check radio-inline">
                                            <input type="radio" id="radio1" name="type" value="alipay">
                                            <label for="radio1">
                                              支付宝
                                            </label>
                                          </div>
                                          <div class="radio3 radio-check radio-inline">
                                            <input type="radio" id="radio2" name="type" value="wxpay">
                                            <label for="radio2">
                                              微信钱包
                                            </label>
                                          </div>
                                          <div class="radio3 radio-check radio-inline">
                                            <input type="radio" id="radio3" name="type" value="qqpay">
                                            <label for="radio3">
                                              QQ钱包/财付通
                                            </label>
                                          </div>
                                        </div>
                                        <button type="submit" class="btn btn-default">充值</button>
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