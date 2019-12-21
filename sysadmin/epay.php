<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['epayurl']) && !empty($_POST['epayid']) && !empty($_POST['epaykey'])){
  	foreach($_POST as $k => $v){
      	$$k = daddslashes($v);
      	$DB->query("UPDATE `ytidc_config` SET `v`='{$$k}' WHERE `k`='{$k}'");
    }
  	@header("Location: ./msg.php?msg=修改成功");
  	exit;
}
$title = "易支付管理";
include("./head.php");
?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">易支付管理</span>
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
                                    <form method="POST" action="epay.php">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">易支付网址</label>
                                            <input name="epayurl" type="text" class="form-control" id="epayurl" placeholder="易支付网址" value="<?=$conf['epayurl']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">易支付ID</label>
                                            <input name="epayid" type="text" class="form-control" id="epayid" placeholder="易支付ID" value="<?=$conf['epayid']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">易支付KEY</label>
                                            <input name="epaykey" type="text" class="form-control" id="epaykey" placeholder="易支付KEY" value="<?=$conf['epaykey']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">微信通道手续费（1%填1）</label>
                                            <input name="epay_fee_wx" type="text" class="form-control" id="epayurl" placeholder="微信通道手续费" value="<?=$conf['epay_fee_wx']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">QQ通道手续费（1%填1）</label>
                                            <input name="epay_fee_qq" type="text" class="form-control" id="epayurl" placeholder="QQ通道手续费" value="<?=$conf['epay_fee_qq']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">支付宝通道手续费（1%填1）</label>
                                            <input name="epay_fee_zfb" type="text" class="form-control" id="epayurl" placeholder="支付宝通道手续费" value="<?=$conf['epay_fee_zfb']?>">
                                        </div>
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