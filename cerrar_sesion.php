<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

	//Coloca el tiempo de vida de la cookie en negativo 
    if (isset($_COOKIE[session_name()])) 
    { 
        setcookie(session_name(), '', time() - 42000, '/'); 
    } 
    //Obtiene todas las variables de sesión y las almacena en un array 
    $_SESSION = array(); 
    //Libera todas las variables de sesión 
    session_unset(); 
    //Cierra la sesión 
    session_destroy(); 
	
	header("location: ../index.php");

?>