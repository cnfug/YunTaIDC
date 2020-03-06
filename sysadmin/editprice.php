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
if($act == "del"){
	if($DB->query("SELECT * FROM `ytidc_user` WHERE `grade`='{$id}'")->num_rows >= 1){
		@header("location: ./msg.php?msg=该价格组尚有用户使用，暂时无法删除。");
		exit;
	}else{
	  	$DB->query("DELETE FROM `ytidc_grade` WHERE `id`='{$id}'");
	  	@header("Location: ./price.php");
	  	exit;
	}
}
if($act == "edit"){
  	$price = json_encode($_POST['price']);
  	$name = daddslashes($_POST['name']);
    $default = daddslashes($_POST['default']);
    $need_paid = daddslashes($_POST['need_paid']);
    $need_save = daddslashes($_POST['need_save']);
    $need_money = daddslashes($_POST['need_money']);
    if($default == '1'){
        $DB->query("UPDATE `ytidc_grade` SET `default`='0' WHERE `default`='1'");
        $DB->query("UPDATE `ytidc_grade` SET `default`='1' WHERE `id`='{$id}'");
    }
  	$DB->query("UPDATE `ytidc_grade` SET `name`='{$name}', `price`='{$price}', `need_save`='{$need_save}', `need_money`='{$need_money}', `need_paid`='{$need_paid}' WHERE `id`='{$id}'");
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
  <h1 class="m-n font-thin h3">编辑价格组</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">编辑价格组</div>
        <div class="panel-body">
          <form role="form" action="./editprice.php?act=edit&id=<?=$id?>" method="POST">
            <div class="form-group">
              <label>价格组名称：</label>
              <input type="text" name="name" class="form-control" placeholder="价格组名称" value="<?=$row['name']?>">
            </div>
            <div class="form-group">
              <label>是否设置为默认</label>
              <select name="default" class="form-control">
              	<?php if($row['default'] == '1'){
              		echo '<option value="1" selected>是</option><option value="0">否</option>';
              	}else{
              		echo '<option value="1">是</option><option value="0" selected>否</option>';
              	}
              	?>
              </select>
            </div>
            <div class="form-group">
              <label>价格组等级（越大越高）</label>
              <input type="number" name="weight" class="form-control" placeholder="价格组等级" value="<?=$row['weight']?>">
            </div>
            <div class="form-group">
              <label>开通消费要求（优先使用，0为不启用）</label>
              <input type="number" name="need_paid" class="form-control" placeholder="开通消费要求" value="<?=$row['need_paid']?>">
            </div>
            <div class="form-group">
              <label>开通预存要求（第二使用，0为不启用）</label>
              <input type="number" name="need_save" class="form-control" placeholder="开通预存要求" value="<?=$row['need_save']?>">
            </div>
            <div class="form-group">
              <label>开通价格（最后使用，0为不启用）</label>
              <input type="number" name="need_money" class="form-control" placeholder="开通价格" value="<?=$row['need_money']?>">
            </div>
            <?php
                while($row2 = $product->fetch_assoc()){
                    echo '<div class="form-group">
                          <label>产品【'.$row2['name'].'】的价格</label>
                          <input name="price['.$row2['id'].']" type="text" class="form-control" id="price" placeholder="产品【'.$row2['name'].'】的价格" value="'.$price[$row2['id']].'"  oninput="value=value.replace(/[^\d.]/g,\'\')">
                          </div>';
                }?>
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