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
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./notice.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_notice` WHERE `id`='{$id}' AND `site`='{$site['id']}'");
  	@header("Location: ./notice.php");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$$k = daddslashes($v);
    }
  	$date = date('Y-m-d');
  	$DB->query("UPDATE `ytidc_notice` SET `title`='{$title}', `content`='{$content}', `date`='{$date}' WHERE `id`='{$id}' AND `site`='{$site['id']}'");
  	@header("Location: ./editnotice.php?id={$id}");
  	exit;
}
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_notice` WHERE `id`='{$id}' AND `site`='{$site['id']}'");
if($row->num_rows != 1){
	@header("Location: ./notice.php");
  	exit;
}else{
	$row = $row->fetch_assoc();
}
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">编辑公告</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">编辑公告</div>
        <div class="panel-body">
          <form role="form" action="./editnotice.php?act=edit&id=<?=$id?>" method="POST">
            <div class="form-group">
              <label>公告标题：</label>
              <input type="text" name="title" class="form-control" placeholder="公告标题" value="<?=$row['title']?>">
            </div>
            <div class="form-group">
              <label>公告内容</label>
              <textarea name="content" class="form-control"><?=$row['content']?></textarea>
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