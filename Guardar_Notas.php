<?
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();

$sqlInd="select * from tbindicadores where CompetenciaIndic='".$_POST['txtIdComp']."'";

$qSqlInd=mysql_query($sqlInd, $con) or die("No se trajeron los indicadores de la competencia " . $_POST['txtIdComp'] . " - " . mysql_error());

$InsBien=0;
$UpdBien=0;

while($rSqlInd=mysql_fetch_array($qSqlInd)){

	$sqlAlum="select * from tbalumnos a, tbgrupoalumnos ga where a.idAlum=ga.idAlumno and ga.idGrupo='".$_POST['txtIdGrupo']. "' and ga.idPeriodo='".$_SESSION['PeriodoUsu']."' order by ApellidosAlum";

	$qSqlAlum=mysql_query($sqlAlum, $con) or die ("No se trajeron los alumnos" . mysql_error());
	
	while($rSqlAlum=mysql_fetch_array($qSqlAlum)){
		
		$sqlNot="select * from tbnotas where idIndic=".$rSqlInd['idIndic']." and idAlumno=".$rSqlAlum['idAlum'];
		
		$qSqlNota=mysql_query($sqlNot, $con) or("No se pudo traer las notas del alumno id: ". $rSqlAlum['idAlum']);

		$nSqlNota=mysql_num_rows($qSqlNota);
		
		$Nom="idNotaA". $rSqlAlum['idAlum']. "I" .$rSqlInd['idIndic'];
		$Valor=$_POST[$Nom];

		if($nSqlNota){
			
			$sqlUpd="update tbnotas set Nota='".$Valor."' where idIndic=".$rSqlInd['idIndic']." and idAlumno=".$rSqlAlum['idAlum'];
			
			$qSqlUpd=mysql_query($sqlUpd,$con) or die("No se actualizó el indicador: ".$rSqlInd['idIndic']. " - " .mysql_error());
			$UpdBien++;
		} else {
	
			$sqlInsertar="insert into tbnotas (idIndic, idAlumno, Nota) values(" . $rSqlAlum['idAlum'] .", ".$rSqlInd['idIndic'].", ".$Valor.")";
			$InsBien++;
		
		}
		

	}
	
}
echo "Actualizados: ".$InsBien+$UpdBien. " Notas guardadas ;)";
?>