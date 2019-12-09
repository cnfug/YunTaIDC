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
  	$DB->query("DELETE FROM `ytidc_fenzhan` WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=删除成功");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_fenzhan` SET `{$k}`='{$value}' WHERE `id`='{$id}'");
    }
  	@header("Location: ./msg.php?msg=修改成功");
  	exit;
}
$title = "编辑分站";
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `id`='{$id}'")->fetch_assoc();
?>
<div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">编辑分站</span>
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
                                    <form method="POST" action="editsite.php?act=edit&id=<?=$id?>">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站名称</label>
                                            <input name="title" type="text" class="form-control" id="name" placeholder="分站名称" value="<?=$row['title']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站副标题</label>
                                            <input name="subtitle" type="text" class="form-control" id="name" placeholder="分站副标题" value="<?=$row['subtitle']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站域名</label>
                                            <input name="domain" type="text" class="form-control" id="serverip" placeholder="分站域名" value="<?=$row['domain']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站介绍</label>
                                            <textarea class="form-control" row="6" name="description"><?=$row['description']?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站后台公告</label>
                                            <textarea class="form-control" row="6" name="notice"><?=$row['notice']?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">站长账号</label>
                                            <input name="admin" type="text" class="form-control" id="sererdns1" placeholder="站长账号" value="<?=$row['admin']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">站长密码</label>
                                            <input name="password" type="text" class="form-control" id="serverdns2" placeholder="站长密码" value="<?=$row['password']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">所属用户ID</label>
                                            <input name="user" type="text" class="form-control" id="serverusername" placeholder="所属用户ID" value="<?=$row['user']?>">
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