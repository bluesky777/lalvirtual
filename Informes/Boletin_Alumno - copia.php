<?php
require_once("../conexion.php");
require_once("../verificar_sesion.php");

require_once("funciones.php");
include("../php/clsPersonaTotal.php");
//require_once("verificar_sesion_arriba.php");

$con=Conectar();

$MiJuicio = new JuicioVal($_SESSION['Year']);

$idAlum;

if (isset($_GET['txtIdAlum'])){
	$idAlum=$_GET['txtIdAlum'];
}else{
	if (isset($_GET['IdAlum'])){
		$idAlum=$_GET['IdAlum'];
	} else {
		$idAlum=275;
	}
}



$sqlAl="Select * from tbalumnos where idAlum=".$idAlum;
$qSqlAl=mysql_query($sqlAl, $con) or die("No se trajo los datos del alumnos.".mysql_error().". ".$sqlAl);
$rSqlAl=mysqli_fetch_array($qSqlAl);


$sqlCol="select * from tbyearcolegio where Year=".$_SESSION['Year'];

$qSqlCol=mysql_query($sqlCol, $con) or die("No se seleccionó la información del año actual. ".mysql_error().". ".$sqlCol);
$rSqlCol=mysqli_fetch_array($qSqlCol);

$sqlGr="select g.NombreGrupo, g.Grupo, g.idGrupo, p.idProf, p.NombresProf, p.ApellidosProf
		from tbgrupos g, tbgrupoalumnos ga, tbprofesores p, tbalumnos a 
		where a.idAlum=ga.idAlumno and ga.idGrupo=g.idGrupo and g.YearGrupo=".$_SESSION['Year'].
			" and g.TitularGrupo=p.idProf and a.idAlum=".$idAlum;	
			
$qSqlGr=mysql_query($sqlGr, $con)or die("No se trajo la información del grupo.".mysql_error()." ".$sqlGr);
$rSqlGr=mysqli_fetch_array($qSqlGr);



$Alto = 2550;
$Ancho = 3300;

$Apell = $rSqlAl['ApellidosAlum'];
$Nomb = $rSqlAl['NombresAlum'];
$Sex = $rSqlAl['SexoAlum'];

$NomCol = $rSqlCol['NombreColegio'];
$Rector = $rSqlCol['RectoraCol'];
$Secre = $rSqlCol['SecretariaCol'];
$imgLogo = $rSqlCol['LogoColegio'];
$Resol = $rSqlCol['EncabezadoCertificados'];

$NomGrado = $rSqlGr['NombreGrupo'];
$Grupo = $rSqlGr['Grupo'];
$idProf = $rSqlGr['idProf'];
$NomProf = $rSqlGr['NombresProf'];
$ApellProf = $rSqlGr['ApellidosProf'];
$idGrupo = $rSqlGr['idGrupo'];
$PonerCaras = false;

if(($Grupo=='T')or($Grupo=='J')or($Grupo=='1')or($Grupo=='2')){
	$PonerCaras=true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link type="text/css" href="Boletin_Alumno.css" rel="stylesheet">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $Apell; ?></title>
</head>

<body>
<?php //////////////////////////  BOLETIN  //////////////////////////////////////?>
<div class="Boletin">
<?php //////////////////////////  ENCABEZADO  //////////////////////////////////////?>
<div class="Encabezado">

  <div class="LogoColegio">
	<img src="../img/Colegio/Logo.jpg">
  </div>
  <div class="margenNombreColegio">
      <div class=NombreColegio>
        <b><?php  echo $NomCol;?></b>
      </div>
  </div>
  
  <div class="margenResolucion">
      <div class="Resolucion">
        <?php  echo $Resol;?>
      </div>
  </div>
  <div class="NombreEst">
  	<b><?php  echo $Apell . " " . $Nomb;?></b>
  </div>

  <div class="Periodo">
	Periodo: <b><?php echo $_SESSION['Per']."-".$_SESSION['Year'];?></b>
  </div>
  
  <div class="Porcentaje">
	Puntaje: <b>
	<?php 
	
	$perso = new clsPersona();
	$Prome = $perso->PromedioTotal($idAlum, $_SESSION['PeriodoUsu']);
	echo intval($Prome)."%";

	?></b>
  </div>
  
  <div class="Grado">
	Grado: <b><?php echo $NomGrado;?></b>
  </div>


  <div class="Profe">
	Titular <?php echo $NomProf." ".$ApellProf; ?>
  </div>
    
</div><?php //////////////////////////  ENCABEZADO  //////////////////////////////////////?>

<?php //////////////////////////  BODY  //////////////////////////////////////?>
<div class="Body">
<?php //////////////////////////  MATERIA  //////////////////////////////////////?>
<?php


$sqlMat = "select * from tbmaterias m, tbmateriagrupo mg, tbgrupos g 
		where m.idMateria=mg.idMateria and mg.idGrupo=g.idGrupo 
		and g.idGrupo=".$idGrupo." and g.YearGrupo=".$_SESSION['Year']." order by ordenMater";
		
$qSqlMat = mysql_query($sqlMat, $con) or die("No se trajeron las materias del grupo ".$idGrupo.". ".mysql_error()." <br>".$sqlMat);


while($rSqlMat = mysqli_fetch_array($qSqlMat)){
?>
  <div class="Materia" style="border-radius: 10; margin-top:5;">
		<div class="NomMateria"><b><?php echo $rSqlMat['NombreMateria'];?></b></div>
		<div class="NotMateria">
        
    <?php //////////////////////////// CALCULO DE NOTA FINAL  //////////////////////////////
	
	$sqlNotComp="SELECT sum((R2.PorcCompet/100)*R2.ValorCompetencia) Valores  from
	(SELECT sum(r.ValorNota) ValorCompetencia, r.PorcCompet from
		(SELECT i.CompetenciaIndic,c.PorcCompet, i.idIndic,i.Indicador, 
		AVG((i.PorcIndic/100)*n.Nota) ValorNota
		FROM (tbcompetencias c 
			INNER JOIN tbindicadores i 
			ON c.idCompet=i.CompetenciaIndic)
				INNER JOIN tbnotas n 
				ON  i.idIndic=n.idIndic
		WHERE c.PeriodoCompet=".$_SESSION['PeriodoUsu']."
			 AND MateriaGrupoCompet='".$rSqlMat['idMaterGrupo']."' 
			 AND n.idAlumno=".$idAlum."
		GROUP BY i.idIndic,i.Indicador) r
	group by (r.CompetenciaIndic) ) R2
	group by R2.ValorCompetencia";
	
	//echo $sqlNotComp;
	
	$qSqlComp=mysql_query($sqlNotComp,$con) or die ("No se calcularon las competencias de la materia: ".$rSqlMat['idMaterGrupo'].". ".mysql_error());
	
	$PromMat=0;
	while($rSqlComp=mysqli_fetch_array($qSqlComp)){
		$PromMat+=$rSqlComp['Valores'];
	}
	
	$Palabra=$MiJuicio->Palabra(intval($PromMat));

	echo "<b>".$Palabra." - ". intval($PromMat);  if($PonerCaras==true) echo $MiJuicio->Carita(intval($PromMat), $Grupo); echo "</b>";
	
	?>
    </div><?PHP ////////////////////////////////// FIN CALCULO DE NOTA FINAL  //////////////////////////////?>
        
  </div><?PHP ///////////////////////////////// FIN MATERIA  //////////////////////////////?>
    
    
    <div class="Competencias"> <?php /////////////////////// COMPETENCIA  //////////////////////////////?>
    <?php
	$sqlComp="SELECT * FROM tbcompetencias 
		where PeriodoCompet=".$_SESSION['PeriodoUsu']." and 
			MateriaGrupoCompet=".$rSqlMat['idMaterGrupo'];
			
	//echo $sqlComp;
	
	$qSqlComp=mysql_query($sqlComp, $con)or die("No se trajeron las competencias.".mysql_error()." ".$sqlComp);
	
	
	$iComp=1;
	
	while($rSqlComp=mysqli_fetch_array($qSqlComp)){
		
		?>
        <div class="Competencia">
        <?php
		echo $iComp++ . ". " .ucfirst($MiJuicio->Mayustil($rSqlComp['Competencia']));
		?>
        </div>
        
        <?PHP

			$sqlInd="SELECT sum((r.PorcCompet/100)*r.ValorNota) ValorCompPorc, r.PorcCompet, sum(r.ValorNota) ValorComp  from
		(SELECT i.CompetenciaIndic,c.PorcCompet, i.idIndic,i.Indicador, AVG((i.PorcIndic/100)*n.Nota) ValorNota
		FROM (tbcompetencias c 
			INNER JOIN tbindicadores i 
			ON c.idCompet=i.CompetenciaIndic)
				INNER JOIN tbnotas n 
				ON  i.idIndic=n.idIndic
		WHERE c.PeriodoCompet=".$_SESSION['PeriodoUsu']."
			 AND MateriaGrupoCompet='".$rSqlMat['idMaterGrupo']."' 
			 AND n.idAlumno=".$idAlum." and c.idCompet=".$rSqlComp['idCompet']."
		GROUP BY i.idIndic,i.Indicador) r
	group by (r.CompetenciaIndic)";
	
	//echo $sqlInd;
	
	$qSqlInd=mysql_query($sqlInd,$con) or die ("No se hizo el segundo calculo de las competencias de la materia: ".$rSqlMat['idMaterGrupo'].". ".mysql_error());

	$rSqlInd=mysqli_fetch_array($qSqlInd);

		?>
        
        <?php /////////////////////////////////// NOTA COMPETENCIA //////////////////////////////?>
        <div class="NotaCompet">
            <div class="Det">
                <?php echo "(".$rSqlInd['PorcCompet']."%=".intval($rSqlInd['ValorCompPorc']).")";?>
            </div>
            <div class="NotaC">
                <?php echo $MiJuicio->Palabra(intval($rSqlInd['ValorComp']))." - ".intval($rSqlInd['ValorComp']); ?>
            </div>
        </div>
        
		
    <?php /////////////////////////////////// INDICDADORES //////////////////////////////?>
    
    <div class="Indicadores">
    	<?php
		$sqlIndi="select * from tbindicadores i inner join tbnotas n on i.idIndic=n.idIndic
			where n.idAlumno=".$idAlum." and i.CompetenciaIndic=".$rSqlComp['idCompet'];
		$qSqlIndi=mysql_query($sqlIndi, $con)or die("No se trajeron los indicadores de la competencia ".$rSqlComp['idCompet'].". ".mysql_error() .$sqlIndi);
		
		//echo $sqlIndi;
		
		$iInd=1;
		
		while($rSqlIndi=mysqli_fetch_array($qSqlIndi)){
    	?>
        <div class="ContInd">
		<div class="NomIndicador"><?php echo $iInd++."-".ucfirst($MiJuicio->Mayustil($rSqlIndi['Indicador'])); ?></div>
        <div class="Cont"><div class="PorcIndicador"><?php echo " (".$rSqlIndi['PorcIndic']."%)"; ?></div></div>
    	<div class="NotIndicador"><?php echo $MiJuicio->Palabra($rSqlIndi['Nota'])." ".$rSqlIndi['Nota']; ?></div>			
		</div>
        <?php
        }
		
		?>
    	
        

    </div> <?php /////////////////////////////////// FIN INDICDADORES ////////////////////////////// ?>
            
        
        
	<?php		
	}  

	?>
    
	
	
    </div> <?php /////////////////////// COMPETENCIAS  //////////////////////////////?>


<?php
}


 ///////////////////////////////////// COMPORTAMIENTO  //////////////////////////////
?>

<div class="Comportamiento">
<div class="BarraComportamiento">

    <div class="TituloComportamiento">
        <b>Comportamiento</b>
    </div>
<?php

$sqlDisc="Select * from tbcomportamiento where MateriaGrupoComport=".$idGrupo. " and AlumnoComport=".$idAlum." and PeriodoComport=".$_SESSION['PeriodoUsu'];

$qSqlDisc=mysql_query($sqlDisc, $con)or die("No se trajo el comportamiento del alumno: "
		. $idAlum.". ".mysql_error()." ".$sqlDisc);

$rSqlDisc=mysqli_fetch_array($qSqlDisc);

$idComportamiento=$rSqlDisc['idComport'];
?>

    <div class="NotaComportamiento"><b><?php echo $MiJuicio->Palabra($rSqlDisc['NotaComport'])." - "
			.$rSqlDisc['NotaComport']; if($PonerCaras==true) echo $MiJuicio->Carita($rSqlDisc['NotaComport'], $Grupo);?></b>
    </div>
    
</div>  <?php //////////////////////////  CIERRA BARRA COMPORTAMIENTO  //////////////////////////////////////?>


<div class="FrasesComport">
<?php

$sqlFraDisc="Select * from tbfrases f, tbfrasescomportamiento fc 
		where f.idFrase=fc.idFrase and fc.idComportamiento='".$idComportamiento."'";

$qSqlFraDisc=mysql_query($sqlFraDisc, $con)or die("No se trajeron las frases de comportamiento. ".mysql_error()." ".$sqlFraDisc);

while($rSqlFraDisc=mysqli_fetch_array($qSqlFraDisc)){

?>
	<div class="Frase"><?php echo $rSqlFraDisc['Frase']; ?></div>
    <div class="TipoFrase"><?php echo $rSqlFraDisc['TipoFrase']; ?></div>
    
<?php
}
?>
</div>

</div><?php //////////////////////////  CIERRA COMPORTAMIENTO  //////////////////////////////////////?>



<?php ////////////////////////// TABLA RESUMEN  //////////////////////////////


$sqlMat ="select * from tbmaterias m, tbmateriagrupo mg, tbgrupos g 
		where m.idMateria=mg.idMateria and mg.idGrupo=g.idGrupo and g.idGrupo=".$idGrupo." 
		order by ordenMater";

$qSqlMat=mysql_query($sqlMat, $con);


$MateriaYa=array();
$PeriodoYa=array();


while($rSqlMat = mysqli_fetch_array($qSqlMat)){
	
	
	$sqlPeriodos="select idPer, Periodo, Year from tbperiodos where Year=".$_SESSION['Year'];
	
	$qSqlPeriodos=mysql_query($sqlPeriodos, $con)or die("No se 
		trajeron los periodos del año " .$_SESSION['Year'].". <br>" . mysql_error());
	

	while($rSqlPeriodos=mysqli_fetch_array($qSqlPeriodos)){
		
		$sqlMalo="select n.Nota, n.idAlumno, i.idIndic, i.Indicador, c.idCompet 
			from tbnotas n, tbindicadores i, tbcompetencias c, tbmateriagrupo mg, 
			tbgrupoalumnos ga, tbalumnos a 
			where n.idIndic=i.idIndic and i.CompetenciaIndic=c.idCompet 
			and c.MateriaGrupoCompet=mg.idMaterGrupo and a.idAlum=ga.idAlumno and a.idAlum=n.idAlumno 
			and mg.idMateria=".$rSqlMat['idMateria']." 
			and n.idAlumno=".$idAlum." and c.PeriodoCompet=".$rSqlPeriodos['idPer']." and n.Nota<70 
			and ga.idPeriodo=".$rSqlPeriodos['idPer'];
			
		
		$qSqlMalo=mysql_query($sqlMalo, $con)or die("No se trajeron las notas de la materia: 
			".$rSqlMat['idMateria'].". ".mysql_error());
		
		if(mysqli_num_rows($qSqlMalo)>0){
			
			if(!in_array($rSqlMat['idMateria'], $MateriaYa)){
				$MateriaYa[]=$rSqlMat['idMateria'];
			}
			if(!in_array($rSqlPeriodos['Periodo'], $PeriodoYa)){
	
				$PeriodoYa[]=$rSqlPeriodos['Periodo'];
			} 
			
		}

	}  /// while periodos a recorrer
	
} //// while materias del alumno
		
?>


<div class="TablaResumen">

Resumen de logros pendientes
<div class="clear"></div>
<table border="1px" style="border-collapse:collapse; ">
<tr>
	<td style="text-align:center"><B>MATERIAS</B></td>
	

<?php
foreach($PeriodoYa as $id_Per => $Periodo){
	?>
    <td><b>Per<?php echo $Periodo; ?></b></td>
	<?php
}
?>

	<td><b>Total</b></td>
</tr>

<?php

foreach($MateriaYa as $id_Mat => $Materia){
	
	$TotalMatPer=0;
	
	$sqlNomM="select NombreMateria from tbmaterias where idMateria=".$Materia;
	
	//echo $sqlNomM;
	
	$qSqlNomM=mysql_query($sqlNomM,$con)or die("No se trajo el nombre de la 
			materia ".$id_Mat.". ".mysql_error());
	
	$rSqlNomM=mysqli_fetch_array($qSqlNomM);
	
	$nomMin=strtolower($rSqlNomM['NombreMateria']);
	?>
<tr>    
    <td class="nomMMat"><?php echo ucfirst($MiJuicio->Mayustil($nomMin)); ?></td>
    
    <?
	
	foreach($PeriodoYa as $id_Per => $Periodo){
			
		$sqlMalo="select n.Nota, n.idAlumno, i.idIndic, i.Indicador, c.idCompet 
			from tbnotas n, tbindicadores i, tbcompetencias c, tbmateriagrupo mg, 
			tbgrupoalumnos ga, tbalumnos a 
			where n.idIndic=i.idIndic and i.CompetenciaIndic=c.idCompet 
			and c.MateriaGrupoCompet=mg.idMaterGrupo and a.idAlum=ga.idAlumno and a.idAlum=n.idAlumno 
			and mg.idMateria=".$Materia." 
			and n.idAlumno=".$idAlum." and c.PeriodoCompet=".$Periodo." and n.Nota<70 
			and ga.idPeriodo=".$Periodo;
			
			//echo $sqlMalo."<br>";
		$ComentarioIndics="";
		$ContPerdidos=0;
		
		
		$qSqlMalo=mysql_query($sqlMalo, $con)or die("No se trajeron las notas de la materia: 
			".$Materia.". ".mysql_error());
				
	
		while($rSqlMalo=mysqli_fetch_array($qSqlMalo)){

			$ComentarioIndics.=$rSqlMalo['Indicador']." =".$rSqlMalo['Nota']." \n";
			$ContPerdidos+=1;
						
		} //// while indicadores perdidos de la materia
		
		$TotalMatPer+=$ContPerdidos;
		
		?>
		
        <td title="<?php echo $ComentarioIndics; ?>">
            <?php echo $ContPerdidos; ?>
        </td>
		
		<?			
		
	}  /// FOREACH periodos a recorrer	
?>
	<td><b><?php echo $TotalMatPer; ?></b></td>
</tr>	

<?
}  /// foreach que tiene las materias donde tiene indicadores pendientes

?>

<tr>
	<td><b>TOTAL</b></td>
<?

$TotalPer=0;
foreach($PeriodoYa as $id_Per => $Periodo){
	

		$sqlMalo="select n.Nota, n.idAlumno, i.idIndic, i.Indicador, c.idCompet 
			from tbnotas n, tbindicadores i, tbcompetencias c, tbmateriagrupo mg, 
			tbgrupoalumnos ga, tbalumnos a 
			where n.idIndic=i.idIndic and i.CompetenciaIndic=c.idCompet 
			and c.MateriaGrupoCompet=mg.idMaterGrupo and a.idAlum=ga.idAlumno and a.idAlum=n.idAlumno 
			and n.idAlumno=".$idAlum." and c.PeriodoCompet=".$Periodo." and n.Nota<70
			 and ga.idPeriodo=".$Periodo;
			
			//echo $sqlMalo."<br>";

		$qSqlMalo=mysql_query($sqlMalo, $con)or die("No se trajeron las notas totales. ".mysql_error());
		$ContPerdidos=0;
		
		while($rSqlMalo=mysqli_fetch_array($qSqlMalo)){

			$ContPerdidos+=1;
						
		} //// while indicadores perdidos de la materia
		
		$TotalPer+=$ContPerdidos;

	?>
    <td><b><?php echo $ContPerdidos; ?></b></td>
	<?
	
}
?>
    <td><b><?php echo $TotalPer; ?></b></td>
</tr>

</table>


<div class="clear"></div>

</div><?php //////////////////////////  BODY  //////////////////////////////////////?>

<div class="clear"></div>

<div class="Firmas"><?php //////////////////////////  FIRMAS  //////////////////////////////////////?>

<div class="FirmaRector">
	<div class="LineaFirma"></div>
    <div class="NombreRector"><?php echo $Rector. "<BR>Rectora"; ?></div>
</div>


<div class="Titular">
	<div class="LineaFirma"></div>
    <div class="NombreTitular"><?php echo $NomProf. " " .$ApellProf . "<BR>Titular"; ?></div>
</div>

</div><?php ///////////////////////////////  TERMINAN FIRMAS  //////////////////////////////////////?>

</div><?php //////////////////////////  BOLETIN  //////////////////////////////////////?>


</body>
</html>
