<?php
require_once("../verificar_sesion.php");
require_once("clsPorcentajes.php");


$Calcs=new clsPorcentajes();
$Calcs->Conectar();
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Puestos</title>
</head>

<body>

<center>


<p>
<?php
$idGrupo = (isset($_GET['idGrupo'])) ? $_GET['idGrupo'] : 10 ;

echo '<input type="hidden" value="'.$idGrupo.'" id="hdIdGrupPuest">';

$qSqlContAl=$Calcs->gContAlumnosxNomGrupo($idGrupo);

$rSqlAl=mysql_fetch_array($qSqlContAl);

$NomGr=$rSqlAl['NombreGrupo'];

$ContAlu=$rSqlAl['cuantos'];

if ($ContAlu==0){
    echo "<div class='AnunNoAlum'>Aun no hay alumnos matriculados en el periodo <b>". $_SESSION['Per'] ."</b> para el grupo ".$NomGr=$rSqlAl['NombreGrupo']."</div>";
    die();
}


?>
<br>
 <b>PORCENTAJES ALCANZADOS DURANTE EL PERIODO <?php echo $_SESSION['Per'];?> - <?php echo $_SESSION['Year'];?><BR>
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
        <div class="cell" width="26"><b><?php echo $rSqlNomMat['AbreviaturaMateria']; ?></b></div>
		<?
	}
	
	?>
    <div class="cell" width="53"><b>TOTAL</b></div>
  </div>
  
<?php

$i=1;
$Listados=array();

$qSqlAl=$Calcs->gPromedioxAlum($idGrupo);

$i = $sw = $PorcGr = 0;

while($rSqlAl=mysql_fetch_array($qSqlAl)){

  ?>
  <div class="row <?php if($sw==0){ echo "cAlter"; $sw=1;}else{ echo $sw=0; }?>" >

    <div class="cell"><?php echo ++$i; //$i++;?></div>
    <div class="cell"><span class="nomCell"><?php echo $rSqlAl['NombresAlum']." ".$rSqlAl['ApellidosAlum']; ?></span></div>

  <?php 
  
	$Porcentaje = $ContPorc = $ContIndic = 0;
  
  foreach($MatCods as $id_Mat => $MatCod){

	$qSqlComp = $Calcs->gDefinitivaAlum($MatCod, $rSqlAl['idAlumno']);

	$rSqlComp=mysql_fetch_array($qSqlComp)

	  ?>
      
      <div class="cell"><?php // Celda en que aparece el porcentaje y logros por materia ?>
	  
	  <?php 
	  	echo str_replace(".0", "", number_format($rSqlComp['Valores'], 1));

		$ComentarioIndics="";
		$ContPerdidos=0;
		
		$qSqlMalo=$Calcs->gNotasPerdidas($MatCod, $rSqlAl['idAlumno']);
		
		while($rSqlMalo=mysql_fetch_array($qSqlMalo)){

			$ComentarioIndics.=$rSqlMalo['Indicador']." =".$rSqlMalo['Nota']." \n";
			$ContPerdidos+=1;
			
		} //// while indicadores perdidos de la materia
		
		$ContIndic+=$ContPerdidos;
		
		if ($ContPerdidos>0){
		?><a href="javascript:void(0);" title="<?php echo $ComentarioIndics; ?>" style="font-size:8px;">(<?php echo $ContPerdidos;?>)</a>
		<?
		}
		?>
	  </div> <!-- Celda en que aparece el porcentaje y logros por materia-->
      
      <?
	  
  }

  $PorcGr += $rSqlAl['PromedioAlum'];
?>

    <div class="cell"><?php echo number_format($rSqlAl['PromedioAlum'], 2); ?>%
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
  	<div class="cell"><b><?php echo number_format($PorcGr/$ContAlu, 2); ?></b></div>
  </div>

</div>
<input type="button" id="btPuestPdf" value="Ver PDF">
<input type="button" id="btPuestExc" value="Ver Excel">

<div class="piepag">
	Los números entre paréntesis son la cantidad de Indicadores pendientes. <br>&lt;Reporte generado en My Virtual College&gt;
</div>
</center>
</body>
</html>

<?php
$Calcs->Cerrar();
?>