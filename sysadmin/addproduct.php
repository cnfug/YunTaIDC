<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
if(!empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['type']) && !empty($_POST['server']) && !empty($_POST['time'])){
  	$name = daddslashes($_POST['name']);
  	$description = daddslashes($_POST['description']);
  	$type = daddslashes($_POST['type']);
  	$server = daddslashes($_POST['server']);
  	$time = daddslashes($_POST['time']);
  	foreach($time as $k => $v){
  		if(!empty($v['name'])){
  			$timearray[$k] = $v;
  		}
  	}
  	$time = json_encode(url_encode($timearray));
  	$DB->query("INSERT INTO `ytidc_product` (`name`, `description`, `type`, `server`, `time`, `configoption`, `status`) VALUES ('{$name}', '{$description}', '{$type}', '{$server}', '{$time}', '', '1')");
  	$newid = $DB->query("select MAX(id) from `ytidc_product`")->fetch_assoc();
  	$newid = $newid['MAX(id)'];
	@header("Location: ./editproduct.php?id={$newid}");
  	exit();
}
$title = "添加产品";
include("./head.php");
$type = $DB->query("SELECT * FROM `ytidc_type` WHERE `status`='1'");
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `status`='1'");
$descriptionhtml = file_get_contents("../templates/".$conf['template']."/user_buy_product_modal.template");
?>
<script>
        var count = 0;

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
  <h1 class="m-n font-thin h3">添加产品</h1>
</div>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">添加产品</div>
        <div class="panel-body">
          <form role="form" action="./addproduct.php" method="POST">
            <div class="form-group">
              <label>产品名称</label>
              <input type="text" name="name" class="form-control" placeholder="产品名称">
            </div>
            <div class="form-group">
              <label>产品介绍</label>
              <textarea class="form-control" name="description"></textarea>
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
              		echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';
              	}
              	?>
              </select>
            </div>
            <div class="form-group">
              <label>产品服务器</label>
              <select name="server" class="form-control m-b">
              	<?php
              	while($row2 = $server->fetch_assoc()){
              		echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';
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
	                </tbody>
	              </table>
	            </div>
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