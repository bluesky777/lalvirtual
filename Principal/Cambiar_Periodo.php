<?php
session_name("LoginUsuario"); 
session_start();

require_once("../conexion.php");


$con=Conectar();

$PerSel = $_GET['PerSel'];
$idU= $_SESSION['idUsuar'];

$sql = "UPDATE tbusuarios SET PeriodoUsu=". $PerSel ." WHERE idUsu=". $idU;

$qSql=mysql_query($sql, $con) or die("No se pudo cambiar el periodo Error: " . mysql_error());


$sqlAc = "select Periodo, Year from tbperiodos where idPer=" . $PerSel;

$qSqlAc=mysql_query($sqlAc, $con);

while ($rSql=mysql_fetch_array($qSqlAc)) {
	$_SESSION['PeriodoUsu']=$PerSel;
	$_SESSION['Per']= $rSql['Periodo'];
	$_SESSION['Year']= $rSql['Year'];
}

echo "Per " . $_SESSION['Per'] ."-" . $_SESSION['Year'];

?>

