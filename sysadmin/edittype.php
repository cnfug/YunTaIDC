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
$act = daddslashes($_GET['act']);
if($act == "del"){
	if($DB->query("SELECT * FROM `ytidc_product` WHERE `type`='{$id}'")->num_rows >= 1){
		@header("Location: ./msg.php?msg=改产品组尚有产品使用，暂时无法删除。");
		exit;
	}else{
	  	$DB->query("DELETE FROM `ytidc_type` WHERE `id`='{$id}'");
	  	@header("Location: ./type.php");
	  	exit;
	}
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_type` SET `{$k}`='{$value}' WHERE `id`='{$id}'");
    }
  	@header("Location: ./edittype.php?id={$id}");
  	exit;
}
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_type` WHERE `id`='{$id}'")->fetch_assoc();
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">编辑分类</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">编辑分类</div>
        <div class="panel-body">
          <form role="form" action="./edittype.php?act=edit&id=<?=$id?>" method="POST">
            <div class="form-group">
              <label>分类名称：</label>
              <input type="text" name="name" class="form-control" placeholder="分类名称" value="<?=$row['name']?>">
            </div>
            <div class="form-group">
              <label>分类权重（越大越前）</label>
              <input type="number" name="weight" class="form-control" placeholder="分类权重" value="<?=$row['weight']?>">
            </div>
            <div class="form-group">
              <label>隐藏分类</label>
              <select class="form-control" name="status">
              <?php
              if($row['hidden'] == "0"){
            	echo '
            	<option value="1">否</option>
            	<option value="0" selected="">是</option>';
              }else{
            	echo '
            	<option value="1" selected="">否</option>
            	<option value="0">是</option>';
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