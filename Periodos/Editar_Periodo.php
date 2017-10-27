<?php
require_once("../verificar_sesion.php");
require_once("clsPeriodos.php");

$Prd=new clsPeriodos();
/*
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/Editar_Periodo.js"></script>

<style type="text/css" title="currentStyle">
    @import "css/Crear_Per.css";
</style>


<title>Editar periodos</title>
</head>

<body>
*/
?>
<input type="hidden" name="idPerH" id="idPerH" value="<?php echo $_SESSION['PeriodoUsu']; ?>" >
<div id="tbPeriodos">
    <div class="TituFilaPer">
    	<span class="TituColPer">
            COD
        </span>
        <span class="TituColPer">
            AÑO
        </span>
    	<span class="TituColPer">
            PERIODO
        </span>
        <span class="TituColPer">
            FECHA INICIO
        </span>
       	<span class="TituColPer">
            FECHA FIN
        </span>
        <span class="TituColPer">
            OPCIONES
        </span>
    </div>
    <?php

	$qSqlP=$Prd->gPeriodos();
	while($rSqlP=mysqli_fetch_array($qSqlP)){
	
	?>
    <div class="FilasPer">
    	<span>
        	<label><?php echo $rSqlP['idPer']; ?></label>
        </span>
    	<span>
        	<label><?php echo $rSqlP['Year']; ?></label>
        </span>
        <span class="ColumPeriodo">
        	<label><?php echo $rSqlP['Periodo']; ?></label>
        </span>
       	<span>
        	<label><?php echo $rSqlP['FechaInicio']; ?></label>
        </span>
        <span>
        	<label><?php echo $rSqlP['FechaFin']; ?></label>
        </span>
        <span>
            <a href="javascript:void(0);" id="<?php echo $rSqlP['idPer'];?>" class="EditarPer" >Editar</a>
            <a href="javascript:void(0);" id="<?php echo $rSqlP['idPer'];?>" class="Matricular" >Matricular</a>
        </span>
    </div>
    
    <?php
	}
    ?>

</div>
<div id="RespContePeri">
    
</div>
<br>
<hr>




  <form class="NuevoPer">
  	<div>
    <p>Año
  <select id="slYear">
  <?php

$qSqlY = $Prd->gYear();;

while($rSqlY=mysqli_fetch_array($qSqlY)){
	if ($rSqlY['ActualYear']==1){
		?>
    <option selected="selected"><?php echo $rSqlY['Year']; ?></option>
    <?php
	} else {
		?>
    <option><?php echo $rSqlY['Year']; ?></option>
    <?php
	}
}
?>
  </select>

	<label>Periodo</label>
        <select id="slPeriodo">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
        </select>
	</div>
   	
    <div>
	<label>Fecha inicio</label>
	<input type="text" name="txtFechIni" id="txtFechIni" class="txtFechIni" />
        
    </div>
    
    <div>
		<label>Fecha fin</label>
		<input type="text" name="txtFechFin" id="txtFechFin" class="txtFechFin" />
        
    </div>
        
    <div>
		<label>Actual</label>
        <select id="slActual">
        	<option value="1">Si</option>
            <option value="0">No</option>
        </select>
        
    </div>

    <input type="submit" value="Crear Periodo" id="btnCrPeriodo">
  </form>

<br>
<hr>
<?php
$Prd->Cerrar();
?>