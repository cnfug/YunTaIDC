<?php
session_start();
if(session_destroy()){
	@header("Location: /index.php");
	exit;
}else{
	unset($_SESSION);
	@header("Location: /index.php");
	exit;
}

?>