<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./type.php");
  	exit;
}
$row = $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$id}'")->fetch_assoc();
$act = daddslashes($_GET['act']);
if($act == "edit"){
	$default_grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
  	$discount = daddslashes($_POST['discount']);
  	$default_price = json_decode($default_grade['price'], true);
  	foreach($default_price as $k => $v){
  		$new_price[$k] = $v * $discount / 100;
  	}
  	$new_price = json_encode($new_price);
  	$DB->query("UPDATE `ytidc_grade` SET `price`='{$new_price}' WHERE `id`='{$id}'");
  	if($DB->error){
      	$error_log = file_get_contents(ROOT."logs/system_error.log");
      	$error_log = $error_log .  $return['status'] .":" . $return['msg'] . "\r\n";
      	file_put_contents(ROOT."/logs/system_error.log", $error_log);
  	}
  	@header("Location: ./editprice.php?id={$id}");
  	exit;
}
include("./head.php");
$product = $DB->query("SELECT * FROM `ytidc_product`");
$price = json_decode($row['price'], true);
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">价格批量修改</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">批量设置</div>
        <div class="panel-body">
          <form role="form" action="./setprice.php?act=edit&id=<?=$id?>" method="POST">
            <div class="form-group">
              <label>价格组名称：</label>
              <input type="text" name="name" class="form-control" placeholder="价格组名称" value="<?=$row['name']?>" disabled="">
            </div>
            <div class="form-group">
              <label>批量折扣（百分比，1%填1。使用默认价格组做成本价）</label>
              <input type="number" name="discount" class="form-control" placeholder="批量折扣" >
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