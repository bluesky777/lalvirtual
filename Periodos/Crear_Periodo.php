<?php
require_once("../verificar_sesion.php");
require_once("../php/clsConexion.php");
// ==================================================================
//
// [ Section description goes here ... ]
//
// ------------------------------------------------------------------

$con = new clsConexion();

$con->Conectar();

$slYear = $_GET['slYear'];
$slPeriodo = $_GET['slPeriodo'];
$slActual = $_GET['slActual'];


$cons_insert = "INSERT INTO `tbperiodos`(`Periodo`, `ActualPer`, `Year`) 
	VALUES ('".$slPeriodo."','".$slActual."','".$slYear."')";
$con->queryx($cons_insert, "No se pudo crear el periodo.");

echo "PERIODO CREADO CON ÉXITO.";


?>