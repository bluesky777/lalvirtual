<?php
require_once("../verificar_sesion.php");
require_once("clsCompetencias.php");

$Cmp = new clsCompetencias();

$idMatG=$_GET['idMatG'];

?>

<div class="ContPrincCompet">  <!-- TODO EL CONTENIDO DE ESTA PÁGINA -->

<div id="ContCompetIndic"><!-- ContCompetIndic -->

        
<div id="ListaCompetencias">
<?php
if (isAdPr()){
?>
<span id="AgregarComp" class="ab"> 
    <a href="javascript:void(0);" id="idMtr:<?php echo $idMatG; ?>">Agregar Competencia</a>
</span> 
<?php
}
?>
<div class="clear"></div>

    <div id="UlCompetencias">
        
        <ul>      

<?php

	$qComp=$Cmp->gCompetencias($idMatG);
	
	$numC=mysql_num_rows($qComp);

	if($numC>0){
		
		?>
            
        <?php
        while ($rComp=mysql_fetch_array($qComp)){
            $idTe = $rComp['idCompet'];
        ?> 
            
        <li id="OrdenC_<?php echo $idTe; ?>">
            
            <div class="CompetColUno">
            
            <span class="OrdenYcompet">
                <span class="OrdenComp" id="sComOr<?php echo $idTe; ?>"><?php echo $rComp['OrdenCompt']; ?></span>
                <span class="Competen" id="sCompe<?php echo $idTe; ?>" title="<?php echo $rComp['Competencia'];?>">
                    <a href="javascript:void(0);"><?php echo $rComp['Competencia']; ?></a>
                </span>
            </span>
                <span class="PorComp" id="sComPo<?php echo $idTe; ?>" title="La competencia vale un <?php echo $rComp['PorcCompet']; ?>% de la materia"><?php echo $rComp['PorcCompet']; ?></span><!-- Si no lo pego aparece el porcentaje separado-->
                <span class="FechCre" id="sCompCr<?php echo $idTe; ?>" title="Fecha Creación: <?php echo $rComp['FecCre']; ?>"><?php echo date("d/m/Y",strtotime($rComp['FecCre'])); ?>
                </span>
                <?php
                if (isAdPr()){
                ?>
                <span class="OptCompet">
                    <span class="icoEliComp" id="Eli<?php echo $rComp['idCompet']; ?>" title="Eliminar competencia">
                        <img src="../img/icono_eliminar.gif" width="16" height="21" style="cursor:pointer" title="Eliminar competencia" />
                    </span>
                    <span class="icoEdiComp" id="Edi<?php echo $rComp['idCompet']; ?>" title="Editar competencia">
                        <img src="../img/icono_editar.png" width="20" height="22" />
                    </span>
                </span>
                <?php
                };
                ?>
             </div>  <!-- Fin Columna Uno -->
             
             <div class="CompetColDos">
                
                <span>
                    <a href="javascript:void(0);" id="idComp:<?php echo $rComp['idCompet']; ?>" title="Mostrar indicadores">
                        <img src="../img/FlechaVerIndic.png">
                    </a>
                </span>
                
             </div><!-- Fin Columna Dos-->
             
			</li>         
		<?php
		};
		?>
        
		</ul>

	</div> <!--  Fin Div id=ListaCompetencias -->
    


    <?php
	} else {
        echo "Aun no ha agregado competencias.";
	}
	?>


<div id="RespuestaComp" class="Respuesta"></div>
<div id="EstPorcComp" class="error" style="display: none;"></div>


</div>

</div>  <!-- ContCompetIndic -->
        
        
    <!----------------- INDICADORES EN FORMA AJAX ------------------------>
        
    <div id="ContenedorIndicadores">
        <span class="InfoIndicTemp">
            <span>
            Seleccione una competencia para ver sus indicadores. Ordene de forma sencilla tal como quiere que aparezca en el boletin.
            </span>
        </span>
    </div> <!-- Fin div de ContenedorIndicadores -->
    
    
    <div class="clear"></div> 
    
        
</div>    <!-- Fin div de Contenedor de Competencias e Indicadores --> 
        
    <div class="clear"></div>
    
    

    
    <p><b>Opciones:</b>
    </p>
    
    <form name="frmCopiarMat" id="frmCopiarMat" method="post" action="#">
    <input type="hidden" value="<?php echo $idMatG; ?>" name="txtIdMat">Copiar competencias a la materia 
    	<select name="txtMateria" id="SelMateria">
    	  <?php
	

	$sql="select m.idMateria, mg.idMaterGrupo, NombreMateria, g.Grupo, g.idGrupo
		from tbmateriagrupo mg, tbgrupos g, tbmaterias m 
		where mg.idProfesor='". $_SESSION['idUsuar']."' and g.idGrupo=mg.idGrupo and m.idMateria=mg.idMateria and g.YearGrupo='". $_SESSION['Year']."'
		order by g.Grupo";
//echo $sql;

	$qsql=mysql_query($sql, $Cmp::$conex) or die("No hay materias. " . mysql_error() . "<br>" . $sql);
	while($rsql=mysql_fetch_array($qsql)){
		?>
    	  <option value="<?php echo $rsql['idMaterGrupo']; ?>"><?php echo $rsql['NombreMateria'] . " - ". $rsql['Grupo']; ?></option>
    	  <?php
		}
		?>
  	  </select>
    	y al periodo
    	<select name="txtPeriodo" id="txtPeriodo">
    	  <?php
		
	$sql="select idPer, Periodo from tbperiodos where Year='". $_SESSION['Year']."'
		order by idPer";
	
	$qsql=mysql_query($sql, $Cmp::$conex) or die("Pailitas, no hay periodos. " . mysql_error() . "<br />" . $sql);
	while($rsql=mysql_fetch_array($qsql)){
		?>
        <option value="<?php echo $rsql['idPer']; ?>"><?php echo $rsql['Periodo']; ?></option>
        <?php
		};
		?>
  	  </select>
        <input type="submit" value="Copiar">
    </form>
    
    <div id="RespCopyComp"></div>
    
