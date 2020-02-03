<?php

include("../includes/common.php");
if(!empty($_POST['username']) && !empty($_POST['password'])){
    $username = daddslashes($_POST['username']);
    $password = daddslashes($_POST['password']);
    if($username == $conf['admin'] && $password == $conf['password']){
      	$_SESSION['adminlogin'] = md5($username.$password.$conf['domain']);
      	@header("Location: ./index.php");
      	exit;
    }else{
      	exit('账号密码错误！<a href="./login.php">点此重新登陆</a>');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>仪表盘 | 云塔云中心v1.0</title>
  <meta name="description" content="app, web app, responsive, responsive layout, admin, admin panel, admin dashboard, flat, flat ui, ui kit, AngularJS, ui route, charts, widgets, components" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="css/animate.css" type="text/css" />
  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="css/simple-line-icons.css" type="text/css" />
  <link rel="stylesheet" href="css/font.css" type="text/css" />
  <link rel="stylesheet" href="css/app.css" type="text/css" />
</head>
<body>
  <div class="app app-header-fixed  ">


    <div class="container w-xxl w-auto-xs" ng-controller="SigninFormController" ng-init="app.settings.container = false;">
      <span class="navbar-brand block m-t">云塔IDC财务管理系统v2.2</span>
      <div class="m-b-lg">
        <div class="wrapper text-center">
          <strong>您将在这里登陆,请输入超级管理员的帐号信息!</strong>
        </div>
        <form name="form" class="form-validation" method="post" action="./login.php">
          <div class="text-danger wrapper text-center" ng-show="authError">
    
          </div>
          <div class="list-group list-group-sm swaplogin">
            <div class="list-group-item">
              <input type="text" name="username" placeholder="Username" class="form-control no-border" ng-model="user.email" required="">
            </div>
            <div class="list-group-item">
               <input type="password" name="password" placeholder="Password" class="form-control no-border" ng-model="user.password" required="">
            </div>
          </div>
          <button type="submit" class="btn btn-lg btn-primary btn-block">现在登录</button>
        </form>
      </div>
      <div class="text-center">
        <p>
      <small class="text-muted">云塔IDC系统<br>© 2019~2020</small>
    </p>
      </div>
    </div>
    
    
    </div>
  <!-- jQuery -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/jquery/bootstrap.js"></script>
  <script type="text/javascript">
    +function ($) {
      $(function(){
        // class
        $(document).on('click', '[data-toggle^="class"]', function(e){
          e && e.preventDefault();
          console.log('abc');
          var $this = $(e.target), $class , $target, $tmp, $classes, $targets;
          !$this.data('toggle') && ($this = $this.closest('[data-toggle^="class"]'));
          $class = $this.data()['toggle'];
          $target = $this.data('target') || $this.attr('href');
          $class && ($tmp = $class.split(':')[1]) && ($classes = $tmp.split(','));
          $target && ($targets = $target.split(','));
          $classes && $classes.length && $.each($targets, function( index, value ) {
            if ( $classes[index].indexOf( '*' ) !== -1 ) {
              var patt = new RegExp( '\\s' + 
                  $classes[index].
                    replace( /\*/g, '[A-Za-z0-9-_]+' ).
                    split( ' ' ).
                    join( '\\s|\\s' ) + 
                  '\\s', 'g' );
              $($this).each( function ( i, it ) {
                var cn = ' ' + it.className + ' ';
                while ( patt.test( cn ) ) {
                  cn = cn.replace( patt, ' ' );
                }
                it.className = $.trim( cn );
              });
            }
            ($targets[index] !='#') && $($targets[index]).toggleClass($classes[index]) || $this.toggleClass($classes[index]);
          });
          $this.toggleClass('active');
        });

        // collapse nav
        $(document).on('click', 'nav a', function (e) {
          var $this = $(e.target), $active;
          $this.is('a') || ($this = $this.closest('a'));
          
          $active = $this.parent().siblings( ".active" );
          $active && $active.toggleClass('active').find('> ul:visible').slideUp(200);
          
          ($this.parent().hasClass('active') && $this.next().slideUp(200)) || $this.next().slideDown(200);
          $this.parent().toggleClass('active');
          
          $this.next().is('ul') && e.preventDefault();

          setTimeout(function(){ $(document).trigger('updateNav'); }, 300);      
        });
      });
    }(jQuery);
  </script>
</body>
</html>