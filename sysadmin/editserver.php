<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./server.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_server` WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=删除成功");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_server` SET `{$k}`='{$value}' WHERE `id`='{$id}'");
    }
  	@header("Location: ./msg.php?msg=修改成功");
  	exit;
}
$title = "编辑服务器";
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$id}'")->fetch_assoc();
$plugins = get_dir(ROOT."/plugins");
?>


            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">编辑服务器</span>
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
                                    <form method="POST" action="editserver.php?act=edit&id=<?=$id?>">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器名称</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="服务器名称" value="<?=$row['name']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器IP</label>
                                            <input name="serverip" type="text" class="form-control" id="serverip" placeholder="服务器IP" value="<?=$row['serverip']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器域名</label>
                                            <input name="serverdomain" type="text" class="form-control" id="serverdomain" placeholder="服务器域名" value="<?=$row['serverdomain']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器DNS1</label>
                                            <input name="serverdns1" type="text" class="form-control" id="sererdns1" placeholder="服务器DNS1" value="<?=$row['serverdns1']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器DNS2</label>
                                            <input name="serverdns2" type="text" class="form-control" id="serverdns2" placeholder="服务器DNS2" value="<?=$row['serverdns2']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器账号</label>
                                            <input name="serverusername" type="text" class="form-control" id="serverusername" placeholder="服务器账号" value="<?=$row['serverusername']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器密码</label>
                                            <input name="serverpassword" type="text" class="form-control" id="serverpassword" placeholder="服务器密码" value="<?=$row['serverpassword']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器哈希</label>
                                            <input name="serveraccesshash" type="text" class="form-control" id="serveraccesshash" placeholder="服务器哈希" value="<?=$row['serveraccesshash']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器面板</label>
                                            <input name="servercpanel" type="text" class="form-control" id="servercpanel" placeholder="服务器面板" value="<?=$row['servercpanel']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器端口</label>
                                            <input name="serverport" type="text" class="form-control" id="serverport" placeholder="服务器端口" value="<?=$row['serverport']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器插件</label>
                                            <div>
                                        		<select name="plugin">
                                            		<optgroup label="请选择">
                                            			<?php foreach($plugins as $k => $v){
                                            				if($row['plugin'] == $k){
                                            					$selected = "selected";
                                            				}else{
                                            					$selected = "";
                                            				}
                                            				echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                                            			}
                                             			?>
                                            		</optgroup>
                                    			</select>
                                    		</div>
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