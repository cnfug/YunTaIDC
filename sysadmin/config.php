<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_config` SET `v`='{$value}' WHERE `k`='{$k}'");
    }
  	@header("Location: ./msg.php?msg=修改成功");
  	exit;
}
$title = "编辑服务器";
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$id}'")->fetch_assoc();
?>


            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">编辑网站</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="title">管理内容</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="config.php?act=edit">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">超级管理账号</label>
                                            <input name="admin" type="text" class="form-control" id="name" placeholder="超级管理账号" value="<?=$conf['admin']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">超级管理密码</label>
                                            <input name="password" type="text" class="form-control" id="serverip" placeholder="超级管理密码" value="<?=$conf['password']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">模板目录名称</label>
                                            <input name="template" type="text" class="form-control" id="serverdns2" placeholder="模板目录名称" value="<?=$conf['template']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站公告</label>
                                            <textarea name="sitenotice" class="form-control"><?=$conf['sitenotice']?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站可选用二级域名（目前只能填写一个）</label>
                                            <input name="sitedomain" type="text" class="form-control" id="sererdns1" placeholder="分站可选用二级域名" value="<?=$conf['sitedomain']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">分站价格</label>
                                            <input name="siteprice" type="text" class="form-control" id="serverdns2" placeholder="分站价格" value="<?=$conf['siteprice']?>" oninput="value=value.replace(/[^\d.]/g,'')">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">邀请用户花费奖励（百分比，1%填1）</label>
                                            <input name="invitepercent" type="text" class="form-control" id="serverusername" placeholder="邀请用户花费奖励" value="<?=$conf['invitepercent']?>" oninput="value=value.replace(/[^\d.]/g,'')">
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