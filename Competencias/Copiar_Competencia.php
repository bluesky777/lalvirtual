<?php 
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();


$sqlSel="select idCompet, Competencia, PorcCompet, FechaCreacionCompet, OrdenCompt, idGrupo as GrupoAnt 
		from tbcompetencias, tbmateriagrupo 
		where MateriaGrupoCompet=".$_POST['idMatAnt']. " 
		and PeriodoCompet=".$_SESSION['PeriodoUsu'] ." and MateriaGrupoCompet=idMaterGrupo";
//echo $sqlSel;
$qSqlSel=mysql_query($sqlSel, $con)or die("No se trajeron las competencias a copiar. ".mysql_error());

$rSqlSel=mysql_fetch_array($qSqlSel);

/// Verificar si el grupo del que se va a copiar es el mismo al que se va a copiar. //////////////////

$sqlGrNew="select idGrupo as GrupoNew from tbmateriagrupo 
		where idMaterGrupo=".$_POST['idMatNew'] . " and idMaterGrupo=".$rSqlSel['GrupoAnt'];
		
$qSqlGrNew=mysql_query($sqlGrNew, $con)or die("No se trajo el grupo nuevo<br>".mysql_error().$sqlGrNew);

$numGr=mysql_num_rows($qSqlGrNew);

$CopyAlum=0;

if($numGr>0){
	$CopyAlum=1;
} else {
	$CopyAlum=0;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////

$qSqlSel=mysql_query($sqlSel, $con)or die("No se trajeron las competencias a copiar. ".mysql_error());
while($rSqlSel=mysql_fetch_array($qSqlSel)){
	
	$sqlIns="insert into tbcompetencias (PeriodoCompet, 
		Competencia, PorcCompet, MateriaGrupoCompet, FechaCreacionCompet, OrdenCompt)
		values ('".$_POST['txtPeriodo']."','".$rSqlSel['Competencia']."',
		'".$rSqlSel['PorcCompet']."','".$_POST['idMatNew']."','".$rSqlSel['FechaCreacionCompet']."',
		'".$rSqlSel['OrdenCompt']."')";


	$qSqlIns=mysql_query($sqlIns, $con)or die("No se pudo copiar la competencia '".$rSqlSel['Competencia']."'. ".mysql_error());

echo "COMPETENCIA CREADA: <b>'".$rSqlSel['Competencia']."'</b> <br>";
	
	$idTemp=mysql_insert_id();
	
	
	$sqlSelInd="select * from tbindicadores where CompetenciaIndic=".$rSqlSel['idCompet'];

	$qSqlSelInd=mysql_query($sqlSelInd, $con)or die("No se pudo seleccionar los indicadores de la competencia ".$rSqlSel['Competencia'].". ".mysql_error());
	
	
	while($rSqlSelInd=mysql_fetch_array($qSqlSelInd)){
		
		$sqlInsInd="insert into tbindicadores (Indicador, PorcIndic, 
				CompetenciaIndic, FechaInicioIndic, FechaFinIndic, 
				OrdenIndic, FechaCreacionIndic, NotaPorDefecto) 
				values('".$rSqlSelInd['Indicador']."','".$rSqlSelInd['PorcIndic']."','".$idTemp."',
				'".$rSqlSelInd['FechaInicioIndic']."','".$rSqlSelInd['FechaFinIndic']."',
				'".$rSqlSelInd['OrdenIndic']."',
				'".$rSqlSelInd['FechaCreacionIndic']."','".$rSqlSelInd['NotaPorDefecto']."')";
				
		$qSqlinsInd=mysql_query($sqlInsInd, $con)or die("No se pudo copiar el indicador '".$rSqlSelInd['Indicador']."'. ".mysql_error()." ". $sqlInsInd);
		
		echo "- Indicador copiado: <b>".$rSqlSelInd['Indicador']."<b><br>";
	
		
		$idT=mysql_insert_id();
		
		
		
		if($CopyAlum=1){ // Si es el mismo grupo entonces copiamos trambién la nota
			
				
			$sqlSelNota="select * from tbnotas where idIndic=".$rSqlSelInd['idIndic'];
			
			$qSqlSelNota=mysql_query($sqlSelNota, $con);
			
			while($rSqlSelNota=mysql_fetch_array($qSqlSelNota)){
				
				$sqlInsNota="insert into tbnotas (idIndic, idAlumno, Nota) 
					values('".$idT."','".$rSqlSelNota['idAlumno']."','".$rSqlSelNota['Nota']."')";
					
				$qSqlInsNota=mysql_query($sqlInsNota, $con);
			
			
			}

			echo "--- Notas posibles copiadas.<br>";			
			
		}
	
	
	}
	
	
	
}

echo "<br><b>Operación finalizada.</b><br>";

?>
