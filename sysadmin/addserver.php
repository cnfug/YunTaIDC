<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['name']) && !empty($_POST['plugin'])){
  	foreach($_POST as $k => $v){
      	$$k = daddslashes($v);
    }
  	$DB->query("INSERT INTO `ytidc_server`(`name`, `serverip`, `serverdomain`, `serverdns1`, `serverdns2`, `serverusername`, `serverpassword`, `serveraccesshash`, `servercpanel`, `serverport`, `plugin`, `status`) VALUES ('{$name}','{$serverip}','{$serverdomain}','{$serverdns1}','{$serverdns2}','{$serverusername}','{$serverpassword}','{$serveraccesshash}','{$servercpanel}','{$serverport}','{$plugin}','1')");
  	@header("Location: ./server.php");
  	exit;
}
$title = "添加服务器";
include("./head.php");
$plugins = get_dir(ROOT."/plugins/server");
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">添加服务器</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">添加服务器</div>
        <div class="panel-body">
          <form role="form" action="./addserver.php" method="POST">
            <div class="form-group">
              <label>服务器名称：</label>
              <input type="text" name="name" class="form-control" placeholder="服务器名称">
            </div>
            <div class="form-group">
              <label>服务器IP</label>
              <input type="text" name="serverip" class="form-control" placeholder="服务器IP">
            </div>
            <div class="form-group">
              <label>服务器域名</label>
              <input type="text" name="serverdomain" class="form-control" placeholder="服务器域名">
            </div>
            <div class="form-group">
              <label>服务器DNS1</label>
              <input type="text" name="serverdns1" class="form-control" placeholder="服务器DNS1">
            </div>
            <div class="form-group">
              <label>服务器DNS2</label>
              <input type="text" name="serverdns2" class="form-control" placeholder="服务器DNS2">
            </div>
            <div class="form-group">
              <label>服务器账号</label>
              <input type="text" name="serverusername" class="form-control" placeholder="服务器账号">
            </div>
            <div class="form-group">
              <label>服务器密码</label>
              <input type="text" name="serverpassword" class="form-control" placeholder="服务器密码">
            </div>
            <div class="form-group">
              <label>服务器哈希</label>
              <input type="text" name="serveraccesshash" class="form-control" placeholder="服务器哈希">
            </div>
            <div class="form-group">
              <label>服务器控制面板</label>
              <input type="text" name="servercpanel" class="form-control" placeholder="服务器控制面板">
            </div>
            <div class="form-group">
              <label>服务器端口</label>
              <input type="number" name="serverport" class="form-control" placeholder="服务器端口">
            </div>
            <div class="form-group">
              <label>服务器接通插件</label>
              <select name="plugin" class="form-control m-b">
              	<?php
              	foreach($plugins as $k => $v){
              		echo '<option value="'.$v.'">'.$k.'</option>';
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