<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['title']) && !empty($_POST['content'])){
  	$title = daddslashes($_POST['title']);
  	$content = daddslashes($_POST['content']);
  	$date = date('Y-m-d');
  	$DB->query("INSERT INTO `ytidc_notice` (`title`, `content`, `date`) VALUES ('{$title}', '{$content}', '$date')");
  	@header("Location: ./msg.php?msg=添加公告成功！");
  	exit;
}
$title = "添加公告";
include("./head.php");

?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">添加公告</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="title">添加内容</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="addnotice.php">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">公告标题</label>
                                            <input name="title" type="text" class="form-control" id="title" placeholder="公告标题">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">公告内容</label><br>
                                            <textarea name="content" row="6" class="form-control" id="content"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-default">添加</button>
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