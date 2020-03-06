<?php

include("../includes/common.php");if(empty($_SESSION['ytidc_user']) || empty($_SESSION['ytidc_token'])){
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
if(!empty($_POST['title']) && !empty($_POST['content'])){
  	$title = daddslashes($_POST['title']);
  	$content = daddslashes($_POST['content']);
  	$date = date('Y-m-d');
  	$DB->query("INSERT INTO `ytidc_notice` (`title`, `content`, `date`, `site`, `status`) VALUES ('{$title}', '{$content}', '{$date}', '{$site['id']}', '1')");
  	@header("Location: ./notice.php");
  	exit;
}
$title = "添加公告";
include("./head.php");

?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">添加公告</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">添加公告</div>
        <div class="panel-body">
          <form role="form" action="./addnotice.php" method="POST">
            <div class="form-group">
              <label>公告标题：</label>
              <input type="text" name="title" class="form-control" placeholder="公告标题">
            </div>
            <div class="form-group">
              <label>公告内容</label>
              <textarea name="content" class="form-control"></textarea>
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