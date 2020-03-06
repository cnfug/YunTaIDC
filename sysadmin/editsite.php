<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./site.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_fenzhan` WHERE `id`='{$id}'");
  	@header("Location: ./site.php");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_fenzhan` SET `{$k}`='{$value}' WHERE `id`='{$id}'");
    }
  	@header("Location: ./editsite.php?id={$id}");
  	exit;
}
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `id`='{$id}'")->fetch_assoc();
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">编辑分站</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">编辑站点</div>
        <div class="panel-body">
          <form role="form" action="./editsite.php?act=edit&id=<?=$id?>" method="POST">
            <div class="form-group">
              <label>分站名称</label>
              <input type="text" name="title" class="form-control" placeholder="分站名称" value="<?=$row['title']?>">
            </div>
            <div class="form-group">
              <label>分站副标题</label>
              <input type="text" name="subtitle" class="form-control" placeholder="分站副标题" value="<?=$row['subtitle']?>">
            </div>
            <div class="form-group">
              <label>分站域名</label>
              <input type="text" name="domain" class="form-control" placeholder="分站域名" value="<?=$row['domain']?>">
            </div>
            <div class="form-group">
              <label>分站后台公告</label>
              <textarea class="form-control" name="notice"><?=$row['notice']?></textarea>
            </div>
            <div class="form-group">
              <label>分站介绍</label>
              <input type="text" name="description" class="form-control" placeholder="分站介绍" value="<?=$row['description']?>">
            </div>
            <div class="form-group">
              <label>分站SEO关键词</label>
              <input type="text" name="keywords" class="form-control" placeholder="分站SEO关键词" value="<?=$row['keywords']?>">
            </div>
            <div class="form-group">
              <label>分站邀请返现百分比</label>
              <input type="text" name="invitepercent" class="form-control" placeholder="分站邀请返现百分比" value="<?=$row['invitepercent']?>">
            </div>
            <div class="form-group">
              <label>所属用户ID</label>
              <input type="number" name="user" class="form-control" placeholder="所属用户ID" value="<?=$row['user']?>">
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