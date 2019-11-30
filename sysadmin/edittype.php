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
  	$DB->query("DELETE FROM `ytidc_type` WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=删除成功");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_type` SET `{$k}`='{$value}' WHERE `id`='{$id}'");
    }
  	@header("Location: ./msg.php?msg=修改成功");
  	exit;
}
$title = "编辑产品组";
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_type` WHERE `id`='{$id}'")->fetch_assoc();
?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">编辑产品组</span>
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
                                    <form method="POST" action="edittype.php?act=edit&id=<?=$id?>">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">产品组名称</label>
                                            <input name="name" type="text" class="form-control" id="title" placeholder="分类名称" value="<?=$row['name']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">产品组分类</label><br>
                                          <div class="radio3 radio-check radio-inline">
                                            <input type="radio" id="radio1" name="type" value="host">
                                            <label for="radio1">
                                              虚拟主机
                                            </label>
                                          </div>
                                          <div class="radio3 radio-check radio-inline">
                                            <input type="radio" id="radio2" name="type" value="vps">
                                            <label for="radio2">
                                              云服务器
                                            </label>
                                          </div>
                                          <div class="radio3 radio-check radio-inline">
                                            <input type="radio" id="radio3" name="type" value="server">
                                            <label for="radio3">
                                              独立服务器
                                            </label>
                                          </div>
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