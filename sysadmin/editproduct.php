<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$id = daddslashes($_GET['id']);
if(empty($id)){
  	@header("Location: ./product.php");
  	exit;
}
$act = daddslashes($_GET['act']);
if($act == "del"){
  	$DB->query("DELETE FROM `ytidc_product` WHERE `id`='{$id}'");
  	@header("Location: ./product.php");
  	exit;
}
if($act == "edit"){
  	foreach($_POST as $k => $v){
      	$value = daddslashes($v);
      	$DB->query("UPDATE `ytidc_product` SET `{$k}`='{$value}' WHERE `id`='{$id}'");
    }
  	$configoption = json_encode(daddslashes($_POST['configoption']));
  	$DB->query("UPDATE `ytidc_product` SET `configoption`='{$configoption}' WHERE `id`='{$id}'");
  	$time = daddslashes($_POST['time']);
  	foreach($time as $k => $v){
  		if(!empty($v['name'])){
  			$timearray[$k] = $v;
  		}
  	}
  	$time = json_encode(url_encode($timearray));
  	$DB->query("UPDATE `ytidc_product` SET `time`='{$time}' WHERE `id`='{$id}'");
  	@header("Location: ./editproduct.php?id={$id}");
  	exit;
}
$row = $DB->query("SELECT * FROM `ytidc_product` WHERE `id`='{$id}'")->fetch_assoc();
$row['configoption'] = json_decode($row['configoption'], 1);
$time = json_decode(url_decode($row['time']),true);
$timecount = count($time);
$type = $DB->query("SELECT * FROM `ytidc_type` WHERE `status`='1'");
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `status`='1'");
$serverinfo = $DB->query("SELECT * FROM `ytidc_server` WHERE `id`='{$row['server']}'")->fetch_assoc();
$descriptionhtml = file_get_contents("../templates/".$conf['template']."/user_buy_product_modal.template");
$plugin = "../plugins/server/".$serverinfo['plugin']."/main.php";
if(!file_exists($plugin) || !is_file($plugin)){
	@header("Location: ./msg.php?msg=服务器插件不存在");
	exit;
}
include($plugin);
include("./head.php");
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
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">编辑产品</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">编辑产品</div>
        <div class="panel-body">
          <form role="form" action="./editproduct.php?act=edit&id=<?=$id?>" method="POST">
            <div class="form-group">
              <label>产品名称</label>
              <input type="text" name="name" class="form-control" placeholder="产品名称" value="<?=$row['name']?>">
            </div>
            <div class="form-group">
              <label>产品介绍</label>
              <textarea class="form-control" name="description"><?=$row['description']?></textarea>
            </div>
            <div class="form-group">
              <label>模板推荐产品介绍</label>
              <textarea class="form-control" disabled=""><?=$descriptionhtml?></textarea>
            </div>
            <div class="form-group">
              <label>产品分类</label>
              <select name="type" class="form-control m-b">
              	<?php
              	while($row2 = $type->fetch_assoc()){
              		if($row2['id'] == $row['tyoe']){
              			echo '<option value="'.$row2['id'].'" selected>'.$row2['name'].'</option>';
              		}else{
              			echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';
              		}
              	}
              	?>
              </select>
            </div>
            <div class="form-group">
              <label>产品服务器</label>
              <select name="server" class="form-control m-b">
              	<?php
              	while($row2 = $server->fetch_assoc()){
              		if($row2['id'] == $row['server']){
              			echo '<option value="'.$row2['id'].'" selected>'.$row2['name'].'</option>';
              		}else{
              			echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';
              		}
              	}
              	?>
              </select>
            </div>
            <div class="form-group">
              <label>产品周期【留空名称为删除】<button class="btn btn-small btn-xs btn-primary" onclick="AddTimeInput()" type="button">添加周期</button></label>
	            <div class="table-responsive">
	              <table class="table table-striped b-t b-light">
	                <thead>
	                  <tr>
	                    <th>周期名称</th>
	                    <th>周期费率</th>
	                    <th>开通天数</th>
	                    <th>周期备注</th>
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
            <?php
                                        if(function_exists($serverinfo['plugin']."_ConfigOption")){
                                        	$function = $serverinfo['plugin']."_ConfigOption";
                                        	$configoption = $function();
                                        	foreach($configoption as $k => $v){
                                        		echo '<div class="form-group">
                                            <label>【插件配置】：'.$k.'</label>
                                            <input type="text" class="form-control" name="configoption['.$k.']" placeholder="'.$v.'" maxlength="256" value="'.$row['configoption'][$k].'">
                                        </div>';
                                        	}
                                        }
            ?>
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