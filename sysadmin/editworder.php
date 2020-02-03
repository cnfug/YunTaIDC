<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./worder.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_worder` WHERE `id`='{$id}'");
  	@header("Location: ./worder.php");
  	exit;
}
if($act == "edit"){
  	$reply = daddslashes($_POST['reply']);
  	$DB->query("UPDATE `ytidc_worder` SET `reply`='{$reply}', `status`='已回复' WHERE `id`='{$id}'");
  	@header("Location: ./worder.php");
  	exit;
}
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_worder` WHERE `id`='{$id}'")->fetch_assoc();
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">回复工单</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">工单内容</div>
        <div class="panel-body">
          <form role="form" action="./editworder.php?act=edit&id=<?=$id?>" method="POST">
            <div class="form-group">
              <label>工单题目</label>
              <input type="text" class="form-control" placeholder="工单题目" value="<?=$row['title']?>" disabled="">
            </div>
            <div class="form-group">
              <label>服务密码</label>
              <textarea class="form-control" placeholder="工单内容" disabled><?=$row['content']?></textarea>
            </div>
            <div class="form-group">
              <label>回复工单</label>
              <textarea class="form-control" placeholder="回复内容" name="reply"><?=$row['reply']?></textarea>
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