<?php

include("../includes/common.php");
$domain = $_SERVER['HTTP_HOST'];
$row = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `domain`='{$domain}'")->fetch_assoc();
$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$row['user']}'")->fetch_assoc();
if(empty($_SESSION['fzadmin']) || empty($_SESSION['fzkey'])){
  	@header("Location: ./login.php");
  	exit;
}
$fzadmin = daddslashes($_SESSION['fzadmin']);
$fzkey = daddslashes($_SESSION['fzkey']);
if($fzadmin != $row['admin'] && $fzkey != md5($_SERVER['HTTP_HOST'].$row['password']."fz")){
  	@header("Location: ./login.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_fenzhan` SET `$k`='{$value}' WHERE `domain`='{$domain}'");
    }
  	@header("Location: ./index.php");
  	exit;
}
$title = "编辑服务器";
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `domain`='{$domain}'")->fetch_assoc();
?>


            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">编辑网站</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="title">管理内容</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="config.php?act=edit">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">网站标题</label>
                                            <input name="title" type="text" class="form-control" id="name" placeholder="网站标题" value="<?=$row['title']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">网站副标题</label>
                                            <input name="subtitle" type="text" class="form-control" id="serverip" placeholder="网站副标题" value="<?=$row['subtitle']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">网站关键词</label>
                                            <input name="keywords" type="text" class="form-control" id="serverip" placeholder="网站关键词" value="<?=$row['keywords']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">网站介绍</label>
                                            <textarea name="description" class="form-control"><?=$row['description']?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">管理账号</label>
                                            <input name="admin" type="text" class="form-control" id="sererdns1" placeholder="管理账号" value="<?=$row['admin']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">管理密码</label>
                                            <input name="password" type="password" class="form-control" id="serverdns2" placeholder="管理密码" value="<?=$row['password']?>" oninput="value=value.replace(/[^\d.]/g,'')">
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