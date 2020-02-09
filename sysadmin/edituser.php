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
  	@header("Location: ./user.php");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
        $value = daddslashes($v);
        if($k == "password"){
            $value = base64_encode($value);
        }
      	$DB->query("UPDATE `ytidc_user` SET `{$k}`='{$value}' WHERE `id`='{$id}'");
    }
  	@header("Location: ./edituser.php?id={$id}");
  	exit;
}
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_user` WHERE `id`='{$id}'")->fetch_assoc();
$grade = $DB->query("SELECT * FROM `ytidc_grade` WHERE `status`='1'");
$password = base64_decode($row['password']);
?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">编辑用户</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">编辑内容</div>
        <div class="panel-body">
          <form role="form" action="./edituser.php?act=edit&id=<?=$id?>" method="POST">
            <div class="form-group">
              <label>用户邮箱</label>
              <input name="email" type="text" class="form-control" placeholder="用户邮箱" value="<?=$row['email']?>">
            </div>
            <div class="form-group">
              <label>用户密码</label>
              <input name="password" type="password" class="form-control" placeholder="用户密码" value="<?=$password?>">
            </div>
            <div class="form-group">
              <label>用户余额</label>
              <input name="money" type="text" class="form-control" placeholder="用户余额" value="<?=$row['money']?>">
            </div>
            <div class="form-group">
              <label>用户价格组</label>
              <select name="grade" class="form-control">
              	<?php while($row2 = $grade->fetch_assoc()){
              			if($row2['id'] == $row['grade']){
              				$selected = "selected";
              			}
  						echo '
                        <option value="'.$row2['id'].'" '.$selected.'>'.$row2['name'].'</option>';
					}
                ?>
              </select>
            </div>
            <div class="form-group">
              <label>邀请上级</label>
              <input name="invite" type="text" class="form-control" placeholder="邀请上级" value="<?=$row['invite']?>">
            </div>
            <div class="form-group">
              <label>所属站点</label>
              <input name="site" type="text" class="form-control" placeholder="所属站点" value="<?=$row['site']?>">
            </div>
            <button type="submit" class="btn btn-sm btn-primary">提交</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php

include("./foot.php");

?>