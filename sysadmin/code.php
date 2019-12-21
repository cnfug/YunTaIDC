<?php
include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$title = "优惠码管理";
$result = $DB->query("SELECT * FROM `ytidc_promo`");
include("./head.php");
?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">优惠码管理</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">

                                    <div class="card-title">
                                    <div class="title">优惠码列表<a href="addcode.php" class="btn btn-danger">添加新优惠码</a></div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>优惠码</th>
                                              	<th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          <?php
                                          while($row = $result->fetch_assoc()){
                                            echo '<tr>
                                                <th scope="row">'.$row['id'].'</th>
                                                <td>'.$row['code'].'</td>
                                                <td><a href="./editcode.php?id='.$row['id'].'" class="btn btn-primary">编辑</a><a href="./editcode.php?act=del&id='.$row['id'].'" class="btn btn-primary">删除</a></td>
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