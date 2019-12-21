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
$title = "管理后台";
$result = $DB->query("SELECT * FROM `ytidc_notice` WHERE `site`='{$site['id']}'");
include("./head.php");
?>


            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">公告管理</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">

                                    <div class="card-title">
                                    <div class="title">公告列表<a href="addnotice.php" class="btn btn-danger">添加新公告</a></div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>标题</th>
                                              	<th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          <?php
                                          while($row = $result->fetch_assoc()){
                                            echo '<tr>
                                                <th scope="row">'.$row['id'].'</th>
                                                <td>'.$row['title'].'</td>
                                                <td><a href="./editnotice.php?id='.$row['id'].'" class="btn btn-primary">编辑</a><a href="./editnotice.php?act=del&id='.$row['id'].'" class="btn btn-primary">删除</a></td>
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