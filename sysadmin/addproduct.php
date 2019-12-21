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
  	$time = json_encode(url_encode($time));
  	$DB->query("INSERT INTO `ytidc_product` (`name`, `description`, `type`, `server`, `time`, `configoption`, `status`) VALUES ('{$name}', '{$description}', '{$type}', '{$server}', '{$time}', '', '1')");
  	@header("Location: ./msg.php?msg=添加产品成功！");
  	exit($DB->error);
}
$title = "添加产品";
include("./head.php");
$type = $DB->query("SELECT * FROM `ytidc_type` WHERE `status`='1'");
$server = $DB->query("SELECT * FROM `ytidc_server` WHERE `status`='1'");
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
            <div class="container-fluid">
                <div class="side-body">
                    <div class="page-title">
                        <span class="title">添加产品</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="title">添加内容</div>
                                    </div>
                                </div>
                                <form method="POST" action="addproduct.php">
                                <div class="card-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">产品名称</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="产品名称">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">产品介绍</label>
                                            <textarea class="form-control" name="description" row="6"></textarea>
                                        </div>
                                      	<div class="form-group">
                                          	<label for="exampleInputEmail1">产品分类</label>
                                    <div>
                                        <select name="type">
                                            <optgroup label="请选择">
                                              <?php while($row2 = $type->fetch_assoc()){
  														echo '
                                                <option value="'.$row2['id'].'">'.$row2['name'].'</option>';
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
  														echo '
                                                <option value="'.$row2['id'].'">'.$row2['name'].'</option>';
													}
                                             	?>
                                            </optgroup>
                                        </select>
                                    </div>	
                                      	</div>
                                </div>
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
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-default">添加</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<?php

include("./foot.php");

?>