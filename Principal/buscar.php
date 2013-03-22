<?php
require_once("../verificar_sesion.php");
require_once("../php/clsConexion.php");

$Cn = new clsConexion();
$Cn->Conectar();

if(isset($_GET['term'])){

	$qSqlPeo = $Cn->BusPeople( $_GET['term'] );

	if(mysql_num_rows($qSqlPeo)==0){
	    echo "Sin alumnos matriculados";
	    die();
	}
	$r = array();
	while($row=mysql_fetch_assoc($qSqlPeo)){
		$r[] = $row;
	}
	echo json_encode( $r );
	die();
}

?>