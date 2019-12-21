<!DOCTYPE html>
<html>

<head>
    <title><?=$title?> - 云塔IDC系统</title>
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

<body class="flat-blue">
    <div class="app-container">
        <div class="row content-container">
            <nav class="navbar navbar-default navbar-fixed-top navbar-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-expand-toggle">
                            <i class="fa fa-bars icon"></i>
                        </button>
                        <ol class="breadcrumb navbar-breadcrumb">
                            <li class="active">管理后台</li>
                        </ol>
                        <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                            <i class="fa fa-th icon"></i>
                        </button>
                    </div>
                </div>
            </nav>
            <div class="side-menu sidebar-inverse">
                <nav class="navbar navbar-default" role="navigation">
                    <div class="side-menu-container">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="#">
                                <div class="icon fa fa-paper-plane"></div>
                                <div class="title">云塔IDC系统 V.2.0</div>
                            </a>
                            <button type="button" class="navbar-expand-toggle pull-right visible-xs">
                                <i class="fa fa-times icon"></i>
                            </button>
                        </div>
                        <ul class="nav navbar-nav">
                            <li class="active">
                                <a href="index.php">
                                    <span class="icon fa fa-tachometer"></span><span class="title">管理后台</span>
                                </a>
                            </li>
                            <li class="panel panel-default dropdown">
                                <a data-toggle="collapse" href="#dropdown-element">
                                    <span class="icon fa fa-desktop"></span><span class="title">产品服务</span>
                                </a>
                                <!-- Dropdown level 1 -->
                                <div id="dropdown-element" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                            <li><a href="type.php">产品组管理</a>
                                            </li>
                                            <li><a href="product.php">产品管理</a>
                                            </li>
                                            <li><a href="server.php">服务器管理</a>
                                            </li>
                                            <li><a href="service.php">在线服务管理</a>
                                            </li>
                                            <li><a href="worder.php">工单管理</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="panel panel-default dropdown">
                                <a data-toggle="collapse" href="#dropdown-table">
                                    <span class="icon fa fa-table"></span><span class="title">用户代理</span>
                                </a>
                                <!-- Dropdown level 1 -->
                                <div id="dropdown-table" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                            <li><a href="user.php">用户管理</a>
                                            </li>
                                            <li><a href="site.php">分站管理</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="panel panel-default dropdown">
                                <a data-toggle="collapse" href="#dropdown-form">
                                    <span class="icon fa fa-file-text-o"></span><span class="title">价格财务</span>
                                </a>
                                <!-- Dropdown level 1 -->
                                <div id="dropdown-form" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                            <li><a href="price.php">价格管理</a>
                                            </li>
                                            <li><a href="code.php">优惠码管理</a>
                                            </li>
                                            <li><a href="order.php">交易记录</a>
                                            </li>
                                            <li><a href="epay.php">易支付管理</a>
                                            </li>
                                            <li><a href="invite.php">用户邀请记录</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="panel panel-default dropdown">
                                <a data-toggle="collapse" href="#dropdown-site">
                                    <span class="icon fa fa-circle"></span><span class="title">网站管理</span>
                                </a>
                                <!-- Dropdown level 1 -->
                                <div id="dropdown-site" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                            <li><a href="notice.php">公告管理</a>
                                            </li>
                                            <li><a href="config.php">编辑资料</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="http://www.yunta.cc/">
                                    <span class="icon fa fa-thumbs-o-up"></span><span class="title">前往官网</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                </nav>
            </div>
            <!-- Main Content -->