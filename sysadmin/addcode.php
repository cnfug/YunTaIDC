<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['code']) && !empty($_POST['price']) && !empty($_POST['product'])){
  	$code = daddslashes($_POST['code']);
  	$price = daddslashes($_POST['price']);
  	$product = daddslashes($_POST['product']);
  	$DB->query("INSERT INTO `ytidc_promo`(`code`, `price`, `product`, `status`) VALUES ('{$code}','{$price}','{$product}','1')");
  	@header("Location: ./msg.php?msg=添加价格组成功！");
  	exit;
}
$title = "添加优惠码";
include("./head.php");

?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">添加优惠码</span>
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
                                    <form method="POST" action="addcode.php">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">优惠码</label>
                                            <input name="code" type="text" class="form-control" id="code" placeholder="优惠码">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">抵扣金额</label>
                                            <input name="price" type="text" class="form-control" id="name" placeholder="抵扣金额">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">优惠产品ID【仅支持单ID】</label>
                                            <input name="product" type="text" class="form-control" id="name" placeholder="用户通过支付价格开通价格组（三选一）">
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