<?php

include("../includes/common.php");
if(!empty($_GET['type'])){
  	$type = daddslashes($_GET['type']);
  	$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `type`='{$type}'");
}else{
  	$type = $DB->query("SELECT * FROM `ytidc_type`")->fetch_assoc();
  	$product = $DB->query("SELECT * FROM `ytidc_product` WHERE `type`='{$type['id']}'");
}
if(!empty($_SESSION['ytidc_user']) && !empty($_SESSION['ytidc_pass'])){
  	$username = daddslashes($_SESSION['ytidc_user']);
  	$password = daddslashes($_SESSION['ytidc_pass']);
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$usernmae}' and `password`='{$password}'")->fetch_assoc();
}
$grade = $user['grade'];
if($grade == 0){
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
  	$price = json_decode($grade['price'], true);
}else{
  	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE id='{$grade}'");
  	if($grade->num_rows != 1){
      	$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `default`='1'")->fetch_assoc();
  		$price = json_decode($grade['price'], true);
    }else{
      	$grade = $grade->fetch_assoc();
      	$price = json_decode($grade['price'], true);
    }
}


$title = "选购产品";
include("./head.php");
?>
            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">选购产品</span>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <form action="./buy.php" method="GET">
                                        <select name="type" style="font-size: 20px;">
                                            <optgroup label="虚拟主机">
                                              <?php
                                              $type = $DB->query("SELECT * FROM `ytidc_type` WHERE `type`='host'");
                                              while($row2 = $type->fetch_assoc()){
                                                	echo '
                                                <option value="'.$row2['id'].'">'.$row2['name'].'</option>';
                                              }
                                              ?>
                                            </optgroup>
                                            <optgroup label="云服务器/VPS">
                                              <?php
                                              $type = $DB->query("SELECT * FROM `ytidc_type` WHERE `type`='vps'");
                                              while($row2 = $type->fetch_assoc()){
                                                	echo '
                                                <option value="'.$row2['id'].'">'.$row2['name'].'</option>';
                                              }
                                              ?>
                                            </optgroup>
                                            <optgroup label="独立服务器">
                                              <?php
                                              $type = $DB->query("SELECT * FROM `ytidc_type` WHERE `type`='server'");
                                              while($row2 = $type->fetch_assoc()){
                                                	echo '
                                                <option value="'.$row2['id'].'">'.$row2['name'].'</option>';
                                              }
                                              ?>
                                            </optgroup>
                                        </select><button type="submit" class="btn btn-default">选择分类</button></form>
                                    </div>
                                    <div class="pull-right card-action">
                                        <div class="btn-group" role="group" aria-label="...">
                                            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#modalCardProfileExample"><i class="fa fa-code"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row no-margin no-gap">
                                      	<?php
                                      		while($row2 = $product->fetch_assoc()){
                                              	echo '<div class="col-sm-3">
                                            <div class="pricing-table green">
                                                <div class="pt-header">
                                                    <div class="plan-pricing">
                                                        <div class="pricing">'.$price[$row2['id']].'元</div>
                                                        <div class="pricing-type">'.$row2['time'].'天</div>
                                                    </div>
                                                </div>
                                                <div class="pt-body">
                                                    <h4>'.$row2['name'].'</h4>
                                                    <ul class="plan-detail">
                                                        '.$row2['description'].'
                                                    </ul>
                                                </div>
                                                <div class="pt-footer">
                                                    <a href="./cart.php?id='.$row2['id'].'" class="btn btn-success">购买</a>
                                                </div>
                                            </div>
                                        </div>';
                                            }
                                      	?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
	include('./foot.php');
?>