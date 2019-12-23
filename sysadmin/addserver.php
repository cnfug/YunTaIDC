<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['name']) && !empty($_POST['plugin'])){
  	foreach($_POST as $k => $v){
      	$$k = daddslashes($v);
    }
  	$DB->query("INSERT INTO `ytidc_server`(`name`, `serverip`, `serverdomain`, `serverdns1`, `serverdns2`, `serverusername`, `serverpassword`, `serveraccesshash`, `servercpanel`, `serverport`, `plugin`, `status`) VALUES ('{$name}','{$serverip}','{$serverdomain}','{$serverdns1}','{$serverdns2}','{$serverusername}','{$serverpassword}','{$serveraccesshash}','{$servercpanel}','{$serverport}','{$plugin}','1')");
  	@header("Location: ./msg.php?msg=添加服务器成功！");
  	exit;
}
$title = "添加服务器";
include("./head.php");
$plugins = get_dir(ROOT."/plugins");
?>

            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">添加服务器</span>
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
                                    <form method="POST" action="addserver.php">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器名称</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="服务器名称">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器IP</label>
                                            <input name="serverip" type="text" class="form-control" id="serverip" placeholder="服务器IP">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器域名</label>
                                            <input name="serverdomain" type="text" class="form-control" id="serverdomain" placeholder="服务器域名">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器DNS1</label>
                                            <input name="serverdns1" type="text" class="form-control" id="sererdns1" placeholder="服务器DNS1">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器DNS2</label>
                                            <input name="serverdns2" type="text" class="form-control" id="serverdns2" placeholder="服务器DNS2">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器账号</label>
                                            <input name="serverusername" type="text" class="form-control" id="serverusername" placeholder="服务器账号">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器密码</label>
                                            <input name="serverpassword" type="text" class="form-control" id="serverpassword" placeholder="服务器密码">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器哈希</label>
                                            <input name="serveraccesshash" type="text" class="form-control" id="serveraccesshash" placeholder="服务器哈希">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器面板</label>
                                            <input name="servercpanel" type="text" class="form-control" id="servercpanel" placeholder="服务器面板">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器端口</label>
                                            <input name="serverport" type="text" class="form-control" id="serverport" placeholder="服务器端口">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">服务器插件</label>
                                            <div>
                                        		<select name="plugin">
                                            		<optgroup label="请选择">
                                            			<?php foreach($plugins as $k => $v){
                                            				echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                                            			}
                                             			?>
                                            		</optgroup>
                                    			</select>
                                    		</div>
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