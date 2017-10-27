<?php
require_once('../verificar_sesion.php');
require_once('clsMaterias.php');
require_once('../php/clsItemUsuario.php');

$Mater=new clsMaterias();

$idUsu=$_GET['idUsu'];
$TipoUsu=$_SESSION['TipoUsu'];

if($TipoUsu == 2){
	?>

	<div id="MatTerminado" class="AnunciarNotas ab">
	    <a href="javascript:void(0);" id="../Anunciar_Fin_Profesor.php">
	    	<span>¿Ya terminaste?</span>
	    </a>
	</div>
	<?php
}
?>

<div class="ContPrincMater">  <!-- TODO EL CONTENIDO DE ESTA PÁGINA -->

	<span class="Titulo1">Materias:</span>
	
	<?php

	$qsql=$Mater->gMaterias($idUsu, $TipoUsu);
	
	while($rsql=mysqli_fetch_array($qsql)){
	
	$InfNot=$Mater->NotiCompet($rsql['idMaterGrupo']);
		
?>
  <div class="MaterList">
  
    <div class="MateriaListada" title="<?php if($TipoUsu==1 or $TipoUsu==2){echo $rsql['NombreGrupo'].': '. $InfNot['CEstado'];} ?>">
    
    <?php
    if (isAdPr()) {
    ?>
     <div class="<?php if($InfNot['CEstado']=="Completo") {echo "MaterLogoGradoAzul";}else{echo "MaterLogoGradoRojo";} ?>" title="<?php echo $rsql['NombreMateria']; ?>">
     
     	<span class="LogoGrado">
        	<?php echo $rsql['Grupo'];?>
        </span>
     </div>
    <?php
    }elseif(isEst()) {
    	$img=new clsItemUsuario();
    	$rutImg = "../img/".$img->gImgPerf($rsql['PerfilImg'], $rsql['idUsu'], $rsql['LoginUsu'], $TipoUsu, $rsql['SexoProf']); 
        if (!file_exists($rutImg))
    		$rutImg = $img->ImagenAlternativa($TipoUsu, $rsql['SexoProf']);
    ?>
      <div class="ImgSujetoDiv">
      	<span class="imgSujetoM">
      		<img src="<?php echo $rutImg; ?>"/>
      	</span>
      </div>
    <?php
    }
    ?> 
    
     <div class="MaterNombre">
    
	  <a href="../Competencias_Materia.php?idMat=<?php echo $rsql['idMaterGrupo'] . "&idProf=" . $idUsu."&idGrupo=".$rsql['idGrupo']; ?>" 
	  	<?php
	  	if($TipoUsu == 1 or $TipoUsu == 2){
			echo "title='".$rsql['NombreMateria']."'";
	  	}elseif($TipoUsu == 3 or $TipoUsu == 4){
	  		echo "title='Profesor: ".$rsql['NombresProf']." ".$rsql['ApellidosProf']."'"; 
	  	}
	  	?> class="Cargador">
			<?php 
			echo $rsql['NombreMateria'];

			if($TipoUsu == 1 or $TipoUsu == 2){
				echo " - <b>". $rsql['NombreGrupo'] ."</b>"; 
			}elseif($TipoUsu == 3 or $TipoUsu == 4){
				echo " - <b>". $rsql['NombresProf'] ." ".$rsql['ApellidosProf'] ."</b>"; 
			}
			?>
      </a>
      
     </div>
      
      <div class="NotifyCompet">
      	
        <span>
            <?php
            if ( $InfNot['CComp']==0){
                echo $InfNot['CComp'] . " competencias(" .$InfNot['CPorComp'] . "%) ";

            } elseif ($InfNot['CComp']==1) {
                echo $InfNot['CComp'] . " competencia(" .$InfNot['CPorComp'] . "%) ";

                if($InfNot['CInd']==1){
                    echo $InfNot['CInd']. " indicador(". $InfNot['CPorInd'] ."%) ";
                } else{
                   echo $InfNot['CInd']. " indicadores(". $InfNot['CPorInd'] ."%) ";
                }

            } else {
                echo $InfNot['CComp'] . " competencias(" .$InfNot['CPorComp'] . "%) "; 

                if($InfNot['CInd']==1){
                	echo $InfNot['CInd']. " indicador(". $InfNot['CPorInd'] ."%) ";
                } else{
                	echo $InfNot['CInd']. " indicadores(". $InfNot['CPorInd'] ."%) ";
                }

            }

            if(isAdPr()){
	            if ($InfNot['CAus']==1) {
	                echo "y ".$InfNot['CAus']." ausencia.";

	            } elseif(($InfNot['CAus']==0) or ($InfNot['CAus']=="")) {
	                echo "y 0 ausencias.";

	            } else {
	                echo "y ".$InfNot['CAus']." ausencias";
	            }
			}
            ?>
                
        </span>

      </div>
      
      
      
	  <div class="MaterOpts">
              
        <span class="OptMaterBt">
        	<a href="javascript:void(0);" class="btMatIzq MaterCompetencia" id="idMat:<?php echo $rsql['idMaterGrupo']; ?>"><span class="textMaterOpt">Competencias</span></a>
        	<a href="javascript:void(0);" class="<?php if(isAdPr()){ echo "btMatCentro";}else{echo "btMatDer";} ?> MaterActiv" id="../Actividades/Actividades.php?idMat=<?php echo $rsql['idMaterGrupo']; ?>"><span class="textMaterOpt">Actividades</span></a>
        	<?php
        	if(isAdPr()){
        	?>
        	<a href="javascript:void(0);" class="btMatCentro MaterAusencia" id="../Ausencias_Materia.php?idMat=<?php echo $rsql['idMaterGrupo']; ?>"><span class="textMaterOpt">Ausencias</span></a>
        	<a href="javascript:void(0);" class="btMatDer MaterSemestral" id="../Semestral_Alumno.php?idMat=<?php echo $rsql['idMaterGrupo']; ?>"><span class="textMaterOpt">Semestral</span></a>
        	<?php
        	}
        	?>
        </span>
      </div>
	</div>
        
  </div>        
	<?php
	}
	?>

<p>&nbsp;</p>

<?php
$num_r = 0;
if($TipoUsu == 1 or $TipoUsu == 2){ 
	$qSqlGr=$Mater->gComportamiento($idUsu);
	$num_r = mysqli_num_rows($qSqlGr);
}

if($num_r>0){

	if($num_r==1){
		?>
		<div class="Titulo1">
			Comportamiento:
		</div>
		<?php
		$rSqlGr=mysqli_fetch_array($qSqlGr);
		?>
		<div class="ComportList">
			<a href="../Comportamiento_Titular.php?idProf=<?php echo $idUsu. "&idGrupo=".$rSqlGr['idGrupo']; ?>">
		    Comportamiento de <?php echo $rSqlGr['Grupo'] ." - ". $rSqlGr['NombreGrupo']; ?>
		    </a>
		</div> 

		<?php
	}
		    
	if($num_r>1){
		?>
		<div class="Titulo1">Comportamientos:</div>
		<?php

		while($rSqlGr=mysqli_fetch_array($qSqlGr)){
		?>
			<div class="ComportList">
			<a href="../Comportamiento_Titular.php?idProf=<?php echo $idUsu. "&idGrupo=".$rSqlGr['idGrupo']; ?>">
		    	Grado: <?php echo $rSqlGr['Grupo'] ." - ". $rSqlGr['NombreGrupo']; ?>
		    </a>
			</div>    
		<?php
		}
	}



}
?>

<hr>
<br><br>
</div>

<?php


?>

