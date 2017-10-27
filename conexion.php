<?php

function Conectar(){
	$hostname;
	$database;
	$login;
	$pass;

	if ($_SERVER['HTTP_HOST']=="lalvirtual.com" or $_SERVER['HTTP_HOST']=="www.lalvirtual.com"){
		$hostname="localhost";
		$database="lalvirtu_myvc";
		$login="lalvirtu_admin";
		$pass="exalted";
	}else{
		$hostname="localhost";
		$database="lalvirtu_myvc";
		$login="root";
		$pass="";			
	}

	
	$con=mysqli_connect($hostname, $login, $pass, $database) or die("Problemas con la conexión al servidor");
	
	mysqli_set_charset($con,"utf8");
	
	return $con;
        
    mysql_close($con);
}


?>