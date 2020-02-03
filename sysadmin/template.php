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
  	@header("Location: ./template.php");
  	exit;
}
$title = "编辑服务器";
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$id}'")->fetch_assoc();
$templates = get_dir(ROOT."/templates/");
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">模板管理</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">更换模板</div>
        <div class="panel-body">
          <form role="form" action="./template.php?act=edit" method="POST">
            <div class="form-group">
              <label>默认模板</label>
              <select name="template" class="form-control">
              	<?php
              		foreach($templates as $k => $v){
              			if($conf['template'] == $v){
              				$selected = "selected";
              			}else{
              				$selected = "";
              			}
              			echo '<option value="'.$v.'" '.$selected.'>'.$k.'</option>';
              		}
              	?>
              </select>
            </div>
            <div class="form-group">
              <label>手机端模板</label>
              <select name="template_mobile" class="form-control">
              	<?php
              		foreach($templates as $k => $v){
              			if($conf['template_mobile'] == $v){
              				$selected = "selected";
              			}else{
              				$selected = "";
              			}
              			echo '<option value="'.$v.'" '.$selected.'>'.$k.'</option>';
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