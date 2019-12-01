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
if($DB->query("SELECT * FROM `ytidc_fenzhan`")->num_rows == 0){
	
}else{
	$siteconf = $DB->query("SELECT * FROM `ytidc_fenzhan` WHERE `domain`='{$domain}'");
	if($siteconf->num_rows == 0){
	  	exit("本站尚未开通！请前往用户后台开通！");
	}else{
	  	$site = $siteconf->fetch_assoc();
	}	
}

?>