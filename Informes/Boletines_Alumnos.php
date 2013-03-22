<?php
require_once("../conexion.php");
require_once("../verificar_sesion.php");

$con=Conectar();

$MiJuicio= new JuicioVal($_SESSION['Year']);


$sqlCol="select * from tbyearcolegio where Year=".$_SESSION['Year'];
$qSqlCol=mysql_query($sqlCol, $con) or die("No se seleccionó la información del año actual. ".mysql_error().". ".$sqlCol);
$rSqlCol=mysql_fetch_array($qSqlCol);


$NomCol = $rSqlCol['NombreColegio'];
$Rector = $rSqlCol['RectoraCol'];
$Secre = $rSqlCol['SecretariaCol'];
$imgLogo = $rSqlCol['LogoColegio'];
$Resol = $rSqlCol['EncabezadoCertificados'];


$sqlGr="select g.NombreGrupo, g.Grupo, g.idGrupo, p.idProf, p.NombresProf, p.ApellidosProf,
		a.idAlum, a.NombresAlum, a.ApellidosAlum, a.SexoAlum
		from tbgrupos g, tbgrupoalumnos ga, tbprofesores p, tbalumnos a 
		where a.idAlum=ga.idAlumno and ga.idGrupo=g.idGrupo and g.TitularGrupo=p.idProf and g.idGrupo=".$_POST['Grupos'];
$qSqlGr=mysql_query($sqlGr, $con)or die("No se trajo la información del grupo.".mysql_error()." ".$sqlGr);

while($rSqlGr=mysql_fetch_array($qSqlGr)){               /////////////// EMPEZAMOS A MOTRAR LOS BOLETINES DE TODOS


$Alto = 2550; //Tamaño de una hoja carta en pixeles
$Ancho = 3300;


$idAlum = $rSqlGr['idAlum'];
$Apell = $rSqlGr['ApellidosAlum'];
$Nomb = $rSqlGr['NombresAlum'];
$Sex = $rSqlGr['SexoAlum'];

$NomGrado = $rSqlGr['NombreGrupo'];
$idGrupo = $rSqlGr['idGrupo'];
$Grupo = $rSqlGr['Grupo'];
$idProf = $rSqlGr['idProf'];
$NomProf = $rSqlGr['NombresProf'];
$ApellProf = $rSqlGr['ApellidosProf'];
$PonerCaras = false;

if(($Grupo=='T')or($Grupo=='J')or($Grupo=='1')or($Grupo=='2')){
	$PonerCaras=true;
}


?>

<html xmlns="http://www.w3.org/1999/xhtml">
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

  <div class="Grado">
	Grado: <?php echo $NomGrado;?>
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
		and g.idGrupo=".$idGrupo." order by ordenMater";
$qSqlMat = mysql_query($sqlMat, $con) or die("No se trajeron las materias del grupo ".$idGrupo.". ".mysql_error()." ".$sqlMat);


while($rSqlMat = mysql_fetch_array($qSqlMat)){
?>
  <div class="Materia">
		<div class="NomMateria"><b><?php echo $rSqlMat['NombreMateria'];?></b></div>
		<div class="NotMateria">
    <?php //////////////////////////// CALCULO DE NOTA FINAL  //////////////////////////////
	$sqlNotComp="SELECT sum((R2.PorcCompet/100)*R2.ValorCompetencia) Valores  from
	(SELECT sum(r.ValorNota) ValorCompetencia, r.PorcCompet from
		(SELECT i.CompetenciaIndic,c.PorcCompet, i.idIndic,i.Indicador, AVG((i.PorcIndic/100)*n.Nota) ValorNota
		FROM (tbcompetencias c 
			INNER JOIN tbindicadores i 
			ON c.idCompet=i.CompetenciaIndic)
				INNER JOIN tbnotas n 
				ON  i.idIndic=n.idIndic
		WHERE c.PeriodoCompet=1
			 AND MateriaGrupoCompet='".$rSqlMat['idMaterGrupo']."' 
			 AND n.idAlumno=".$idAlum."
		GROUP BY i.idIndic,i.Indicador) r
	group by (r.CompetenciaIndic) ) R2
	group by R2.ValorCompetencia";
	
	//echo $sqlNotComp;
	
	$qSqlComp=mysql_query($sqlNotComp,$con) or die ("No se calcularon las competencias de la materia: ".$rSqlMat['idMaterGrupo'].". ".mysql_error()." ".$sqlNotComp);
	
	$PromMat=0;
	while($rSqlComp=mysql_fetch_array($qSqlComp)){
		$PromMat+=$rSqlComp['Valores'];
	}
	
	$Palabra=$MiJuicio->Palabra(intval($PromMat));
	
	if($PonerCaras==true) $imgCar=$MiJuicio->Carita($Palabra);
	
	echo "<b>".$Palabra." - ". intval($PromMat); if($PonerCaras==true) echo $imgCar; echo "</b>";
	
	?>
    </div><?PHP ////////////////////////////////// FIN CALCULO DE NOTA FINAL  //////////////////////////////?>
        
  </div><?PHP ///////////////////////////////// FIN MATERIA  //////////////////////////////?>
    
    
    <div class="Competencias"> <?php /////////////////////// COMPETENCIA  //////////////////////////////?>
    <?php
	$sqlComp="SELECT * FROM tbcompetencias 
		where PeriodoCompet=1 and MateriaGrupoCompet=".$rSqlMat['idMaterGrupo'];
	$qSqlComp=mysql_query($sqlComp, $con)or die("No se trajeron las competencias.".mysql_error()." ".$sqlComp);
	
	
	$iComp=1;
	
	while($rSqlComp=mysql_fetch_array($qSqlComp)){
		
		?>
        <div class="Competencia">
        <?php
		echo $iComp++ . ". " .$rSqlComp['Competencia'];
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
		WHERE c.PeriodoCompet=1
			 AND MateriaGrupoCompet='".$rSqlMat['idMaterGrupo']."' 
			 AND n.idAlumno=".$idAlum." and c.idCompet=".$rSqlComp['idCompet']."
		GROUP BY i.idIndic,i.Indicador) r
	group by (r.CompetenciaIndic)";
	
	//echo $sqlInd;
	
	$qSqlInd=mysql_query($sqlInd,$con) or die ("No se hizo el segundo calculo de las competencias de la materia: ".$rSqlMat['idMaterGrupo'].". ".mysql_error());

	$rSqlInd=mysql_fetch_array($qSqlInd);

		?>
        
        <?php /////////////////////////////////// NOTA COMPETENCIA //////////////////////////////?>
        <div class="NotaCompet">
            <div class="Det">
                <?php echo "(".$rSqlInd['PorcCompet']."%=".intval($rSqlInd['ValorCompPorc']).")";?>
            </div>
            <div class="NotaC">
                <?php echo $MiJuicio->Palabra(intval($rSqlInd['ValorComp']))." - ".intval($rSqlInd['ValorComp']);?>
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
		
		while($rSqlIndi=mysql_fetch_array($qSqlIndi)){
    	?>
        <div class="ContInd">
		<div class="NomIndicador"><?php echo $iInd++."-".$rSqlIndi['Indicador']; ?></div>
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

$sqlDisc="Select * from tbcomportamiento where MateriaGrupoComport=".$idGrupo. " and AlumnoComport=".$idAlum." and PeriodoComport=".$_SESSION['Per'];

$qSqlDisc=mysql_query($sqlDisc, $con)or die("No se trajo el comportamiento del alumno: ". $idAlum.". ".mysql_error()." ".$sqlDisc);

$rSqlDisc=mysql_fetch_array($qSqlDisc);

$idComportamiento=$rSqlDisc['idComport'];
?>

    <div class="NotaComportamiento"><b><?php echo $MiJuicio->Palabra($rSqlDisc['NotaComport'])." - ".$rSqlDisc['NotaComport']; if($PonerCaras==true) echo $MiJuicio->Carita($Palabra);?></b>
    </div>
    
</div>  <?php //////////////////////////  CIERRA BARRA COMPORTAMIENTO  //////////////////////////////////////?>


<div class="FrasesComport">
<?php

$sqlFraDisc="Select * from tbfrases f, tbfrasescomportamiento fc where f.idFrase=fc.idFrase and fc.idComportamiento=".$idComportamiento;

$qSqlFraDisc=mysql_query($sqlFraDisc, $con)or die("No se trajeron las frases de comportamiento. ".mysql_error()." ".$sqlFraDisc);

while($rSqlFraDisc=mysql_fetch_array($qSqlFraDisc)){

?>
	<div class="Frase"><?php echo $rSqlFraDisc['Frase']; ?></div>
    <div class="TipoFrase"><?php echo $rSqlFraDisc['TipoFrase']; ?></div>
    
<?php
}
?>
</div>

</div><?php //////////////////////////  CIERRA COMPORTAMIENTO  //////////////////////////////////////?>



<div class="TablaResumen">
<?php //////////////////////////  TABLA RESUMEN  //////////////////////////////////////?>
<?php


while($rSqlMat = mysql_fetch_array($qSqlMat)){
	
	$sqlPeriodos="select idPer, Periodo, Year from tbperiodos where Year=".$_SESSION['Year'];
	
	$qSqlPeriodos=mysql_query($sqlPeriodos, $con)or die("No se trajeron los periodos del año
		" .$_SESSION['Year'].". <br>" . mysql_error());
		
	while($rSqlPeriodos=mysql_fetch_array($qSqlPeriodos)){
		
		$sqlMalo="select n.Nota, n.idAlumno, i.idIndic, i.Indicador, c.idCompet, 
			from tbnotas n, tbindicadores i, tbcompetencias c, tbmateriagrupo mg,
				tbgrupoalumnos ga, tbalumnos a  
			where n.idIndic=i.idIndic and i.idIndic=c.idCompet and c.idCompet=mg.idMaterGrupo 
				and a.idAlum=ga.idAlumno and a.idAlum=n.idAlumno 
				and mg.idMaterGrupo=".$rSqlMat['idMateria']." and n.idAlumno=".$idAlum." 
				and ga.idPeriodo=".$rSqlPeriodos['Periodo']."
			and n.Nota<70 and idPeriodo=";
			
		$qSqlMalo=mysql_query($sqlMalo, $con)or die("No se trajeron las notas de la materia: 
			".$rSqlMat['idMateria'].". ".mysql_error());
		
		$ComentarioIndics="";
		$ContPerdidos=0;
		
		while($rSqlMalo=mysql_fetch_array($qSqlMalo)){
			
			$ComentarioIndics.=$rSqlMalo['Indicador']."=".$rSqlMalo['Nota']." \n";
			$ContPerdidos+=1;
			
			?>
			
			<div class="NomMatPerd" title="<? echo $ComentarioIndics; ?>">
				<? echo $rSqlMat['NombreMateria']; ?>
			</div>
			
			<div class="CantMatPer">
            	<? echo $ContPerdidos; ?>
            </div>
			
			<?			
			
			
		} //// while indicadores perdidos de la materia

		
	}  /// while periodos a recorrer
	

	
	
} //// while materias del alumno

?>

</div>




</div><?php //////////////////////////  BODY  //////////////////////////////////////?>


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

<?php
}       //////////////////////////////// FIN CADA BOLETIN

?>