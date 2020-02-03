<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['displayname']) && !empty($_POST['gateway']) && !empty($_POST['fee']) && !empty($_POST['status'])){
  	$displayname = daddslashes($_POST['displayname']);
  	$gateway = daddslashes($_POST['gateway']);
  	$fee = daddslashes($_POST['fee']);
  	$status = daddslashes($_POST['status']);
  	$DB->query("INSERT INTO `ytidc_payplugin`(`displayname`, `gateway`, `fee`, `status`) VALUES ('{$displayname}','{$gateway}','{$fee}','{$status}')");
  	$newid = $DB->query("select MAX(id) from `ytidc_payplugin`")->fetch_assoc();
  	$newid = $newid['MAX(id)'];
	@header("Location: ./editpay.php?id={$newid}");
  	exit();
}
$title = "添加产品";
include("./head.php");
$plugins = get_dir(ROOT."/plugins/payment/");
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">添加产品</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">添加产品</div>
        <div class="panel-body">
          <form role="form" action="./addpay.php" method="POST">
            <div class="form-group">
              <label>显示名称</label>
              <input type="text" name="displayname" class="form-control" placeholder="显示名称">
            </div>
            <div class="form-group">
              <label>接口插件</label>
              <select name="gateway" class="form-control m-b">
              	<?php
              	foreach($plugins as $k => $v){
              		echo '<option value="'.$v.'">'.$k.'</option>';
              	}
              	?>
              </select>
            </div>
            <div class="form-group">
              <label>到账费率（95%就填95）</label>
              <input type="text" name="fee" class="form-control" placeholder="到账费率">
            </div>
            <div class="form-group">
              <label>接口状态</label>
              <select name="status" class="form-control m-b">
              	<option value="1">开启</option>
              	<option value="0">关闭</option>
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