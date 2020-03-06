<?php

include("../includes/common.php");
if(empty($_SESSION['ytidc_user']) || empty($_SESSION['ytidc_token'])){
  	@header("Location: ./login.php");
     exit;
}else{
  	$username = daddslashes($_SESSION['ytidc_user']);
  	$userkey = daddslashes($_SESSION['ytidc_token']);
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}'");
  	if($user->num_rows != 1){
      	@header("Location: ./login.php");
      	exit;
    }else{
    	$user = $user->fetch_assoc();
    	$site = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `user`='{$user['id']}'");
    	if($site->num_rows != 1){
    		exit('该用户尚未开通分站！<a href="./login.php">点此重新登陆</a>');
    	}else{
    		$site = $site->fetch_assoc();
    	}
      	$userkey1 = md5($_SERVER['HTTP_HOST'].$user['password']);
      	if($userkey != $userkey1){
      		@header("Location: ./login.php");
      		exit;
      	}
    }
}
$usernum = $DB->query("SELECT * FROM `ytidc_user` WHERE `site`='{$site['id']}'")->num_rows;
$order = $DB->query("SELECT * FROM `ytidc_order` WHERE `user`='{$user['id']}'")->num_rows;
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
          <small class="text-muted">欢迎使用云塔v2.3</small>
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
                <div class="h1 text-info font-thin h1"><?=$usernum?></div>
                <span class="text-muted text-xs">旗下用户</span>
                <div class="top text-right w-full">
                  <i class="fa fa-caret-down text-warning m-r-sm"></i>
                </div>
              </div>
            </div>
            <div class="col-xs-6">
              <a href class="block panel padder-v bg-primary item">
                <span class="text-white font-thin h1 block"><?=$order?></span>
                <span class="text-muted text-xs">您的订单</span>
                <span class="bottom text-right w-full">
                  <i class="fa fa-cloud-upload text-muted m-r-sm"></i>
                </span>
              </a>
            </div>
            <div class="col-xs-12 m-b-md">
              <div class="r bg-light dker item hbox no-border">
                <div class="col dk padder-v r-r">
                  <div class="text-primary-dk font-thin h1"><span><?=$user['money']?>元</span></div>
                  <span class="text-muted text-xs">您的余额</span>
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
            <h4 class="font-thin m-t-none m-b text-muted">在线服务</h4>
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
            <p><?=$site['notice']?></p>
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