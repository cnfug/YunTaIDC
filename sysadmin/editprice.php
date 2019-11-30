<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./type.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_type` WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=删除成功");
  	exit;
}
if($act == "edit"){
  	$price = json_encode($_POST['price']);
  	$name = daddslashes($_POST['name']);
  	$DB->query("UPDATE `ytidc_grade` SET `name`='{$name}', `price`='{$price}' WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=修改成功");
  	exit;
}
$title = "编辑价格组";
include("./head.php");
$product = $DB->query("SELECT * FROM `ytidc_product`");
$row = $DB->query("SELECT * FROM `ytidc_grade` WHERE `id`='{$id}'")->fetch_assoc();
$price = json_decode($row['price'], true);
?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">编辑价格组</span>
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
                                    <form method="POST" action="editprice.php?act=edit&id=<?=$id?>">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">产品组名称</label>
                                            <input name="name" type="text" class="form-control" id="title" placeholder="产品组名称" value="<?=$row['name']?>">
                                        </div><?php
                                      	while($row2 = $product->fetch_assoc()){
                                      		echo '<div class="form-group">
                                            <label for="exampleInputEmail1">产品【'.$row2['name'].'】的价格</label>
                                            <input name="price['.$row2['id'].']" type="text" class="form-control" id="price" placeholder="产品【'.$row2['name'].'】的价格" value="'.$price[$row2['id']].'">
                                        </div>';
                                      	}?>
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