<?php
require_once('conexion.php');
require_once('verificar_sesion.php');

$con=Conectar();

$sql="UPDATE `lalvirtu_myvc`.`tbusuarios` SET `PassUsu`='". $_POST['txtPass'] ."', `CifradoUsu`=1 WHERE `idUsu`='". $_SESSION['idUsuar'] ."';

";

//echo $sql;

$qsql=mysql_query($sql, $con) or die("No se guarda jeje. " .mysql_error().$sql);
if(!$qsql){
	echo "Error al guardar, reintentelo";
} else {
	echo "Exitoso";
}

?>