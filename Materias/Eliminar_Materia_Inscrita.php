<?php
require_once("conexion.php");

$con=Conectar();

$sql="delete from tbmateriagrupo where idMaterGrupo='".$_POST['idMatGr']."'";
//echo $sql;
$qSql=mysql_query($sql, $con) or die("Tómalo!!, no se pudo eliminar. " . mysql_error());

echo("Se ha borrado la materia exitosamente.");

?>