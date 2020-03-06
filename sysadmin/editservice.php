<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./service.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_service` WHERE `id`='{$id}'");
  	@header("Location: ./service.php");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	if($k == 'password'){
      		$v = base64_encode($v);
      	}
      	$DB->query("UPDATE `ytidc_service` SET `{$k}`='{$value}' WHERE `id`='{$id}'");
    }
  	@header("Location: ./editservice.php?id={$id}");
  	exit;
}
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_service` WHERE `id`='{$id}'")->fetch_assoc();
$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$row['product']}'")->fetch_assoc();
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">编辑在线服务</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">编辑服务</div>
        <div class="panel-body">
          <form role="form" action="./editservice.php?act=edit&id=<?=$id?>" method="POST">
            <div class="form-group">
              <label>购买服务产品名称</label>
              <input type="text" class="form-control" value="<?=$product['name']?>" disabled>
            </div>
            <div class="form-group">
              <label>服务账号</label>
              <input type="text" name="username" class="form-control" placeholder="服务账号" value="<?=$row['username']?>">
            </div>
            <div class="form-group">
              <label>服务密码</label>
              <input type="text" name="password" class="form-control" placeholder="服务密码" value="<?php echo base64_decode($row['password']); ?>">
            </div>
            <div class="form-group">
              <label>服务到期时间</label>
              <input type="date" name="enddate" class="form-control" placeholder="服务到期时间" value="<?=$row['enddate']?>">
            </div>
            <div class="form-group">
              <label>服务状态</label>
              <select class="form-control" name="status">
              	<?php
              	if($row['status'] == '等待审核'){
              		echo '<option value="激活">激活</option><option value="等待审核" selected>等待审核</option>';
              	}else{
              		echo '<option value="激活" selected>激活</option><option value="等待审核">等待审核</option>';
              	}
              	?>
              </select>
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