<?php

include("../includes/common.php");
$session = md5($conf['admin'].$conf['password'].$conf['domain']);
if(empty($_SESSION['adminlogin']) || $_SESSION['adminlogin'] != $session){
  	@header("Location: ./login.php");
  	exit;
}
$user = $DB->query("SELECT * FROM `ytidc_user`")->num_rows;
$service = $DB->query("SELECT * FROM `ytidc_service`")->num_rows;
$site = $DB->query("SELECT * FROM `ytidc_fenzhan`")->num_rows;
$worder = $DB->query("SELECT * FROM `ytidc_worder`")->num_rows;
$title = "管理后台";
if($conf['crondate'] == date('Y-m-d')){
	$cronstatus = "正常";
}else{
	$cronstatus = "异常";
}
include("./head.php");
?>

<div class="hbox hbox-auto-xs hbox-auto-sm" ng-init="
    app.settings.asideFolded = false; 
    app.settings.asideDock = false;
  ">
  <!-- main -->
  <div class="col">
    <!-- main header -->
    <div class="bg-light lter b-b wrapper-md">
      <div class="row">
        <div class="col-sm-6 col-xs-12">
          <h1 class="m-n font-thin h3 text-black">仪表盘</h1>
          <small class="text-muted">欢迎使用云塔v2.2</small>
        </div>
      </div>
    </div>
    <!-- / main header -->
    <div class="wrapper-md" ng-controller="FlotChartDemoCtrl">
      <!-- stats -->
      <div class="row">
        <div class="col-md-5">
          <div class="row row-sm text-center">
            <div class="col-xs-6">
              <div class="panel padder-v item">
                <div class="h1 text-info font-thin h1"><?=$user?></div>
                <span class="text-muted text-xs">注册用户</span>
                <div class="top text-right w-full">
                  <i class="fa fa-caret-down text-warning m-r-sm"></i>
                </div>
              </div>
            </div>
            <div class="col-xs-6">
              <a href class="block panel padder-v bg-primary item">
                <span class="text-white font-thin h1 block"><?=$service?></span>
                <span class="text-muted text-xs">在线服务</span>
                <span class="bottom text-right w-full">
                  <i class="fa fa-cloud-upload text-muted m-r-sm"></i>
                </span>
              </a>
            </div>
            <div class="col-xs-6">
              <a href class="block panel padder-v bg-info item">
                <span class="text-white font-thin h1 block"><?=$site?></span>
                <span class="text-muted text-xs">开通分站</span>
                <span class="top text-left">
                  <i class="fa fa-caret-up text-warning m-l-sm"></i>
                </span>
              </a>
            </div>
            <div class="col-xs-6">
              <div class="panel padder-v item">
                <div class="font-thin h1"><?=$worder?></div>
                <span class="text-muted text-xs">开启工单</span>
                <div class="bottom text-left">
                  <i class="fa fa-caret-up text-warning m-l-sm"></i>
                </div>
              </div>
            </div>
            <div class="col-xs-12 m-b-md">
              <div class="r bg-light dker item hbox no-border">
                <div class="col w-xs v-middle hidden-md">
                  <div ng-init="data1=[60,40]" ui-jq="sparkline" ui-options="{{data1}}, {type:'pie', height:40, sliceColors:['{{app.color.warning}}','#fff']}" class="sparkline inline"></div>
                </div>
                <div class="col dk padder-v r-r">
                  <div class="text-primary-dk font-thin h1"><span><?=$cronstatus?></span></div>
                  <span class="text-muted text-xs">Cron工作</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="panel wrapper">
            <label class="i-switch bg-warning pull-right" ng-init="showSpline=true">
              <input type="checkbox" ng-model="showSpline">
              <i></i>
            </label>
            <h4 class="font-thin m-t-none m-b text-muted">最近销售情况</h4>
            <div ui-jq="plot" ui-refresh="showSpline"style="height:246px" >
            </div>
          </div>
        </div>
      </div>
      <!-- / stats -->

      <!-- tasks -->
      <div class="row">
        <div class="col-md-12">
          <div class="panel no-border">
            <div class="panel-heading wrapper b-b b-light">
              <h4 class="font-thin m-t-none m-b-none text-muted">最新消息</h4>              
            </div>
            <ul class="list-group list-group-lg m-b-none">
            	<?php
            	
            	if($conf['cloud_get_news'] == 1){
            		$news = file_get_contents("http://yzx.mobi/news.json");
            		$news = json_decode($news, true);
            		foreach($news as $t => $url){
            			echo '
              <li class="list-group-item">
                <span class="pull-right label bg-success inline m-t-sm">News</span>
                <a href="'.$url.'" target="_blank">'.$t.'</a>
              </li>';
            		}
            	}else{
            		echo '<li class="list-group-item">
                <span class="pull-right label bg-primary inline m-t-sm">Offline</span>
                <a>您关闭了接收最新消息的功能！</a>
              </li>';
            	}
            	?>
            </ul>
            <div class="panel-footer">
              <span class="pull-right badge badge-bg m-t-xs">More</span>
              <a class="btn btn-primary btn-addon btn-sm" href="https://jq.qq.com/?_wv=1027&k=5od4Wkj"><i class="fa fa-plus"></i>更多消息</a>
            </div>
          </div>
        </div>
      </div>
      <!-- / tasks -->
    </div>
  </div>
  <!-- / main -->
</div>
<?php
include("./foot.php");
?>