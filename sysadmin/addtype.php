<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['name'])){
  	$name = daddslashes($_POST['name']);
  	$weight = daddslashes($_POST['weight']);
  	$hidden = daddslashes($_POST['hidden']);
  	$DB->query("INSERT INTO `ytidc_type` (`name`, `weight` ,`status`) VALUES ('{$name}', '{$weight}', '{$hidden}')");
  	@header("Location: ./type.php");
  	exit;
}
$title = "添加产品组";
include("./head.php");

?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">添加分类</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">添加分类</div>
        <div class="panel-body">
          <form role="form" action="./addtype.php" method="POST">
            <div class="form-group">
              <label>分类名称：</label>
              <input type="text" name="name" class="form-control" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label>分类权重（越大越前）</label>
              <input type="number" name="weight" class="form-control" placeholder="分类权重">
            </div>
            <div class="form-group">
              <label>隐藏分类</label>
              <select class="form-control" name="status">
            	<option value="1">否</option>
            	<option value="0">是</option>
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