<?php
include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$title = "服务器管理";
$result = $DB->query("SELECT * FROM `ytidc_server`");
include("./head.php");
?>
        <div class="bg-light lter b-b wrapper-md">
          <h1 class="m-n font-thin h3">服务器管理</h1>
        </div>
        <div class="wrapper-md">
          <div class="panel panel-default">
            <div class="panel-heading">
              服务器列表<a href="./addserver.php" class="btn btn-primary btn-xs btn-small">添加</a>
            </div>
            <div class="table-responsive">
              <table class="table table-striped b-t b-light">
                <thead>
                  <tr>
                    <th>编号</th>
                    <th>名称</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                	<?php
                  	 while($row = $result->fetch_assoc()){
                  	 	echo '<tr>
                    <td>'.$row['id'].'</td>
                    <td>'.$row['name'].'</td>
                    <td><a href="./editserver.php?id='.$row['id'].'" class="btn btn-primary btn-xs btn-small">编辑</a><a href="./editserver.php?act=del&id='.$row['id'].'" class="btn btn-default btn-xs btn-small">删除</a></td>
                  </tr>';
                  	 }
                  	?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
<?php

include("./foot.php");
?>