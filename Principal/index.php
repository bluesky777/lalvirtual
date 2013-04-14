<?php
require_once("../verificar_sesion.php");
require_once("../conexion.php");
require_once("encabezado.php");
 
$con=Conectar();

$tipoU=$_SESSION['TipoUsu'];

require_once("barra_superior.php");

?>

<input type="hidden" id="hdIdUsu" value="<?php echo $_SESSION['idUsuar']; ?>" />
<input type="hidden" id="hdTipoUsu" value="<?php echo $_SESSION['TipoUsu']; ?>" />

<div id="CentroPrinc">

<!-- - - - - - - - - - - - - INICIA EL MENU PRICIPAL - - - - - - - - -->


<div id="MenuPrincipal">

    <span class="imgUsuIzquierda">
        <div class="screenImg">
            <div class="intScreen">
                <img src="../img/<?php echo $_SESSION['PerfilImg']; ?>" title="Imagen de perfil">
            </div>
        </div>
    </span> <!-- Termina .imgUsu -->
    
    <span id="InforUsuario">
        
        <span class="NombreDeUsu"><?php echo $_SESSION['Usuario']?></span>
        
    </span>
    
    <div class="SepararMenu"></div>
    
  <ul id="MenuOpciones">
      
    <?php if ($tipoU == 1) ?>
    <li class="OpcionPrinc">	
        <a href="javascript:void(0);" id="../Notificaciones.php" class="OptPrinNotificaciones">Notificaciones</a>
    </li>
             
        <?php
        		
        if(in_array("Ver notas profesores", $_SESSION['Privilegios'][0]) or $tipoU==1){
		
        ?>
        
        <li class="OpcionPrinc">
            <a href="javascript:void(0);" title="Ver contenido de los profesores" onclick="oscurecer();">Profesores</a>
            <ul class="submenu">
        	<?php
                $sqlprof="SELECT idProf, NombresProf, ApellidosProf from tbprofesores p, tbyearprofesores yp 
                    WHERE p.idProf=yp.idProfesor 
                        and idYear=" . $_SESSION['Year'] . " order by NombresProf ";

                $q=mysql_query($sqlprof, $con) or die ("Problema al consultar profesores." . mysql_error());

                while($reg=mysql_fetch_array($q)){
                ?>
                    <li>
                        <a href="javascript:void(0);" id="MatProf:<?php echo $reg['idProf']; ?>" class="CargarMaterias" title="<?php echo $reg['NombresProf'] . " " .  $reg['ApellidosProf']; ?>"><?php echo $reg['NombresProf'] . " " .  $reg['ApellidosProf'];  ?></a>
                    </li>
                <?php
                }
                ?>

            </ul>
        </li>    
        <?php
        } 
        ?>
        
        <li class="OpcionPrinc">
            <a href="javascript:void(0);" id="../Anuncios.php" title="Revisar todos los anuncios" class="OptPrinAnuncios">Anuncios
            </a>
        </li>        
        
        <?php if($tipoU==2) { ?>
        
        <li  title="Ver materias y sus opciones" class="OpcionPrinc">
            <a href='javascript:void(0);' class="CargarMaterias">Materias</a>
        </li> 
        
        <?php } 

        if(in_array("Ver boletines", $_SESSION['Privilegios'][0]) or $tipoU==1)
        { 
        ?>
        
        <li class="OpcionPrinc"><a href="javascript:void(0);" id="../Informes/Elejir_Boletin.php" class="OptVerBoletin">Boletines</a></li>
        
        <?php 
        }


        if(in_array("Ver boletines finales", $_SESSION['Privilegios'][0]) or $tipoU==1)
        { 
        ?>
        
        <li class="OpcionPrinc"><a href="javascript:void(0);" id="../Informes/Boletin_Final/Elejir_Boletin_Final.php" class="OptVerBoletinFinal">Boletines finales</a></li>
        
        <?php 
        }
		?>
        
        
	<?php if (in_array("Editar estructura grupos", $_SESSION['Privilegios'][0]) or ($tipoU==1)){ ?>
        <li class="OpcionPrinc"><a href="javascript:void(0);">Editar</a>
            <ul class="subsubmenu2">
            	<li><a href="javascript:void(0);" id="../Grupos.php" class="OptGrupos">Grupos</a></li>
                <li><a href="../Alumnos.php" class="Opt2Alum">Alumnos</a></li>
                <li><a href="../Usuarios.php" class="Opt2Usu">Usuarios</a></li>
                <li><a href="javascript:void(0);" class="Opt2NotAl">Notas de alumnos</a></li>
                <li><a href="javascript:void(0);" class="Opt2Export">Exportar</a></li>
            </ul>
        </li>
      
        <?php } 
        
        if (isAdm()){
        ?>
        
        <li class="OpcionPrinc" id="OptElePlan">
            <a href="javascript:void(0);" id="../Informes/Elejir_Planilla.php">Planillas</a>
        </li>
        
        <?
        } 
        
        if (isAdPr() or isEst()){  ?>
        
        <li class="OpcionPrinc OptPuestos">
            <a href="javascript:void(0);">Puestos</a>
        </li>
        
        <?

        } 
        if (isPr()){         ?>
            <li class="OpcionPrinc OptPrinEdNtAlMenu">
                <a href="javascript:void(0);">Editar notas de alumno</a>
            </li>      <?php 
        }
        if (isEst() or isAcud()){         ?>
            <li class="OpcionPrinc">
                <a href="javascript:void(0);" class="CargarMaterias">Materias</a>
            </li> 
            <li class="OpcionPrinc OptPrinEdNtAlMenu">
                <a href="javascript:void(0);">Ver notas</a>
            </li>        <?php 
        }
        if (isEst()){ ?>
        
        <li class="OpcionPrinc">
            <a href="javascript:void(0);" id="../Informes/Boletin_Alumno.php?txtIdAlum=<?php  echo $_SESSION['idUsuar']; ?>" class="OptVerBoletinAl">Ver boletin
            </a>
        </li>
        
        <?php } ?>
      
      	
        <?php
            if(in_array("Crear periodos", $_SESSION['Privilegios'][0]) or $tipoU==1)
            { 
            ?>
		
        <li class="OpcionPrinc OptPrinEditPer"><a href='../Editar_Periodo.php' class="Cargador">Editar periodos</a></li>
        
        <?php 
        }
        ?>


</ul>
    
</div>


<!-- - - - - - - - - - - - FINALIZA EL MENU PRICIPAL - - - - - - - -->



<div class="wrapper">

<div id="MenuSuper">
    <input type="button" id="CrAnu" class="bt1" value="Crear anuncio" />
</div>

<div id="ContLoaded">
    
<div class="Cont">
    
<p>&nbsp;</p>
<p>
  <?php
		if (isset($_SESSION['PazySalvoAlum'])==1){
			//echo "<B>PAZ Y SALVO</B>";
			
		}
		
		?>
  Hola<?php if (isset($_SESSION['NombresUsuar'])){echo " ".$_SESSION['NombresUsuar'];}else{echo " ".$_SESSION['Usuario'];} ?>, te ruego disculpes todos los inconvenientes que encuentres en esta aplicación pues aun está en desarrollo, ten paciencia y verifica tus movimientos para evitar inconvenientes.</p>
<p>&nbsp;</p>
<p>Este sitio está adaptado para navegadores como Chrome y Firefox. Internet Explorer no soporta la tecnología usada en este sitio.</p>
<p>&nbsp;</p>
<p>Deseo que tu experiencia en este sitio sea lo más agradable posible.</p>
<p>&nbsp;</p>
<p>Atentamente,</p>
<p>Programador.</p>
<p>&nbsp;</p>
        <form action="../Informes/Boletin_Alumno.php" method="post" name="frmAlu" target="_blank">
        	<input type="hidden" value="<?php echo $_SESSION['idAlum']; ?>" name="txtIdAlum">
        </form>
<br />

<p><span class="Mensaje">My Virtual College será tu mundo educativo donde podrás desarrollar tus capacidades al máximo.</span></p>

</div> <!-- Fin Content-->

</div> <!-- Fin Contentido loaded-->


</div><!-- Fin wrapper -->

</div>

<div id="CuadroAnunciar" title="Hacer un anuncio" style="display: none;"></div>

</body>
</html>