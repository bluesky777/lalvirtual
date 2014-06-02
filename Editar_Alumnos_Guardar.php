<?php
require_once('conexion.php');
require_once('verificar_sesion.php');

$con=Conectar();
//Cambiar la hora del servidor por la de Colombia
putenv ("TZ=America/Bogota");

$sql="update tbalumnos set NoMatriculaAlum='". $_POST['txtMat']."', NombresAlum='".$_POST['txtNombres']."', ApellidosAlum='". $_POST['txtApellidos']."',
	  SexoAlum='".$_POST['txtSex']."', PazySalvoAlum=".$_POST['PazySalvo'].", DeudaAlum=".$_POST['txtPension']."
	where idAlum='".$_POST['txtId']."'";

//echo $sql;

$qsql=mysql_query($sql, $con) or die("No se guarda. " .mysql_error().$sql);
if(!$qsql){
	echo "Error al guardar";
} else {
	echo "Guardado con &Eacute;xito.";
}
?>