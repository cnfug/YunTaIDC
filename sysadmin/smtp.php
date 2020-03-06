<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_config` SET `v`='{$value}' WHERE `k`='{$k}'");
    }
  	@header("Location: ./smtp.php");
  	exit;
}
include("./head.php");
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">SMTP管理</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">SMTP服务设置</div>
        <div class="panel-body">
          <form role="form" action="./smtp.php?act=edit" method="POST">
            <div class="form-group">
              <label>服务器地址</label>
              <input type="text" name="smtp_host" class="form-control" placeholder="SMTP服务器地址" value="<?=$conf['smtp_host']?>">
            </div>
            <div class="form-group">
              <label>SMTP账号</label>
              <input type="text" name="smtp_user" class="form-control" placeholder="SMTP账号" value="<?=$conf['smtp_user']?>">
            </div>
            <div class="form-group">
              <label>SMTP密码</label>
              <input type="text" name="smtp_pass" class="form-control" placeholder="SMTP密码" value="<?=$conf['smtp_pass']?>">
            </div>
            <div class="form-group">
              <label>SMTP端口</label>
              <input type="text" name="smtp_port" class="form-control" placeholder="SMTP端口" value="<?=$conf['smtp_port']?>">
            </div>
            <div class="form-group">
              <label>SMTP加密方式</label>
              <select name="smtp_secure" class="form-control">
              <?php
            	if($conf['smtp_secure'] == 'tls'){
            		echo '<option value="tls" selected>TLS加密</option><option value="ssl">SSL加密</option><option value="0">不加密</option>';
            	}elseif($conf['smtp_secure'] == "ssl"){
            		echo '<option value="tls">TLS加密</option><option value="ssl" selected>SSL加密</option><option value="0">不加密</option>';
            	}else{
            		echo '<option value="tls">TLS加密</option><option value="ssl">SSL加密</option><option value="0" selected>不加密</option>';
            	}
              ?>
              </select>
            </div>
            <div class="form-group">
              <label>提前续费提醒（天）</label>
              <input type="text" name="mail_alert" class="form-control" placeholder="续费提醒" value="<?=$conf['mail_alert']?>">
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