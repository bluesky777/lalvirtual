<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();

if ($_POST['Oper']==0){
	$sqlVi="update tbbitfinprofesor set VistoAdmin=1 where idBitFinP=".$_POST['idBitFinP'];
	$qSqlVi=mysql_query($sqlVi, $con) or die("No se cambio el estado a leído. ".mysql_error().". ".$sqlVi);
	
	echo "Visto";
	
} else {
	echo "ahora no";
}

?>