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

	
	$con=mysql_connect($hostname, $login, $pass) or die("Problemas con la conexión al servidor");
	mysql_query("SET NAMES 'utf8'");
	mysql_select_db($database, $con)or die ("No se conecta a la DB");
	
	return $con;
        
    mysql_close($con);
}


?>