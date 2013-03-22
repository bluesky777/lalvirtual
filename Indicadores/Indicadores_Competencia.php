<?php
require_once("../verificar_sesion.php");
require_once("clsIndicadores.php");

$Indis=new clsIndicadores();
$idComp=$_GET['idComp'];

if(isAdPr()){
?>

<div id="OpcionesIndic">
    <span  id="IngrNotas">
        <a href="<?php echo $_GET['idComp']; ?>">Notas</a>
    </span>
    <span  id="AgregarIndic">
        <a href="javascript:void(0);" id="agI:<?php echo $_GET['idComp']; ?>">Nuevo Indicador.</a>
    </span>
    
    <div class="OpcionesGeneIndic">
        <div class="Opcion">
            <span class="FondoOpt"><a href="javascript:void(0);"><span>Opciones</span></a></span>
        </div>
        <span class="SubOpcionesIndic">
            <span class="SubOpcionesIndic2">
                <span class="SubOpcionIndic"><a href="javascript:void(0);" id="PegarVarInd" title="(En contrucción)Puedes copiar desde word varios &#10;indicadores de una manera rápida e inteligente">Agregar varios indicadores</a></span>
                <span class="SubOpcionIndic"><a href="javascript:void(0);" id="AgregarInd" title="(En contrucción)Copia todos los indicadores incluyendo las notas si es el mismo grupo">Copiar indicadores en otra competencia.</a></span>
                <span class="SubOpcionIndic"><a href="Agregar_Tareas_AComp.php?idComp=<?php echo $_GET['idComp']; ?>" title="(En contrucción)Traer datos&#10; de una tarea incluyendo sus notas">Agregar tareas.</a></span>
                <span class="SubOpcionIndic"><a href="Borrar_Indicadores_Comp.php?idComp=<?php echo $_GET['idComp']; ?>" title="(En contrucción)Borrar todos &#10;los indicadores de esta competencia">Borrar indicadores.</a></span>
                <span class="SubOpcionIndic"><a href="Recuperar_Indicadores_Borrados.php?idComp=<?php echo $_GET['idComp']; ?>">Recuperar indicador borrado.</a></span>
            </span>
        </span>
        
    </div>
    
    <div class="clear"></div>
</div>

<?php
}
?>



<div id="ListadoIndicadores">


<div id="UlIndicadores">

    <ul>
                
<?php

$qsql=$Indis->gIndicadores($idComp);

while($rsql = mysql_fetch_array($qsql)){
    
?>
<li id="OrdenI_<?php echo $rsql['idIndic']; ?>">
    
    <div class="OrdenIndic" id="sIndOr<?php echo $rsql['idIndic']; ?>"><?php echo $rsql['OrdenIndic']; ?></div>
    <div class="ColumnaCentral" style="width:320px;">
        
        <span class="Indicador" title="<?php echo $rsql['Indicador']; ?>"><a href="../Alumnos_Notas.php?idComp=<?php echo $rsql['CompetenciaIndic']; ?>" id="sIndIn<?php echo $rsql['idIndic']; ?>"><?php echo $rsql['Indicador']; ?></a>
        </span> 

        <span class="PorcIndic" title="Este indicador vale un <?php echo $rsql['PorcIndic']; ?>% de la competencia seleccionada"  id="sIndPo<?php echo $rsql['idIndic']; ?>"><?php echo $rsql['PorcIndic']; ?></span>

        <span class="NotaDefecIndic" id="sIndDe<?php echo $rsql['idIndic']; ?>"><?php echo $rsql['NotaPorDefecto']; ?></span>

        <span class="FechIniIndic" id="sIndNi<?php echo $rsql['idIndic']; ?>"><?php echo date("d/m/Y h:i a",strtotime($rsql['FechaInicioIndic'])); ?></span>

        <span class="FechFinIndic" id="sIndFi<?php echo $rsql['idIndic']; ?>"><?php echo date("d/m/Y h:i a",strtotime($rsql['FechaFinIndic'])); ?></span>

        <span class="FechCreIndic" id="sIndCr<?php echo $rsql['idIndic']; ?>"><?php echo date("d/m/Y h:i a",strtotime($rsql['FechaCreacionIndic'])); ?></span> 
    
    </div>
    
    <div class="ColumnaDere">
        
        <?php
        if(isAdPr()){
        ?>
        <div class="OptIndic">

            <span class="icoEliIndic" id="Eli<?php echo $rsql['idIndic']; ?>" title="Eliminar indicador">
                <img src="../img/icono_eliminar.gif" width="17" height="22" />
            </span>

            <span class="icoEdiIndic" id="Edi<?php echo $rsql['idIndic']; ?>" title="Editar indicador">

                <img src="../img/icono_editar.png" width="17" height="22" />
 
            </span>

        </div><!-- Fin de las opciones de un indicador -->
        <?php
        }
        ?>
    </div>
</li>

<?php
}
?>


    </ul>


</div><!-- este es el div de UlIndicadores -->



</div> <!-- Fin Listado indicadores-->


<div id="RespuestaInd" class="Respuesta"></div>
<div id="EstPorcId" class="error" style="display: none;"></div>

<div class="PegarIndicadores">
    
    <form name="frmPegarIndicadores" id="frmPegarIndicadores">
        <label id="TituloPegarIndicadores">Crear varios indicadores</label>
        <input type="hidden" name="txtOrdenIndicPeg" id="txtOrdenIndicPeg" />
        
        <label for="txtIndicadorIndicPeg" id="lbNuevIndicadorPeg">Pegue en la caja de texto (desde Word, Excel, etc.) los indicadores que quiere añadir. Se permite hasta un máximo de 10 indicadores por operación</label>

        
        <textarea name="txtIndicadorIndicPeg" id="txtIndicadorIndicPeg" title="RECOMENDACIONES: &#10; 1) Pegue con CONTROL+V &#10; 2) Presione un Enter entre cada indicador. &#10; 3) No ponga simbolos como las viñetas. &#10; 4) Y no use comillas."></textarea>
        
        <div class="ConteIndicsPeg">
            <div class="conteIndicPeg">
                <span class="NuevIndicPegOr" id="NuevIndicPegOr1">-</span>
                <span class="NuevIndicadorPeg" id="NuevIndicadorPeg1">------</span>
                <span class="NuevIndicPegPo" id="NuevIndicPegPo1">0</span>
            </div>
            
            <div class="conteIndicPeg">
                <span class="NuevIndicPegOr" id="NuevIndicPegOr2">-</span>
                <span class="NuevIndicadorPeg" id="NuevIndicadorPeg2">------</span>
                <span class="NuevIndicPegPo" id="NuevIndicPegPo2">0</span>
            </div>

            <div class="conteIndicPeg">
                <span class="NuevIndicPegOr" id="NuevIndicPegOr3">-</span>
                <span class="NuevIndicadorPeg" id="NuevIndicadorPeg3">------</span>
                <span class="NuevIndicPegPo" id="NuevIndicPegPo3">0</span>
            </div>

            <div class="conteIndicPeg">
                <span class="NuevIndicPegOr" id="NuevIndicPegOr4">-</span>
                <span class="NuevIndicadorPeg" id="NuevIndicadorPeg4">------</span>
                <span class="NuevIndicPegPo" id="NuevIndicPegPo4">0</span>
            </div>
            
            <div class="conteIndicPeg">
                <span class="NuevIndicPegOr" id="NuevIndicPegOr5">-</span>
                <span class="NuevIndicadorPeg" id="NuevIndicadorPeg5">------</span>
                <span class="NuevIndicPegPo" id="NuevIndicPegPo5">0</span>
            </div>
            
            <div class="conteIndicPeg">
                <span class="NuevIndicPegOr" id="NuevIndicPegOr6">-</span>
                <span class="NuevIndicadorPeg" id="NuevIndicadorPeg6">------</span>
                <span class="NuevIndicPegPo" id="NuevIndicPegPo6">0</span>
            </div>
            
            <div class="conteIndicPeg">
                <span class="NuevIndicPegOr" id="NuevIndicPegOr7">-</span>
                <span class="NuevIndicadorPeg" id="NuevIndicadorPeg7">------</span>
                <span class="NuevIndicPegPo" id="NuevIndicPegPo7">0</span>
            </div>

            <div class="conteIndicPeg">
                <span class="NuevIndicPegOr" id="NuevIndicPegOr8">-</span>
                <span class="NuevIndicadorPeg" id="NuevIndicadorPeg8">------</span>
                <span class="NuevIndicPegPo" id="NuevIndicPegPo8">0</span>
            </div>

            <div class="conteIndicPeg">
                <span class="NuevIndicPegOr" id="NuevIndicPegOr9">-</span>
                <span class="NuevIndicadorPeg" id="NuevIndicadorPeg9">------</span>
                <span class="NuevIndicPegPo" id="NuevIndicPegPo9">0</span>
            </div>
            
            <div class="conteIndicPeg">
                <span class="NuevIndicPegOr" id="NuevIndicPegOr10">-</span>
                <span class="NuevIndicadorPeg" id="NuevIndicadorPeg10">------</span>
                <span class="NuevIndicPegPo" id="NuevIndicPegPo10">0</span>
            </div>
        </div>
        <br>
        <input type="reset" name="resetNuevoIndicPeg" id="cancelNuevoIndicPeg" value="Cancelar" />
        <input type="submit" name="submitNuevoIndicPeg" id="submitNuevoIndicPeg" value="Añadir" />
        
    </form>
</div>