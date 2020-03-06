<?php

include("../includes/common.php");
if(empty($_SESSION['ytidc_user']) || empty($_SESSION['ytidc_token'])){
  	@header("Location: ./login.php");
     exit;
}else{
  	$username = daddslashes($_SESSION['ytidc_user']);
  	$userkey = daddslashes($_SESSION['ytidc_token']);
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}'");
  	if($user->num_rows != 1){
      	@header("Location: ./login.php");
      	exit;
    }else{
    	$user = $user->fetch_assoc();
    	$site = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `user`='{$user['id']}'");
    	if($site->num_rows != 1){
    		exit('该用户尚未开通分站！<a href="./login.php">点此重新登陆</a>');
    	}else{
    		$site = $site->fetch_assoc();
    	}
      	$userkey1 = md5($_SERVER['HTTP_HOST'].$user['password']);
      	if($userkey != $userkey1){
      		@header("Location: ./login.php");
      		exit;
      	}
    }
}
$act = daddslashes($_GET['act']);
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_fenzhan` SET `{$k}`='{$value}' WHERE `id`='{$site['id']}'");
    }
  	@header("Location: ./config.php");
  	exit;
}
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `id`='{$id}'")->fetch_assoc();
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">站点资料</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">编辑资料</div>
        <div class="panel-body">
          <form role="form" action="./config.php?act=edit" method="POST">
            <div class="form-group">
              <label>站点标题</label>
              <input type="text" name="title" class="form-control" placeholder="站点标题" value="<?=$site['title']?>">
            </div>
            <div class="form-group">
              <label>站点副标题</label>
              <input type="text" name="subtitle" class="form-control" placeholder="站点副标题" value="<?=$site['subtitle']?>">
            </div>
            <div class="form-group">
              <label>站点SEO介绍</label>
              <input type="text" name="description" class="form-control" placeholder="站点SEO介绍" value="<?=$site['description']?>">
            </div>
            <div class="form-group">
              <label>站点SEO关键词</label>
              <input type="text" name="keywords" class="form-control" placeholder="站点SEO关键词" value="<?=$site['keywords']?>">
            </div>
            <div class="form-group">
              <label>邀请用户奖励百分比（从你的提成中扣除，不要超过100）</label>
              <input name="invitepercent" type="number" class="form-control" id="serverusername" placeholder="邀请用户奖励百分比" value="<?=$site['invitepercent']?>" max="100">
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