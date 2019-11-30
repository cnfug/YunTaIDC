<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./notice.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_notice` WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=删除成功");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$$k = daddslashes($v);
    }
  	$date = date('Y-m-d');
  	$DB->query("UPDATE `ytidc_notice` SET `title`='{$title}', `content`='{$content}', `date`='{$date}' WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=修改成功");
  	exit;
}
$title = "编辑公告";
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_notice` WHERE `id`='{$id}'")->fetch_assoc();
?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">编辑公告</span>
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
                                    <form method="POST" action="editnotice.php?act=edit&id=<?=$id?>">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">公告标题</label>
                                            <input name="title" type="text" class="form-control" id="title" placeholder="公告标题" value="<?=$row['title']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">公告内容</label><br>
                                            <textarea name="content" row="6" class="form-control" id="content"><?=$row['content']?></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-default">编辑</button>
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