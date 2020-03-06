<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./pay.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_payplugin` WHERE `id`='{$id}'");
  	@header("Location: ./pay.php");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_payplugin` SET `{$k}`='{$value}' WHERE `id`='{$id}'");
    }
  	$configoption = json_encode(daddslashes($_POST['configoption']));
  	$DB->query("UPDATE `ytidc_payplugin` SET `configoption`='{$configoption}' WHERE `id`='{$id}'");
  	@header("Location: ./editpay.php?id={$id}");
  	exit;
}
$row = $DB->query("SELECT * FROM `ytidc_payplugin` WHERE `id`='{$id}'")->fetch_assoc();
$row['configoption'] = json_decode($row['configoption'], 1);
$plugin = "../plugins/payment/".$row['gateway']."/main.php";
if(!file_exists($plugin) || !is_file($plugin)){
	@header("Location: ./msg.php?msg=插件文件不存在，请删除后重新添加！");
	exit;
}
include($plugin);
include("./head.php");

$plugins_file = get_dir(ROOT."/plugins/payment/");
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">支付接口编辑</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">配置接口</div>
        <div class="panel-body">
          <form role="form" action="./editpay.php?act=edit&id=<?=$id?>" method="POST">
            <div class="form-group">
              <label>显示名称</label>
              <input type="text" name="displayname" class="form-control" placeholder="显示名称" value="<?=$row['displayname']?>">
            </div>
            <div class="form-group">
              <label>接口插件</label>
              <select name="gateway" class="form-control m-b">
              	<?php
              	foreach($plugins_file as $k => $v){
              		if($v == $row['gateway']){
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
              <label>到账费率（95%就填95）</label>
              <input type="text" name="fee" class="form-control" placeholder="到账费率" value="<?=$row['fee']?>">
            </div>
            <div class="form-group">
              <label>接口状态</label>
              <select name="status" class="form-control m-b">
              	<?php
              		if($v == $row['gateway']){
              			echo '
              	<option value="1" selected>开启</option>
              	<option value="0">关闭</option>';
              		}else{
              			echo '
              	<option value="1">开启</option>
              	<option value="0" selected>关闭</option>';
              		}
              	?>
              </select>
            </div>
            <?php
                                        if(function_exists($row['gateway']."_ConfigOption")){
                                        	$function = $row['gateway']."_ConfigOption";
                                        	$configoption = $function();
                                        	foreach($configoption as $k => $v){
                                        		if($v['type'] == "text"){
                                        			echo '<div class="form-group">
                                            <label>【插件配置】：'.$v['label'].'</label>
                                            <input type="text" class="form-control" name="configoption['.$k.']" placeholder="'.$v['placeholder'].'" maxlength="256" value="'.$row['configoption'][$k].'">
                                        </div>';
                                        		}
                                        		if($v['type'] == "number"){
                                        			echo '<div class="form-group">
                                            <label>【插件配置】：'.$v['label'].'</label>
                                            <input type="number" class="form-control" name="configoption['.$k.']" placeholder="'.$v['placeholder'].'" maxlength="256" value="'.$row['configoption'][$k].'">
                                        </div>';
                                        		}
                                        		if($v['type'] == "select"){
                                        			echo '<div class="form-group">
                                            		<label>【插件配置】：'.$v['label'].'</label>
                                        			<select name="configoption['.$k.']" class="form-control">';
                                        			foreach($v['option'] as $k1 => $v1){
                                        				if($row['configoption'][$k] == $v1){
                                        					echo '<option value="'.$v1.'" selected>'.$k1.'</option>';
                                        				}else{
                                        					echo '<option value="'.$v1.'">'.$k1.'</option>';
                                        				}
                                        			}
                                        			echo '</select></div>';
                                        		}
                                        	}
                                        }
            ?>
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