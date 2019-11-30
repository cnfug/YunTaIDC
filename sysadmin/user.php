<?php
include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$title = "用户管理";
$result = $DB->query("SELECT * FROM `ytidc_user`");
include("./head.php");
?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">用户管理</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">

                                    <div class="card-title">
                                    <div class="title">用户列表</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>账号</th>
                                                <th>余额</th>
                                              	<th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          <?php
                                          while($row = $result->fetch_assoc()){
                                            echo '<tr>
                                                <th scope="row">'.$row['id'].'</th>
                                                <td>'.$row['username'].'</td>
                                                <td>'.$row['money'].'</td>
                                                <td><a href="./edituser.php?id='.$row['id'].'" class="btn btn-primary">编辑</a><a href="./edituser.php?act=del&id='.$row['id'].'" class="btn btn-primary">删除</a></td>
                                            </tr>';
                                          }
                                          ?>
                                                
                                        </tbody>
                                    </table>
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