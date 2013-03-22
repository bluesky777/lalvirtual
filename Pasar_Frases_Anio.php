<?php
require_once('verificar_sesion.php');
require_once('conexion.php');


$con=Conectar();


if (isset( $_GET['Pasar'] )){

	$sqlFra = "SELECT * FROM tbfrases f where f.YearFrase='2012'";
	$qSqlFra = mysql_query($sqlFra, $con) or die("No se trajeron las frases.");

	while ($rSqlFra = mysql_fetch_array($qSqlFra)) {

		$sql="INSERT into tbfrases(Frase, TipoFrase, YearFrase) values('".$rSqlFra['Frase']."','".$rSqlFra['TipoFrase']."','2013');";

		$qSql = mysql_query($sql, $con) or die ("No se pudo ingresar la frase. ".mysql_error());
		
		echo $rSqlFra['Frase'] . " <strong>Frase guardada con éxito</strong>";
	}

	

}else{
?>

<a href="Pasar_Frases_Anio.php?Pasar=true">Pasar frases del 2012 al 2013</a>

<?php
}

?>