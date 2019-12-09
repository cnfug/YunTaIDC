<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./worder.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_worder` WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=删除成功");
  	exit;
}
if($act == "edit"){
  	$reply = daddslashes($_POST['reply']);
  	$DB->query("UPDATE `ytidc_worder` SET `reply`='{$reply}', `status`='已回复' WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=回复成功");
  	exit;
}
$title = "编辑公告";
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_worder` WHERE `id`='{$id}'")->fetch_assoc();
?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">回复工单</span>
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
                                    <form method="POST" action="editworder.php?act=edit&id=<?=$id?>">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">工单标题</label>
                                            <input name="title" type="text" class="form-control" id="title" placeholder="公告标题" value="<?=$row['title']?>" disabled="">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">工单内容</label><br>
                                            <textarea name="content" row="6" class="form-control" id="content" disabled=""><?=$row['content']?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">你的回复</label><br>
                                            <textarea name="reply" row="6" class="form-control" id="content"><?=$row['reply']?></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-default">回复</button>
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