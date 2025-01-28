<?php
    $hostname="172.16.1.81";
    $username="si"; 
    $password="snib-2019"; 
    $db_name="snibmx"; 
    
	$mysqli = new mysqli($hostname, $username, $password, $db_name);
	if ($mysqli->connect_errno) {
    echo "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
 
?>