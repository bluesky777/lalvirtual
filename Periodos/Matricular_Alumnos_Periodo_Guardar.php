<?php
require_once("../verificar_sesion.php");
require_once("clsPeriodos.php");

$Prd=new clsPeriodos();

$idPer=$_GET['idPer'];
$rSqlP=$Prd->gPeriodo($idPer);
$PerNow=$rSqlP['Periodo'];

$sqlAnt="select idAlumno, idGrupo 
	from tbgrupoalumnos ga, tbperiodos p
	where ga.Estado=1  and p.Periodo=ga.idPeriodo 
	and p.idPer=". ($PerNow-1);

$sqlNow="select idAlumno from tbgrupoalumnos where idPeriodo=". $PerNow;

$qSqlAnt=$Prd->queryx($sqlAnt, "No se trajeron los alumnos anteriores.");
$qSqlNow=$Prd->queryx($sqlNow, "No se trajeron los alumnos actuales");

$Cont=0;

while($rAnt=mysql_fetch_array($qSqlAnt)){
	
	$sw=0;
	
	while($rNow=mysql_fetch_array($qSqlNow)){
		
		if($rNow['idAlumno']==$rAnt['idAlumno']){
			$sw=1;
			break 2;
		} 
	}
	
	if($sw==0){
		
		$sqlIns="INSERT INTO tbgrupoalumnos (`idAlumno`, `idGrupo`, `idPeriodo`, `Estado`) 
			VALUES (". $rAnt['idAlumno'] . ", ". $rAnt['idGrupo'] .", ". $PerNow . ", '1')";
			
		$qSqlIns=$Prd->queryx($sqlIns, "No se pudo matricular -Alum: ".
											 $rAnt['idAlumno']. " -Gr: ". $rAnt['idGrupo'] .
											 " -Per: ". $PerNow);
		$Cont++;
	}
	
	
}

echo "Hecho. " . $Cont . " alumnos agregados al periodo <b>" . $PerNow. "</b>. Recargue la página.";

$Prd->Cerrar();

?>