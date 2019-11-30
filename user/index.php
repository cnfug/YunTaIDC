<?php

include("../includes/common.php");
if(empty($_SESSION['ytidc_user']) && empty($_SESSION['ytidc_pass'])){
  	@header("Location: ./login.php");
     exit;
}else{
  	$username = daddslashes($_SESSION['ytidc_user']);
  	$password = daddslashes($_SESSION['ytidc_pass']);
  	$user = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}' AND `password`='{$password}'");
  	if($user->num_rows != 1){
      	@header("Location: ./login.php");
      	exit;
    }else{
      	$user = $user->fetch_assoc();
    }
}

$servicecount = $DB->query("SELECT * FROM `ytidc_service` WHERE `userid`='{$user['id']}'")->num_rows;
$wordercount = $DB->query("SELECT * FROM `ytidc_worder` WHERE `user`='{$user['id']}'")->num_rows;
$invitecount = $DB->query("SELECT * FROM `ytidc_user` WHERE `invite`='{$user['id']}'")->num_rows;
$notice = $DB->query("SELECT * FROM `ytidc_notice`");
$title = "用户管理";
include("./head.php");
?>
            <div class="container-fluid">
                <div class="side-body padding-top">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <a href="#">
                                <div class="card red summary-inline">
                                    <div class="card-body">
                                        <i class="icon fa fa-inbox fa-4x"></i>
                                        <div class="content">
                                            <div class="title"><?=$servicecount?></div>
                                            <div class="sub-title">在线服务</div>
                                        </div>
                                        <div class="clear-both"></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <a href="#">
                                <div class="card yellow summary-inline">
                                    <div class="card-body">
                                        <i class="icon fa fa-comments fa-4x"></i>
                                        <div class="content">
                                            <div class="title"><?=$wordercount?></div>
                                            <div class="sub-title">提交工单</div>
                                        </div>
                                        <div class="clear-both"></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <a href="#">
                                <div class="card green summary-inline">
                                    <div class="card-body">
                                        <i class="icon fa fa-bitcoin fa-4x"></i>
                                        <div class="content">
                                            <div class="title"><?=$user['money']?></div>
                                            <div class="sub-title">用户余额</div>
                                        </div>
                                        <div class="clear-both"></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <a href="#">
                                <div class="card blue summary-inline">
                                    <div class="card-body">
                                        <i class="icon fa fa-share-alt fa-4x"></i>
                                        <div class="content">
                                            <div class="title"><?=$invitecount?></div>
                                            <div class="sub-title">邀请用户</div>
                                        </div>
                                        <div class="clear-both"></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row  no-margin-bottom">
                        <div class="col-sm-6 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="thumbnail no-margin-bottom">
                                        <img src="./img/thumbnails/picjumbo.com_IMG_4566.jpg" class="img-responsive">
                                        <div class="caption">
                                            <h3 id="thumbnail-label">充值余额<a class="anchorjs-link" href="#thumbnail-label"><span class="anchorjs-icon"></span></a></h3>
                                            <p>支持三网支付进行充值余额，充值余额进行产品购买。</p>
                                            <p><a href="./pay.php" class="btn btn-primary" role="button">前往充值</a> <a href="./buy.php" class="btn btn-default" role="button">开通服务</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="thumbnail no-margin-bottom">
                                        <img src="./img/thumbnails/picjumbo.com_IMG_3241.jpg" class="img-responsive">
                                        <div class="caption">
                                            <h3 id="thumbnail-label">邀请用户<a class="anchorjs-link" href="#thumbnail-label"><span class="anchorjs-icon"></span></a></h3>
                                            <p>邀请用户通过你的链接注册，赚取用户提成。</p>
                                            <p><a href="./invite.php" class="btn btn-success" role="button">获取链接</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="card card-success">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="title"><i class="fa fa-comments-o"></i> 最新公告</div>
                                    </div>
                                    <div class="clear-both"></div>
                                </div>
                                <div class="card-body no-padding">
                                    <ul class="message-list">
                                      <?php
  										while($row2 = $notice->fetch_assoc()){
                                          	echo '
                                        <a href="./noticedetail.php?id='.$row2['id'].'">
                                            <li>
                                                <div class="message-block">
                                                    <div><span class="username">'.$row2['title'].'</span>
                                                    </div>
                                                    <div class="message">'.$row2['date'].'</div>
                                                </div>
                                            </li>
                                        </a>';
                                        }
                                      ?>
                                    </ul>
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