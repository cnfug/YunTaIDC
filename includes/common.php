<?php
//error_reporting(0);
define("CACHE_FILE", 0);
define("IN_CRONLITE", true);
define("SYSTEM_ROOT", dirname(__FILE__) . "/");
define("ROOT", dirname(SYSTEM_ROOT) . "/");
define("ROOT_PATH", dirname(SYSTEM_ROOT) . "/");
date_default_timezone_set("PRC");
define("SYS_KEY", "daishua_key");
define("CC_Defender", 1);
$date = date("Y-m-d H:i:s");
$domain = $_SERVER['HTTP_HOST'];
session_start();
$scriptpath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$siteurl = ($_SERVER['SERVER_PORT']==443 ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $sitepath . '/';
if(!file_exists(ROOT."install/install.lock")){
	exit('目前检测系统还没安装，如果已经安装请手动建立install.lock到install目录！如未安装请访问 '.$_SERVER['HTTP_HOST'].'/install 进行安装！');
}
if(file_exists(ROOT."install/index.php") || file_exists(ROOT."install/install.sql")){
	exit('检测到您还没有删除安装的页面，可能会造成无法估计的损失，请前往install目录删除index.php以及install.sql');
}/*
if(file_exists(ROOT."install/update.sql") || file_exists(ROOT."install/update.php")){
	exit('检测到您还没有删除更新的页面，可能会造成无法估计的损失，请前往install目录删除update.php以及update.sql');
}*/
if(is_file(SYSTEM_ROOT.'360safe/360webscan.php')){//360网站卫士
    require_once(SYSTEM_ROOT.'360safe/360webscan.php');
}
include_once(SYSTEM_ROOT.'security.php');
require(ROOT."config.php");
if (!$dbconfig["user"] || !$dbconfig["pass"] || !$dbconfig["name"]) {
	header("Content-type:text/html;charset=utf-8");
	echo "你还没安装！请先前往安装";
	exit(0);
}
$DB = new Mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['pass'], $dbconfig['name'], $dbconfig['port']);
$config = $DB->query("SELECT * FROM `ytidc_config`");
while($crow = $config->fetch_assoc()){
  	$conf[$crow['k']] = $crow['v'];
}
include SYSTEM_ROOT."function.php";
include SYSTEM_ROOT."template.php";
if(ismobile()){
	$template_name = $conf['template_mobile'];
}else{
	$template_name = $conf['template'];
}
$siteconf = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `domain`='{$domain}'");
if($siteconf->num_rows == 0){
  	$site = array(
  		'id' => '0',
  		'title' => $conf['mainsite_title'],
  		'subtitle' => $conf['mainsite_subtitle'],
  		'description' => $conf['mainsite_description'],
  		'keywords' => $conf['mainsite_keywords'],
  		'status' => 1,
  		'user' => 0,
  	);
}else{
  	$site = $siteconf->fetch_assoc();
}	

?>