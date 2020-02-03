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
  	@header("Location: ./config.php");
  	exit;
}
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$id}'")->fetch_assoc();
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
              <label>客服1QQ</label>
              <input type="text" name="contactqq1" class="form-control" placeholder="客服1QQ" value="<?=$conf['contactqq1']?>">
            </div>
            <div class="form-group">
              <label>客服2QQ</label>
              <input type="text" name="contactqq2" class="form-control" placeholder="客服2QQ" value="<?=$conf['contactqq2']?>">
            </div>
            <div class="form-group">
              <label>邀请用户花费奖励（百分比，1%填1）</label>
              <input name="invitepercent" type="text" class="form-control" id="serverusername" placeholder="邀请用户花费奖励" value="<?=$conf['invitepercent']?>" oninput="value=value.replace(/[^\d.]/g,'')">
            </div>
            <div class="form-group">
              <label>分站公告</label>
              <textarea name="sitenotice" class="form-control"><?=$conf['sitenotice']?></textarea>
            </div>
            <div class="form-group">
              <label>分站可选用二级域名后缀</label>
              <input name="sitedomain" type="text" class="form-control" id="sererdns1" placeholder="分站可选用二级域名" value="<?=$conf['sitedomain']?>">
            </div>
            <div class="form-group">
              <label>分站价格</label>
              <input name="siteprice" type="text" class="form-control" id="serverdns2" placeholder="分站价格" value="<?=$conf['siteprice']?>" oninput="value=value.replace(/[^\d.]/g,'')">
            </div>
            <div class="form-group">
              <label>超级管理员账号</label>
              <input type="text" name="admin" class="form-control" placeholder="超级管理员账号" value="<?=$conf['admin']?>">
            </div>
            <div class="form-group">
              <label>超级管理员密码</label>
              <input type="password" name="password" class="form-control" placeholder="超级管理员密码" value="<?=$conf['password']?>">
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