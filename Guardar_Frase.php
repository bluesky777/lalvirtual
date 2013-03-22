<?php

require_once('conexion.php');
require_once('verificar_sesion.php');

$con=Conectar();

if($_POST['txtOpe']=="Nuevo"){
	$sql="insert into tbfrases(Frase, TipoFrase, YearFrase) values('".$_POST['txtFrase']."','".$_POST['txtTipo']."','".$_SESSION['Year']."');";

	$qSql=mysql_query($sql, $con) or die ("No se pudo ingresar la frase. ".mysql_error());
	echo "Frase guardada con éxito";
} else {
	
	$sqlUd="Update tbfrases set Frase='".$_POST['txtFrase']."' TipoFrase='".$_POST['txtTipo']."' YearFrase=".$_SESSION['Year'];
}
?>