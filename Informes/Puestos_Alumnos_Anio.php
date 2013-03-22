<?php
require_once("../verificar_sesion.php");
require_once("clsPorcentajesAnio.php");


$Calcs=new clsPorcentajesAnio();
$Calcs->Conectar();
?>

<html>
<head>
<?php 
if(file_exists("../Informes/css/Puestos_Alumnos.css")){
	echo '<link rel="stylesheet" type="text/css" href="../Informes/css/Puestos_Alumnos.css">';
	echo '<script type="text/javascript" src="../Informes/js/Puestos.js" ></script>';
}else{
	echo '<link rel="stylesheet" type="text/css" href="css/Puestos_Alumnos.css">';
	echo '<link rel="stylesheet" type="text/css" href="../Principal/reset.css">';
	echo '<script type="text/javascript" src="js/Puestos.js" ></script>';

	if ($_SERVER['HTTP_HOST']=="lalvirtual.com" or $_SERVER['HTTP_HOST']=="www.lalvirtual.com"){    
	?>
	    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" ></script> 
	    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script> 
	<?php
	}else{
	?>
	    <script type="text/javascript" src="../js/jquery-1.7.2.min.js" ></script>
	    <script type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script>
	<?php
	}
}
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Puestos</title>
</head>

<body>

<center>


<p>
  <?php

$idGrupo = (isset($_GET['idGrupo'])) ? $_GET['idGrupo'] : 10 ;

echo '<input type="hidden" value="'.$idGrupo.'" id="hdIdGrupPuest">';

$Period=$Calcs->gLastPeriodo($idGrupo);
$qSqlContAl=$Calcs->gContAlumnosxNomGrupo($idGrupo, $Period);

$rSqlAl=mysql_fetch_assoc($qSqlContAl);

$NomGr=$rSqlAl['NombreGrupo'];

$ContAlu=$rSqlAl['cuantos'];

if ($ContAlu==0){
    echo "<div class='AnunNoAlum'>Aun no hay alumnos matriculados en el periodo <b>". $Period ."</b> para el grupo ".$NomGr=$rSqlAl['NombreGrupo']."</div>";
    die();
}

?>
<br>
 <b>PORCENTAJES DEL AÑO <?php echo $_SESSION['Year'];?><BR>
  GRADO <?php echo $NomGr; ?>
 </b></p>
<div class="tbc">
  <div class="row">
    <div class="cell"><b>No</b></div>
    <div class="cell"><b>ALUMNOS</b></div>
    <?
	$qSqlNomMat = $Calcs->gAbrevMatxGrupo($idGrupo);
	
	$MatCods=array();
	while($rSqlNomMat=mysql_fetch_array($qSqlNomMat)){
		
		$MatCods[]=$rSqlNomMat['idMaterGrupo'];
		?>
        <div class="cell" width="26"><b><? echo $rSqlNomMat['AbreviaturaMateria']; ?></b></div>
		<?
	}
	
	?>
    <div class="cell" width="53"><b>TOTAL</b></div>
  </div>
  
<?php

$i=1;
$Listados=array();

$qSqlAl=$Calcs->gPromedioxAlum($idGrupo, $Period);
$i = $sw = $PorcGr = 0;

while($rSqlAl=mysql_fetch_array($qSqlAl)){

  ?>
  <div class="row <? if($sw==0){ echo "cAlter"; $sw=1;}else{ echo $sw=0; }?>" >

    <div class="cell"><?php echo ++$i; //$i++;?></div>
    <div class="cell"><span class="nomCell"><?php echo $rSqlAl['NombresAlum']." ".$rSqlAl['ApellidosAlum']; ?></span></div>

  <?php 
  
	$Porcentaje = $ContPorc = $ContIndic = 0;
  
  foreach($MatCods as $id_Mat => $MatCod){

	 ?>
      
      <div class="cell"><?php // Celda en que aparece el porcentaje y logros por materia ?>
	  
	  <? 
	  	echo str_replace(".0", "", number_format($Calcs->gPromxAluxMatxAnio($MatCod, $rSqlAl['idAlumno']), 1));

		$ComentarioIndics="";
		$ContPerdidos=0;
		
		$qSqlMalo=$Calcs->gNotasPerdidas($MatCod, $rSqlAl['idAlumno']);
		
		while($rSqlMalo=mysql_fetch_array($qSqlMalo)){

			$ComentarioIndics.="Per".$rSqlMalo['PeriodoCompet']."-".$rSqlMalo['Indicador']." =".$rSqlMalo['Nota']." \n";
			$ContPerdidos+=1;
			
		} //// while indicadores perdidos de la materia
		
		$ContIndic+=$ContPerdidos;
		
		if ($ContPerdidos>0){
		?><a href="javascript:void(0);" title="<? echo $ComentarioIndics; ?>" class="ComentsPers" style="font-size:8px;">(<? echo $ContPerdidos;?>)</a>
		<?
		}
		?>
	  </div> <!-- Celda en que aparece el porcentaje y logros por materia-->
      
      <?
	  
  }
  $PromAluTotal = $rSqlAl['PromedioAlumTotal'];
  $PorcGr += $PromAluTotal;
//********************  PUNTAJE TOTAL DEL ALUMNO **************************
?>
    <div class="cell colTot"><? echo number_format($PromAluTotal, 2); ?>%
    <span title="Total de indicadores pendientes" style="font-size:8px;"><?php if ($ContIndic>0) echo "(".$ContIndic.")"; ?></span></div>

  </div>
<?


}

  ?>
  <div class="row">
  	<div class="cell"></div>
  	<div class="cell" colspan="2"><b>TOTAL</b></div>
    <?
	foreach($MatCods as $id_Mat => $MatCod){
		?>
     
     <div class="cell"></div>
        
        <?
	}
	?>
  	<div class="cell"><b><? echo number_format($PorcGr/$ContAlu, 2); ?></b></div>
  </div>

</div>

<input type="button" id="btPuestPdf" value="Ver PDF">
<input type="button" id="btPuestExc" value="Ver Excel">

<div class="piepag">
	Los alumnos mostrados son los activos en el periodo <?php echo $Period; ?><br>
	Los números entre paréntesis son la cantidad de Indicadores pendientes.<br>
	&lt;Reporte generado en My Virtual College&gt;
</div>
</center>

<div id="DiagV" style="display: none">
	<span class="CerrarDiag"><a href="" title="Cerrar cuadro de dialogo">X</a></span>
	<div class="titulo1">Indicadores pendientes</div>
	<div id="ContPer"></div>
</div>

</body>
</html>

<?php
$Calcs->Cerrar();
?>