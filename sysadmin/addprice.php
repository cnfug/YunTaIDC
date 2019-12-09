<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['name']) && !empty($_POST['weight']) && !empty($_POST['need_money']) && !empty($_POST['need_save']) && !empty($_POST['need_paid'])){
  	$name = daddslashes($_POST['name']);
  	$weight = daddslashes($_POST['weight']);
  	$need_money = daddslashes($_POST['need_money']);
  	$need_save = daddslashes($_POST['need_save']);
  	$need_paid = daddslashes($_POST['need_paid']);
  	$date = date('Y-m-d');
  	$DB->query("INSERT INTO `ytidc_grade`(`name`, `weight`, `need_paid`, `need_money`, `need_save`, `default`, `price`, `status`) VALUES ('{$name}','{$weight}','{$need_paid}','{$need_money}','{$need_save}','0','','1')");
  	@header("Location: ./msg.php?msg=添加价格组成功！");
  	exit;
}
$title = "添加价格组";
include("./head.php");

?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">添加价格组</span>
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
                                    <form method="POST" action="addprice.php">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">价格组名称</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="价格组名称">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">价格组等级（越大越高级）</label>
                                            <input name="weight" type="text" class="form-control" id="name" placeholder="价格组等级">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">购买自动开通价格（0为不启用）</label>
                                            <input name="need_money" type="text" class="form-control" id="name" placeholder="用户通过支付价格开通价格组（三选一）">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">预存自动开通所需预存（0为不启用）</label>
                                            <input name="need_save" type="text" class="form-control" id="name" placeholder="用户通过预存开通价格组（不扣款，三选一）">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">消费自动开通所需消费（0为不启用）</label>
                                            <input name="need_paid" type="text" class="form-control" id="name" placeholder="用户通过消费开通价格组（不扣款，三选一）">
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