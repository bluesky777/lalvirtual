<?php

function Conectar(){
/*	$hostname="localhost";
	$database="lalvirtu_myvc";
	$login="lalvirtu_admin";
	$pass="exalted";
	*/
	$hostname="localhost";
	$database="lalvirtu_myvc";
	$login="root";
	$pass="123456";
	
	$con=mysql_connect($hostname, $login, $pass) or die("Problemas con la conexión al servidor");
	
	mysql_select_db($database, $con)or die ("No se conecta a la db");
	
	return $con;	
}


?>