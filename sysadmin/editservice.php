<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./service.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_service` WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=删除成功");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_service` SET `{$k}`='{$value}' WHERE `id`='{$id}'");
    }
  	@header("Location: ./msg.php?msg=修改成功");
  	exit;
}
$title = "编辑在线服务";
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_service` WHERE `id`='{$id}'")->fetch_assoc();
$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$row['id']}'")->fetch_assoc();
?>


            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">编辑在线服务</span>
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
                                    <form method="POST" action="editservice.php?act=edit&id=<?=$id?>">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">购买服务名称</label>
                                            <input type="text" class="form-control" id="name" placeholder="购买服务名称" value="<?=$product['name']?>" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务账号</label>
                                            <input name="username" type="text" class="form-control" id="serverip" placeholder="服务账号" value="<?=$row['username']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务密码</label>
                                            <input name="password" type="text" class="form-control" id="serverdomain" placeholder="服务密码" value="<?=$row['password']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">到期日期</label>
                                            <input name="enddate" type="date" class="form-control" id="sererdns1" placeholder="到期日期" value="<?=$row['enddate']?>">
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