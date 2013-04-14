<?php
require_once("../../verificar_sesion.php");
require_once("../clsPorcentajesAnio.php");


$Calcs=new clsPorcentajesAnio();
$Calcs->Conectar();

$idGrupo = (isset($_GET['idGrupo'])) ? $_GET['idGrupo'] : 10 ;

echo '<input type="hidden" value="'.$idGrupo.'" id="hdIdGrupPuest">';
$Period=$Calcs->gLastPeriodo($idGrupo);
$NomGr = $Calcs->gNombreGrupo($idGrupo);

$TablaPuestos=array();
$TablaMaterias=array();
$PromGrupo=0;
$Calcs->gtbPuestos($idGrupo, $TablaPuestos, $TablaMaterias, $PromGrupo);

?>
<center>
<br>
 <b>PORCENTAJES DEL AÑO <?php echo $_SESSION['Year'];?><BR>
  GRADO <?php echo $NomGr; ?>
 </b></p>

  <?
  foreach ($TablaPuestos as $KeyAlu => $valAlu) {
	?>
	<?php

		?>
  <div class="row">
    <div class="cell"><b>Puest</b></div>
    <div class="cell"><b>ALUMNO</b></div>
    <?
    foreach ($TablaMaterias as $KeyMat => $valMat) {
		
		?>
        <div class="cell" width="26"><b><?php echo $valMat['AbreviaturaMateria']; ?></b></div>
		<?
	}
	?>
	<div class="cell" width="53"><b>TOTAL</b></div>
  </div>
  <div class="row">
    <div class="cell" width="26"><?php echo $valAlu['NO']; ?></div>
        <div class="cell" width="26"><?php echo $valAlu['NombresAlum']." ".$valAlu['NombresAlum']; ?></div>
      <?
	  foreach ($valAlu['Materias'] as $KeyDef => $valDef) {
	  ?>
	  	<div class="cell" width="26"><?php echo $valDef['Definitiva']; ?></div>
      <?
	  }
	  ?>
        <div class="cell" width="26"><b><?php echo $valAlu['PromedioAlum']; ?></b></div>
		<?
	?>
	</div>
	<?php
	}
	?>
	<div class="row">
	  	<div class="cell"></div>
	  	<div class="cell" colspan="2"><b>TOTAL</b></div>
	<?php
    foreach ($TablaMaterias as $KeyMat => $valMat) {
		?>
        <div class="cell" width="26"></div>
		<?
	}
	?>
	<div class="cell" colspan="2"><b><?php echo number_format($PromGrupo, 2); ?></b></div>
  </div>

</div>

<div class="piepag">
	Los alumnos mostrados son los activos en el periodo <?php echo $Period; ?><br>
	Los números entre paréntesis son la cantidad de Indicadores pendientes.<br>
	&lt;Reporte generado en My Virtual College&gt;
</div>

<input type="button" id="btPuestAnioFirPdf" value="Ver PDF">
<input type="button" id="btPuestAnioFirExc" value="Ver Excel">
