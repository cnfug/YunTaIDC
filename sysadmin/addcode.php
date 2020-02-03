<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['code']) && !empty($_POST['price']) && !empty($_POST['product'])){
  	$code = daddslashes($_POST['code']);
  	$price = daddslashes($_POST['price']);
  	$product = daddslashes($_POST['product']);
  	if($DB->query("SELECT * FROM `ytidc_promo` WHERE `code`='{$code}'")->num_rows >= 1){
  		@header("Location: ./msg.php?msg=优惠码已存在！");
  		exit;
  	}
  	$DB->query("INSERT INTO `ytidc_promo`(`code`, `price`, `product`, `status`) VALUES ('{$code}','{$price}','{$product}','1')");
  	$newid = $DB->query("SELECT * FROM `ytidc_promo` WHERE `code`='{$code}'")->fetch_assoc();
  	$newid = $newid['id'];
  	@header("Location: ./editcode.php?id={$newid}");
  	exit;
}
$title = "添加优惠码";
include("./head.php");

?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">添加优惠码</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">添加优惠码</div>
        <div class="panel-body">
          <form role="form" action="./addcode.php" method="POST">
            <div class="form-group">
              <label>优惠码：</label>
              <input type="text" name="code" class="form-control" placeholder="优惠码">
            </div>
            <div class="form-group">
              <label>抵扣金额</label>
              <input type="text" name="price" class="form-control" placeholder="抵扣金额">
            </div>
            <div class="form-group">
              <label>优惠产品ID【多个使用,分开】</label>
              <input type="text" name="product" class="form-control" placeholder="优惠产品ID">
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