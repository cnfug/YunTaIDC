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
  	$DB->query("DELETE FROM `ytidc_product` WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=删除成功");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_product` SET `{$k}`='{$value}' WHERE `id`='{$id}'");
    }
  	$configoption = json_encode(daddslashes($_POST['configoption']));
  	$DB->query("UPDATE `ytidc_product` SET `configoption`='{$configoption}' WHERE `id`='{$id}'");
  	$time = json_encode(url_encode(daddslashes($_POST['time'])));
  	$DB->query("UPDATE `ytidc_product` SET `time`='{$time}' WHERE `id`='{$id}'");
  	@header("Location: ./msg.php?msg=修改成功");
  	exit;
}
$title = "编辑产品";
include("./head.php");
$row = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$id}'")->fetch_assoc();
$row['configoption'] = json_decode($row['configoption'], 1);
$time = json_decode(url_decode($row['time']),true);
$timecount = count($time);
$type = $DB->query("SELECT * FROM `ytidc_type` WHERE `status`='1'");
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `status`='1'");
$serverinfo = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$row['server']}'")->fetch_assoc();
$plugin = "../plugins/".$serverinfo['plugin']."/main.php";
if(!file_exists($plugin) || !is_file($plugin)){
	@header("Location: ./msg.php?msg=服务器插件不存在");
	exit;
}
include($plugin);
?>

<script>
        var count = <?=$timecount?>;

        //用来判断是删除 还是增加按钮 以便count值进行计算
        function checkCount(boolOK, coun) {
            if (boolOK == true) {
                return count++;
            }
            else {
                count--;
            }
        }
        function AddTimeInput() {
            // checkCount(2, true);
            countAA = checkCount(true, count);
            // alert(countAA);
            //count++;
            var time = document.getElementById("timetable");

            var tr = document.createElement('tr');
    	    	var td = document.createElement('td');
    	    	td.innerHTML='<input type="text" class="form-control" name="time[' + count + '][name]" value=""/>';
    			tr.appendChild(td);
    	    	var td = document.createElement('td');
    	    	td.innerHTML='<input type="text" class="form-control" name="time[' + count + '][discount]" value=""/>';
    			tr.appendChild(td);
    	    	var td = document.createElement('td');
    	    	td.innerHTML='<input type="text" class="form-control" name="time[' + count + '][day]" value=""/>';
    			tr.appendChild(td);
    	    	var td = document.createElement('td');
    	    	td.innerHTML='<input type="text" class="form-control" name="time[' + count + '][remark]" value=""/>';
    			tr.appendChild(td);
    		time.appendChild(tr);
        }
        </script>
            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">编辑产品</span>
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
                                    <form method="POST" action="editproduct.php?act=edit&id=<?=$id?>">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">产品名称</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="产品名称" value="<?=$row['name']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">产品介绍</label>
                                            <textarea class="form-control" name="description" row="6"><?=$row['description']?></textarea>
                                        </div>
                                      	<div class="form-group">
                                          	<label for="exampleInputEmail1">产品分类</label>
                                    <div>
                                        <select name="type">
                                            <optgroup label="请选择">
                                              <?php while($row2 = $type->fetch_assoc()){
                                              	if($row2['id'] == $row['type']){
                                              		$selected = "selected";
                                              	}else{
                                              		$selected = "";
                                              	}
  														echo '
                                                <option value="'.$row2['id'].'" '.$selected.'>'.$row2['name'].'</option>';
													}
                                             	?>
                                            </optgroup>
                                        </select>
                                    </div>	
                                      	</div>
                                      	<div class="form-group">
                                          	<label for="exampleInputEmail1">产品服务器</label>
                                          	
                                    <div>
                                        <select name="server">
                                            <optgroup label="请选择">
                                              <?php while($row2 = $server->fetch_assoc()){
                                              	if($row2['id'] == $row['server']){
                                              		$selected = "selected";
                                              	}else{
                                              		$selected = "";
                                              	}
  														echo '
                                                <option value="'.$row2['id'].'" '.$selected.'>'.$row2['name'].'</option>';
													}
                                             	?>
                                            </optgroup>
                                        </select>
                                    </div>	
                                      	</div>
                                        
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="title">周期配置 <button class="btn btn-danger" onclick="AddTimeInput()" type="button"> 添加产品周期</button></div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>名称</th>
                                                <th>费率</th>
                                                <th>开通日数</th>
                                              	<th>备注（根据插件提示填写）</th>
                                            </tr>
                                        </thead>
                                        <tbody id="timetable">
                                        	<?php
                                        	foreach($time as $k => $v){
                                            	echo '<tr>
                                                <td><input type="text" class="form-control" placeholder="周期名称" name="time['.$k.'][name]" value="'.$v['name'].'"></td>
                                                <td><input type="text" class="form-control" placeholder="周期费率" name="time['.$k.'][discount]" value="'.$v['discount'].'"></td>
                                                <td><input type="text" class="form-control" placeholder="开通日数" name="time['.$k.'][day]" value="'.$v['day'].'"></td>
                                                <td><input type="text" class="form-control" placeholder="周期介绍" name="time['.$k.'][remark]" value="'.$v['remark'].'"></td>
                                            </tr>';
                                            }
                                        	?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="title">插件配置</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                	
                                	<?php
                                        if(function_exists($serverinfo['plugin']."_ConfigOption")){
                                        	$function = $serverinfo['plugin']."_ConfigOption";
                                        	$configoption = $function();
                                        	foreach($configoption as $k => $v){
                                        		echo '<div class="form-group">
                                            <label for="exampleInputEmail1">【插件配置】：'.$k.'</label>
                                            <input type="text" class="form-control" name="configoption['.$k.']" placeholder="'.$v.'" maxlength="256" value="'.$row['configoption'][$k].'">
                                        </div>';
                                        	}
                                        }
                                        ?>
                                        
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