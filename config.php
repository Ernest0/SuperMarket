<?php
########## MySql details (Replace with yours) #############
$username = 'root'; //mysql username
$password = ''; //mysql password
$hostname = 'localhost'; //hostname
$databasename = 'abarrotes_santi'; //databasename

//connect to database
//$mysqli = new mysqli($hostname, $username, $password, $databasename);

function dbConnect (){
 	$conn =	null;
	try {
	   	$conn = new PDO('mysql:host='.$GLOBALS['hostname'].';dbname='.$GLOBALS['databasename'], $GLOBALS['username'], $GLOBALS['password']);
		//echo 'Connected succesfully.<br>';
	}
	catch (PDOException $e) {
		echo '<p>Cannot connect to database !!</p>';
		echo '<p>'.$e.'</p>';
	    exit;
	}

	return $conn;
 }

function dbmsqli(){

	// Create connection
	$conn = mysqli_connect($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['databasename']);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	return $conn;
}

?>