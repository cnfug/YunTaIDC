<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
	@header("Location: ./code.php");
	exit;
}
$promo = $DB->query("SELECT * FROM `ytidc_promo` WHERE `id`='{$id}'");
if($promo->num_rows != 1){
	@header("Location: ./code.php");
	exit();
}else{
	$row = $promo->fetch_assoc();
}
$act = daddslashes($_GET['act']);
if($act == "edit"){
	if(!empty($_POST['code']) && !empty($_POST['price']) && !empty($_POST['product'])){
	  	$code = daddslashes($_POST['code']);
	  	$price = daddslashes($_POST['price']);
	  	$product = daddslashes($_POST['product']);
	  	$DB->query("UPDATE `ytidc_promo` SET `code`='{$code}', `price`='{$price}', `product`='{$product}' WHERE `id`='{$id}'");
	  	@header("Location: ./msg.php?msg=添加价格组成功！");
	  	exit;
	}else{
	  	@header("Location: ./msg.php?msg=参数不能为空！");
	  	exit;
	}
}
if($act == "del"){
	$DB->query("DELETE FROM `ytidc_promo` WHERE `id`='{$id}'");
	@header("Location: ./msg.php?msg=删除成功！");
	exit;
}

include("./head.php");

?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">编辑优惠码</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">编辑优惠码</div>
        <div class="panel-body">
          <form role="form" action="./editcode.php?act=edit&id=<?=$id?>" method="POST">
            <div class="form-group">
              <label>优惠码：</label>
              <input type="text" name="code" class="form-control" placeholder="优惠码" value="<?=$row['code']?>">
            </div>
            <div class="form-group">
              <label>抵扣金额</label>
              <input type="text" name="price" class="form-control" placeholder="抵扣金额" value="<?=$row['price']?>">
            </div>
            <div class="form-group">
              <label>优惠产品ID【多个使用,分开】</label>
              <input type="text" name="product" class="form-control" placeholder="优惠产品ID" value="<?=$row['product']?>">
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