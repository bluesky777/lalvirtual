<?php
require_once("../verificar_sesion.php");
require_once("clsPeriodos.php");

$Prd=new clsPeriodos();
?>

<!--  MENU CONTENEDOR  -->
<div class="MenuCont">
    
<?

$idPer=$_GET['idPer'];

$rSqlP=$Prd->gPeriodoYear($idPer);
$Peri=$rSqlP["Periodo"];
$Year=$rSqlP["Year"];
//echo "Peridooooo ".$Peri;
?>
    
<a href="../Periodos/Matricular_Alumnos_Periodo_Guardar.php?idPer=<? echo $idPer; ?>" title="Deja el periodo <? echo $Peri; ?> como el anterior" id="MatriAll">Matricular todos</a>

<div class="clearIz"></div>

<div id="RespPeriMatAll"></div>

<div class="clear"></div>

<!--  Contenedor de los alumnos del ANTERIOR  -->

<div class="ContPer">
<ul id="Grupos">
<?php

$qSqlGr=$Prd->gGrupoYear($Year);

while($rSqlGr=mysql_fetch_array($qSqlGr)){
	?>
	<li>
    	<?
    	$rSqlCant=$Prd->gCantAlum($rSqlGr['idGrupo'], ($Peri-1), $Year);

    	echo $rSqlGr['Grupo'] . " - ". $rSqlGr['NombreGrupo'] . "(". $rSqlCant[0] .")"; 
		
		$qSqlAl=$Prd->gAlumnos($rSqlGr['idGrupo'], ($Peri-1), $Year);
		
		if (($numAl=mysql_num_rows($qSqlAl))>0){
		?>
		
        <ul>
        
		<?
		while($rSqlAl=mysql_fetch_array($qSqlAl)){
			?>
			<li>
            	<? echo $rSqlAl['ApellidosAlum'] . " " . $rSqlAl['NombresAlum']; ?>
            </li>
			<?
		}
		
		?>
        </ul>
        <?
		}
        ?>
                      
        
    </li>

    	
	<?	
}
?>

</ul>

</div>
<!--  Fin periodo ANTERIOR -->


<!--  Contenidor de los alumnos del periodo ACTUAL -->

<div class="ContPer"> 

<ul id="Grupos">

<?php
$qSqlGr=$Prd->gGrupos($Year);

while($rSqlGr=mysql_fetch_array($qSqlGr)){
	?>
	<li>
    	<?
    	
		$rSqlCant=$Prd->gCantAlum2($rSqlGr["idGrupo"], $idPer);
			
    	echo $rSqlGr['Grupo'] . " - ". $rSqlGr['NombreGrupo'] . "(". $rSqlCant[0] .")"; 
		
		$qSqlAl=$Prd->gAlumnos2($rSqlGr['idGrupo'], $idPer);
		
		if (($numAl=mysql_num_rows($qSqlAl))>0){
		?>
		
        <ul>
        
		<?
		
		while($rSqlAl=mysql_fetch_array($qSqlAl)){
			?>
			<li>
            	<? echo $rSqlAl['ApellidosAlum'] . " " . $rSqlAl['NombresAlum']; ?>
            </li>
			<?
		}
		
		?>
        </ul>
        <?
		}
        ?>
               
        
    </li>

    	
	<?	
}
?>

</ul>

</div>
<!--  Fin periodo ACTUAL -->

<div class="clear"></div>

<div>

<a href="../Grupos.php" id="MatrPorGrupo">Matricular por grado </a>

</div>


</div> <!--  MENU CONTENEDOR  -->

<?
$Prd->Cerrar();
?>
