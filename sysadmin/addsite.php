<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['title']) && !empty($_POST['domain']) && !empty($_POST['description']) && !empty($_POST['notice']) && !empty($_POST['user'])){
  	foreach($_POST as $k => $v){
      	$$k = daddslashes($v);
    }
  	$DB->query("INSERT INTO `ytidc_fenzhan`(`domain`, `title`, `subtitle`, `description`, `keywords`, `notice`, `invitepercent`, `user`, `status`) VALUES ('{$domain}','{$title}','{$subtitle}','{$description}','{$keywords}','{$notice}',0,'{$user}','1')");
  	@header("Location: ./site.php");
  	exit;
}
$title = "添加分站";
include("./head.php");

?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">添加分站</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">添加站点</div>
        <div class="panel-body">
          <form role="form" action="./addsite.php" method="POST">
            <div class="form-group">
              <label>分站名称</label>
              <input type="text" name="title" class="form-control" placeholder="分站名称">
            </div>
            <div class="form-group">
              <label>分站副标题</label>
              <input type="text" name="subtitle" class="form-control" placeholder="分站副标题">
            </div>
            <div class="form-group">
              <label>分站域名</label>
              <input type="text" name="domain" class="form-control" placeholder="分站域名">
            </div>
            <div class="form-group">
              <label>分站后台公告</label>
              <textarea class="form-control" name="notice"></textarea>
            </div>
            <div class="form-group">
              <label>分站介绍</label>
              <input type="text" name="description" class="form-control" placeholder="分站介绍">
            </div>
            <div class="form-group">
              <label>分站SEO关键词</label>
              <input type="text" name="keywords" class="form-control" placeholder="分站SEO关键词">
            </div>
            <div class="form-group">
              <label>所属用户ID</label>
              <input type="number" name="user" class="form-control" placeholder="所属用户ID">
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