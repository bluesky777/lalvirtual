<?php
require_once("../verificar_sesion.php");
require_once("../php/clsConexion.php");
// ==================================================================
//
// Depemdoendo del roll-update, se hará un guardado en la BD
//
// ------------------------------------------------------------------

$con = new clsConexion();

$con->Conectar();


$idMatGr = $_POST['idMatGr'];
$action = $_POST['action'];

if($action == 'ordenarMateria'){
	$OrdenMat = $_POST['OrdenMat'];
	$cons_update = "UPDATE tbmateriagrupo mg SET mg.OrdenMater = '".$OrdenMat ."'  WHERE idMaterGrupo = '".$idMatGr."'";
	$con->queryx($cons_update, "No se pudo actualizar el orden de la materia.");
	echo "Ordenado con exito.";
}


if($action == 'cambiarCredito'){
	$CredMat = $_POST['CredMat'];
	$cons_update = "UPDATE tbmateriagrupo mg SET mg.CreditosMater = '".$CredMat ."'  WHERE idMaterGrupo = '".$idMatGr."'";
	$con->queryx($cons_update, "No se pudo guardar el credito de la materia.");
	echo "Creditos cambiados exitosamente.";
}


/*
function getRequest(){ 
	$query_str = file_get_contents("php://input"); 
	$array = array(); 
	parse_str($query_str, $array); 
	return $array;
}
*/

?>