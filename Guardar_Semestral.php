<?
require_once('conexion.php');
require_once('verificar_sesion.php');

$con=Conectar();

		
$sqlE="select ex.idExa 	from tbexamenessem ex 
	where ex.MateriaGrupoExa=".$_POST['idMat']. " 
	and ex.SemestreExa=".$_POST['idSem'];
	
$qSqlE=mysql_query($sqlE, $con) or die("No se consulto el examnen.</br>".mysql_error());

$rSqlE=mysql_fetch_array($qSqlE);
	
$idExam=0;
	
$sqlL="select idAlum, NombresAlum, ApellidosAlum 
	from tbalumnos a, tbgrupoalumnos ga, tbmateriagrupo mg, tbgrupos g 
	where ga.idGrupo=g.idGrupo and ga.idAlumno=a.idAlum and g.idGrupo=mg.idGrupo 
	and mg.idMaterGrupo=".$_POST['idMat'] . " and ga.idPeriodo=".$_SESSION['PeriodoUsu'];

$qSqlL=mysql_query($sqlL, $con) or die ("No se trajo el listado de alumnos para modificarlos. ".mysql_error());


while($rSqlL=mysql_fetch_array($qSqlL)){
	
	$sqlA="select n.Nota, ex.idExa 
		from tbexamenessem ex, tbnotasemes n 
		where ex.MateriaGrupoExa=".$_POST['idMat']. " 
		and ex.SemestreExa=".$_POST['idSem']."
		and n.idExamen=ex.idExa and n.idAlumno=".$rSqlL['idAlum'];
		
	$qSqlA=mysql_query($sqlA, $con) or die("No se consulto la nota del alumno ".$rSqlL['NombresAlum']."</br>".mysql_error());

  
  $num=mysql_num_rows($qSqlA);
  
  $sqlEx; //Declaro
  
  $NtTemp="Nota".$rSqlL['idAlum']; //Nombre de la nota que voy a guardar
  
  if ($num>0){
	
	$sqlEx="UPDATE `tbnotasemes` SET `Nota`=".$_POST[$NtTemp]." WHERE `idExamen`='".$rSqlE['idExa']."' and `idAlumno`='". $rSqlL['idAlum'] ."';";
	
	$qSqlEx=mysql_query($sqlEx,$con);
	
	$idExam=$rSqlE['idExa'];
	   
  } else {
	

	$idExam=$rSqlE['idExa'];
	
	$sqlEx="INSERT INTO `tbnotasemes` (`idExamen`, `idAlumno`, `Nota`) 
		VALUES ('".$rSqlE['idExa']."', ". $rSqlL['idAlum'] .", ".$_POST[$NtTemp].");";

	$qSqlEx=mysql_query($sqlEx, $con)or die("Problemas con el alumno ".$rSqlL['NombresAlum']."</br>".mysql_error());

	
  }
	
}


$sqlDes="UPDATE `tbexamenessem` SET `Semestral`='".$_POST['txtDesc']."' WHERE `idExa`='".$idExam."';";

$qSqlDes=mysql_query($sqlDes, $con);

echo "Guardado";
?>