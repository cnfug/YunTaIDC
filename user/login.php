<?php

include("../includes/common.php");

if(!empty($_POST['username']) && !empty($_POST['password'])){
    $username = daddslashes($_POST['username']);
    $password = daddslashes($_POST['password']);
    $result = $DB->query("SELECT * FROM `ytidc_user` WHERE `username`='{$username}' and `password`='{$password}'");
    if($result->num_rows != 1){
        exit('账号密码错误！<a href="./login.php">点我重新登陆</a>');
    }else{
        $_SESSION['ytidc_user'] = $username;
        $_SESSION['ytidc_pass'] = $password;
        @header("Location: ./index.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>后台登陆 - <?=$site['name']?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <!-- CSS Libs -->
    <link rel="stylesheet" type="text/css" href="./lib/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./lib/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="./lib/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="./lib/css/bootstrap-switch.min.css">
    <link rel="stylesheet" type="text/css" href="./lib/css/checkbox3.min.css">
    <link rel="stylesheet" type="text/css" href="./lib/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="./lib/css/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="./lib/css/select2.min.css">
    <!-- CSS App -->
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/themes/flat-blue.css">
</head>

<body class="flat-blue login-page">
    <div class="container">
        <div class="login-box">
            <div>
                <div class="login-form row">
                    <div class="col-sm-12 text-center login-header">
                        <i class="login-logo fa fa-connectdevelop fa-5x"></i>
                        <h4 class="login-title"><?=$site['name']?></h4>
                    </div>
                    <div class="col-sm-12">
                        <div class="login-body">
                            <div class="progress hidden" id="login-progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                    登陆后台...
                                </div>
                            </div>
                            <form method="POST" action="./login.php">
                                <div class="control">
                                    <input name="username" type="text" class="form-control" placeholder="用户账号" value="" />
                                </div>
                                <div class="control">
                                    <input name="password" type="password" class="form-control" placeholder="用户密码" value="" />
                                </div>
                                <div class="login-button text-center">
                                    <input type="submit" class="btn btn-primary" value="登陆">
                                  	<a href="./login.php" class="btn btn-default">注册</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Javascript Libs -->
    <script type="text/javascript" src="./lib/js/jquery.min.js"></script>
    <script type="text/javascript" src="./lib/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./lib/js/Chart.min.js"></script>
    <script type="text/javascript" src="./lib/js/bootstrap-switch.min.js"></script>
    
    <script type="text/javascript" src="./lib/js/jquery.matchHeight-min.js"></script>
    <script type="text/javascript" src="./lib/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="./lib/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="./lib/js/select2.full.min.js"></script>
    <script type="text/javascript" src="./lib/js/ace/ace.js"></script>
    <script type="text/javascript" src="./lib/js/ace/mode-html.js"></script>
    <script type="text/javascript" src="./lib/js/ace/theme-github.js"></script>
    <!-- Javascript -->
    <script type="text/javascript" src="./js/app.js"></script>
</body>

</html>
