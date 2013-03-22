<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();

$sqlVeri="DELETE from tbgrupoalumnos where idAlumno='".$_GET['idAlum']."' 
	and idPeriodo='".$_SESSION['PeriodoUsu']."' and idGrupo='" . $_GET['idGrup'] . "'";

$qSqlVeri=mysql_query($sqlVeri, $con) or die("No se pudo remover el alumno de este grupo. ".mysql_error(). " - ". $sqlVeri);

echo "El estudiante ha sido removido de este grupo.";

/*
$x=mysql_affected_rows($qSqlVeri);
echo "Aqui: " . $x;

if($x>0){
	echo "El estudiante ha sido removido de este grupo.";
}else{
	echo "No se pudo desvincular este alumno de este grupo.";
}
*/
?>
