<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./user.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_user` WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=删除成功");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_user` SET `{$k}`='{$value}' WHERE `id`='{$id}'");
    }
  	@header("Location: ./msg.php?msg=修改成功");
  	exit;
}
$title = "编辑用户";
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$id}'")->fetch_assoc();
$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `status`='1'");
?>


            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">编辑用户</span>
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
                                    <form method="POST" action="edituser.php?act=edit&id=<?=$id?>">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">用户邮箱</label>
                                            <input name="email" type="email" class="form-control" id="email" placeholder="用户邮箱" value="<?=$row['email']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">用户余额</label>
                                            <input name="money" type="text" class="form-control" id="money" placeholder="用户余额" value="<?=$row['money']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">用户等级</label>
                                          <div>
                                        <select name="grade">
                                            <optgroup label="请选择">
                                              <?php while($row2 = $grade->fetch_assoc()){
  														echo '
                                                <option value="'.$row2['id'].'">'.$row2['name'].'</option>';
													}
                                             	?>
                                            </optgroup>
                                        </select>
                                    </div>	
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">邀请上级</label>
                                            <input name="money" type="text" class="form-control" id="money" placeholder="用户余额" value="<?=$row['invite']?>" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">所属站点ID</label>
                                            <input name="money" type="text" class="form-control" id="money" placeholder="用户余额" value="<?=$row['site']?>" disabled>
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
