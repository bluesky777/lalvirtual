<?php

require_once('conexion.php');
require_once('verificar_sesion.php');

$con=Conectar();

$sqlComp="select * from tbcomportamiento where MateriaGrupoComport=".$_POST['txtGrupo'];
$qSqlComp=mysql_query($sqlComp, $con)or die("No se trajeron los registros de comportamiento.".mysql_error().". ".$sqlComp);

while($rSqlComp=mysqli_fetch_array($qSqlComp)){
	
	$nom="idComport" . $rSqlComp["idComport"];
	$valor=$_POST[$nom];
	
	$sql="UPDATE tbcomportamiento set NotaComport='".$valor."' where idComport=".$rSqlComp['idComport'].";";
	
	$qSql=mysql_query($sql, $con) or die ("No se pudo guardar las notas. ".mysql_error().". ".$sql);
		
	
}
	
echo "Notas guardadas.";

?>