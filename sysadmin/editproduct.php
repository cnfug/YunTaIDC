<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./type.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_product` WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=删除成功");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_product` SET `{$k}`='{$value}' WHERE `id`='{$id}'");
    }
  	$configoption = ExplodeConfig(daddslashes($_POST['configoption']));
  	$DB->query("UPDATE `ytidc_product` SET `configoption`='{$configoption}' WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=修改成功");
  	exit;
}
$title = "编辑产品";
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$id}'")->fetch_assoc();
$configoption = PackConfig($row['configoption']);
$type = $DB->query("SELECT * FROM `ytidc_type` WHERE `status`='1'");
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `status`='1'");
?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">编辑产品</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="title">编辑内容</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="editproduct.php?act=edit&id=<?=$id?>">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">产品名称</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="产品名称" value="<?=$row['name']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">产品介绍</label>
                                            <textarea class="form-control" name="description" row="6"><?=$row['description']?></textarea>
                                        </div>
                                      	<div class="form-group">
                                          	<label for="exampleInputEmail1">产品分类</label>
                                    <div>
                                        <select name="type">
                                            <optgroup label="请选择">
                                              <?php while($row2 = $type->fetch_assoc()){
  														echo '
                                                <option value="'.$row2['id'].'">'.$row2['name'].'</option>';
													}
                                             	?>
                                            </optgroup>
                                        </select>
                                    </div>	
                                      	</div>
                                      	<div class="form-group">
                                          	<label for="exampleInputEmail1">产品服务器</label>
                                          	
                                    <div>
                                        <select name="server">
                                            <optgroup label="请选择">
                                              <?php while($row2 = $server->fetch_assoc()){
  														echo '
                                                <option value="'.$row2['id'].'">'.$row2['name'].'</option>';
													}
                                             	?>
                                            </optgroup>
                                        </select>
                                    </div>	
                                      	</div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">产品期限（天）</label>
                                            <input name="time" type="number" class="form-control" id="time" placeholder="产品期限" value="<?=$row['time']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">产品配置</label>
                                            <textarea class="form-control" name="configoption" row="6"><?=$configoption?></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-default">修改</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php

include("./foot.php");

?>