<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['title']) && !empty($_POST['content'])){
  	$title = daddslashes($_POST['title']);
  	$content = daddslashes($_POST['content']);
  	$date = date('Y-m-d');
  	$DB->query("INSERT INTO `ytidc_notice` (`title`, `content`, `date`, `site`, `status`) VALUES ('{$title}', '{$content}', '$date', '0', '1')");
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