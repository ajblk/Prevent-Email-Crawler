<?php
/****** Site Constants  *****************/

$liveFlag = 0;

## For checking the site url 
if($_SERVER['HTTP_HOST']=='localhost')
{
	$liveFlag = 0;	

	//Databse Connectivity 

	define('DB_SERVER', 'localhost'); 
	define('DB_SERVER_USERNAME', 'localuser');
	define('DB_SERVER_PASSWORD', 'fontspwd');
	define('DB_DATABASE', 'fontsdb');	
}
else
{
	$liveFlag = 1;

	// Databse Connectivity  

	define('DB_SERVER', 'liveserver');
	define('DB_SERVER_USERNAME', 'liveserveruser');
	define('DB_SERVER_PASSWORD', 'liveserverpassword');
	define('DB_DATABASE', 'liveserverfontsdb');
	
}


// New MYSQLI Connection Technique	

$mysqli = new mysqli(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);

if ($mysqli->connect_errno) {
    echo "Failed connect to MySQL: " . mysqli_connect_error();
}

$mysqli->set_charset('utf8');

	
	
	
?>