<!DOCTYPE html>
<html>

<head>
    <title><?=$title?> - 分站后台管理</title>
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
                                <div class="title">分站管理系统 V1.0</div>
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
                            <li>
                                <a href="../">
                                    <span class="icon fa fa-thumbs-o-up"></span><span class="title">前往前台</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                </nav>
            </div>
            <!-- Main Content -->