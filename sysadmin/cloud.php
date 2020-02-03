<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_config` SET `v`='{$value}' WHERE `k`='{$k}'");
    }
  	@header("Location: ./cloud.php");
  	exit;
}
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$id}'")->fetch_assoc();
$templates = get_dir(ROOT."/templates/");
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">云中心控制台</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">云中心开关</div>
        <div class="panel-body">
          <form role="form" action="./cloud.php?act=edit" method="POST">
            <div class="form-group">
              <label>接收云中心信息</label>
              <select name="cloud_get_news" class="form-control">
              	<option value="1">开启</option>
              	<option value="0">关闭</option>
              </select>
            </div>
            <div class="form-group">
              <label>开启云中心支付地址验证</label>
              <select name="cloud_pay_vertify" class="form-control">
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