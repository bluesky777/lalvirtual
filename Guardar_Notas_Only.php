<?
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();


		
$sqlNot="select * from tbnotas where idIndic=".$_POST['idInd']." and idAlumno=".$_POST['idAlu'];
		
$qSqlNota=mysql_query($sqlNot, $con) or("No se pudo traer las notas del alumno id: ". $rSqlAlum['idAlum']);

$nSqlNota=mysqli_num_rows($qSqlNota);


if($nSqlNota){
	
	$sqlUpd="update tbnotas set Nota='".$_POST['Nota']."' 
		where idIndic=".$_POST['idInd']." and idAlumno=".$_POST['idAlu'];
	
	
	$qSqlUpd=mysql_query($sqlUpd, $con) or die("No se actualizó el indicador: ".$_POST['idInd']. " - " .mysql_error());
	
	echo "Nota guardada: ".$_POST['Nota'];

} else {

	$sqlInsertar="insert into tbnotas (idIndic, idAlumno, Nota) 
		values(" .$_POST['idInd'] .", ". $_POST['idAlu'].", ".$_POST['Nota'].")";
		
	$sqlInsertar=mysql_query($sqlInsertar, $con)or die("No inserta la nota ".$_POST['Nota'].". ".mysql_error());
	
	echo "Nota ingresada: ".$_POST['Nota'];
}

?>