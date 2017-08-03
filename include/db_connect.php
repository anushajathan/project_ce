<?php
function getConn() {	
	$database="your_database_name";
	$hostname='your_hosting_name';
	$username='username';
	$password='password'; 
	
	$connection = mysqli_connect($hostname,$username, $password) OR DIE ('Unable to connect to database! Please try again later.');
	mysqli_select_db($connection,$database);
	return $connection;
	
}
define("tbl_ce_users", "ce_users");
define("tbl_admin_details", "admin_details");

?>