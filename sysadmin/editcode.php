<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
	@header("Location: ./code.php");
	exit;
}
$promo = $DB->query("SELECT * FROM `ytidc_promo` WHERE `id`='{$id}'");
if($promo->num_rows != 1){
	@header("Location: ./code.php");
	exit();
}else{
	$row = $promo->fetch_assoc();
}
$act = daddslashes($_GET['act']);
if($act == "edit"){
	if(!empty($_POST['code']) && !empty($_POST['price']) && !empty($_POST['product'])){
	  	$code = daddslashes($_POST['code']);
	  	$price = daddslashes($_POST['price']);
	  	$product = daddslashes($_POST['product']);
	  	$DB->query("UPDATE `ytidc_promo` SET `code`='{$code}', `price`='{$price}', `product`='{$product}' WHERE `id`='{$id}'");
	  	@header("Location: ./msg.php?msg=添加价格组成功！");
	  	exit;
	}else{
	  	@header("Location: ./msg.php?msg=参数不能为空！");
	  	exit;
	}
}
if($act == "del"){
	$DB->query("DELETE * FROM `ytidc_promo` WHERE `id`='{$id}'");
	@header("Location: ./msg.php?msg=删除成功！");
	exit;
}

$title = "编辑优惠码";
include("./head.php");

?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">编辑优惠码</span>
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
                                    <form method="POST" action="editcode.php?id=<?=$id?>&act=edit">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">优惠码</label>
                                            <input name="code" type="text" class="form-control" id="code" placeholder="优惠码" value="<?=$row['code']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">抵扣金额</label>
                                            <input name="price" type="text" class="form-control" id="name" placeholder="抵扣金额" value="<?=$row['price']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">优惠产品ID【仅支持单ID】</label>
                                            <input name="product" type="text" class="form-control" id="name" placeholder="优惠产品ID" value="<?=$row['product']?>">
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