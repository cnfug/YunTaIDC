<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['name']) && !empty($_POST['domain']) && !empty($_POST['description']) && !empty($_POST['admin']) && !empty($_POST['password']) && !empty($_POST['user']) && !empty($_POST['notice'])){
  	foreach($_POST as $k => $v){
      	$$k = daddslashes($v);
    }
  	$DB->query("INSERT INTO `ytidc_fenzhan` (`domain`, `name`, `description`, `notice`, `admin`, `password`, `user`, `status`) VALUES ('{$domain}', '{$name}', '{$description}', '{$notice}', '{$admin}', '{$password}', '{$user}', '1')");
  	@header("Location: ./msg.php?msg=添加服务器成功！");
  	exit;
}
$title = "添加分站";
include("./head.php");

?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">添加分站</span>
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
                                    <form method="POST" action="addsite.php">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站名称</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="分站名称">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站域名</label>
                                            <input name="domain" type="text" class="form-control" id="serverip" placeholder="分站域名">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站介绍</label>
                                            <textarea class="form-control" row="6" name="description"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站后台公告</label>
                                            <textarea class="form-control" row="6" name="notice"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">站长账号</label>
                                            <input name="admin" type="text" class="form-control" id="sererdns1" placeholder="站长账号">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">站长密码</label>
                                            <input name="password" type="text" class="form-control" id="serverdns2" placeholder="站长密码">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">所属用户ID</label>
                                            <input name="user" type="text" class="form-control" id="serverusername" placeholder="所属用户ID">
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