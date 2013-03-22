
<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();

$idAlum=$_POST['idAlum'];
$idComp=$_POST['idComp'];

$sqlInd="select idIndic from tbindicadores where CompetenciaIndic=".$idComp;
$qSqlInd=mysql_query($sqlInd, $con) or die("No se trajeron los indicadores." . mysql_error());

$i=0;

while($rSqlInd=mysql_fetch_array($qSqlInd)){
	$sql="delete from tbnotas where idIndic=".$rSqlInd['idIndic'];
	$qSql=mysql_query($sql, $con) or die("No se pudo eliminar la nota: ".$rSqlInd['idIndic']);
	$i++;
}

echo "Elimados con éxito ".$i." notas. Si vuelve a esta página se crearán automáticamente las notas para el estudiante";