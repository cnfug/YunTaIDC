<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['name']) && !empty($_POST['weight'])){
  	$name = daddslashes($_POST['name']);
  	$weight = daddslashes($_POST['weight']);
  	$need_money = daddslashes($_POST['need_money']);
  	$need_save = daddslashes($_POST['need_save']);
  	$need_paid = daddslashes($_POST['need_paid']);
  	$date = date('Y-m-d');
  	if($DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->num_rows <= 0){
  		$default = 1;
  	}else{
  		$default = 0;
  	}
  	$DB->query("INSERT INTO `ytidc_grade`(`name`, `weight`, `need_paid`, `need_money`, `need_save`, `default`, `price`, `status`) VALUES ('{$name}','{$weight}','{$need_paid}','{$need_money}','{$need_save}','{$default}','','1')");
  	@header("Location: ./price.php");
  	exit;
}
$title = "添加价格组";
include("./head.php");

?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">添加价格组</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">添加价格组</div>
        <div class="panel-body">
          <form role="form" action="./addprice.php" method="POST">
            <div class="form-group">
              <label>价格组名称：</label>
              <input type="text" name="name" class="form-control" placeholder="价格组名称">
            </div>
            <div class="form-group">
              <label>价格组等级（越大越高）</label>
              <input type="number" name="weight" class="form-control" placeholder="价格组等级">
            </div>
            <div class="form-group">
              <label>开通消费要求（优先使用，0为不启用）</label>
              <input type="number" name="need_paid" class="form-control" placeholder="开通消费要求">
            </div>
            <div class="form-group">
              <label>开通预存要求（第二使用，0为不启用）</label>
              <input type="number" name="need_save" class="form-control" placeholder="开通预存要求">
            </div>
            <div class="form-group">
              <label>开通价格（最后使用，0为不启用）</label>
              <input type="number" name="need_money" class="form-control" placeholder="开通价格">
            </div>
            <button type="submit" class="btn btn-sm btn-primary">提交</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php

include("./foot.php");

?>