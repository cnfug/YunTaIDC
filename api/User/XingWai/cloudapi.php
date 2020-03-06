<?php
include("../../../includes/common.php");
$params = daddslashes($_GET);
$user = $DB->query("SELECT * FROM ytidc_user` WHERE `username`='{$params['userid']}'");

?>