<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();

	$sql="insert into tbgrupoalumnos(idAlumno, idGrupo, idPeriodo, Estado) values('".$_GET['idAlum']."','".$_GET['idGrup']."','".$_SESSION['PeriodoUsu']."',1)";

	$qSql=$con->query($sql) or die ("No se pudo matricular. " . mysqli_error($con));
	
	echo "<meta charset='utf-8'>Matriculado con éxito;";


/*
$sqlVeri="Select idAlumno from tbgrupoalumnos where idAlumno='".$_GET['idAlum']."' and idPeriodo='".$_SESSION['Per']."'";
$qSqlVeri=mysql_query($sqlVeri,$con) or die("No se pudo comprobar si el alumno está matriculado en otro grupo del periodo");

$x=mysqli_num_rows($qSqlVeri);

if($x>0){
	echo "No se puede matriculas, el alumno ya está matriculado en otro grupo este periodo.";
}else{
	$sql="insert into tbgrupoalumnos(idAlumno, idGrupo, idPeriodo, Estado) values('".$_GET['idAlum']."','".$_GET['idGrup']."','".$_SESSION['Per']."',1)";

	$qSql=mysql_query($sql, $con) or die ("No se pudo matricular. " . mysql_error());
	
	echo "Matriculado con éxito;";
}
*/

?>