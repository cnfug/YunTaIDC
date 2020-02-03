<?php

//最新数据库Class，使用mysqli方法
class DB {
	var $link = null;
	function __construct($db_host, $db_user, $db_pass, $db_pass, $db_port){
		$this->link = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);
		if (!$this->link) die('Connect Error (' . mysqli_connect_errno() . ') '.mysqli_connect_error());
		mysqli_query($this->link,"set sql_mode = ''");
		 //字符转换，读库
		mysqli_query($this->link,"set character set 'utf8'");
		//写库
		mysqli_query($this->link,"set names 'utf8'");
 
		return true;
	}
	
	function query($q){
		return mysqli_query($this->link, $q);
	}
	
	function fetch($q){
		return mysqli_fetch_assoc($q);
	}
	
	function insert($q){
		if(mysqli_query($this->link, $q)){
			return mysqli_insert_id($this->link);
		}else{
			return false;
		}
	}
	
	function get_row($q){
		$result = mysqli_query($this->link, $q);
		return mysqli_fetch_assoc($result);
	}
	
	function num_rows($q){
		$result = mysqli_query($this->link, $q);
		if($result){
			return mysqli_num_rows($result);
		}else{
			return false;
		}
	}
}

?>