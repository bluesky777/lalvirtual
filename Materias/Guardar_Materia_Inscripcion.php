<?php
require_once("../conexion.php");

$con=Conectar();

$sql="insert into tbmateriagrupo(idMateria, idGrupo, idProfesor, CreditosMater, OrdenMater)
	 values(".$_POST['txtMateria'].", ".$_POST['txtGrupo'].", ".$_POST['txtProfesor'].", ".$_POST['txtCreditos'].", ".$_POST['txtOrden'].")";
	 
//echo $sql;
$qSql=mysql_query($sql, $con) or die("No se pudo inscribir materia." . mysql_error());
echo "Exitoso";
?>