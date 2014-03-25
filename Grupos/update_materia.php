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
$OrdenMat = $_POST['OrdenMat'];
$action = $_POST['action'];

if($action == 'ordenarMateria'){
	$cons_update = "UPDATE tbmateriagrupo mg SET mg.OrdenMater = '".$OrdenMat ."'  WHERE idMaterGrupo = '".$idMatGr."'";
	$con->queryx($cons_update, "No se pudo actualizar el orden de la materia.");
	echo "Ordenado con exito.";
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