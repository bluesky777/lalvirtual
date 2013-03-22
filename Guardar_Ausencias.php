<?

require_once('conexion.php');
require_once('verificar_sesion.php');

$con=Conectar();

$sqlL="select idAlumno from tbgrupoalumnos
	where idGrupo=".$_POST['idGrupo'];

$qSqlL=mysql_query($sqlL, $con) or die("No se trajeron los alumnos. ".mysql_error());

while($rSqlL=mysql_fetch_array($qSqlL)){
	
		
	$sqlA="select idAus, CantidadAus from tbausencias where idAlumno=". $rSqlL['idAlumno']. " 
		and idPeriodo=".$_SESSION['PeriodoUsu']." and idMaterGrupo=". $_POST['idMat'];

	$qSqlA=mysql_query($sqlA, $con) or die("No se trajo la nota del alumno ".$rSqlL['idAlumno']."</br>".mysql_error());

  
	$num=mysql_num_rows($qSqlA);
	
	$idTemp="Nota".$rSqlL['idAlumno'];
	
	if ($num>0){
		$rSqlA=mysql_fetch_array($qSqlA);
		
		$sqlEx="update tbausencias set CantidadAus='". $_POST[$idTemp] ."' where idAus=". $rSqlA['idAus'];

		
	} else {
		$rSqlA['CantidadAus']=0;
		
		$sqlEx="INSERT INTO `tbausencias` (`idMaterGrupo`, `idAlumno`, `idPeriodo`, `CantidadAus`) 
			VALUES ('".$_POST['idMat']."', '". $rSqlL['idAlumno'] ."', 
			'". $_SESSION['PeriodoUsu']."', '". $_POST[$idTemp] ."')";
			
				
	}
  
	
	$qSqlEx=mysql_query($sqlEx, $con)or die("No se modifico la ausencia. <br/>" . mysql_error()." - ".$sqlEx);

}
echo "Guardado";
	
?>
