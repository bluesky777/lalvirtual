<?php
require_once("verificar_sesion.php");
require_once("php/clsPersonaTotal.php");

$person=new clsPersona();

$Cod=$_SESSION['idUsuar'];
$person->DatosxUsu($Cod);

$Login=$person->gLogin();
$Activo=$person->gActU();
$PerfilImg=$person->ImgPerf();
$PrincImg=$person->ImgPrinc();
$CmmPerf=$person->gCommImgPerf();
$CmmPrin=$person->gCommImgPrin();

$tipoU=$person->giTiU();

if($tipoU!=1){
    $SexoUsu=$person->gSexo();
}

$UbicaRel="../img/";
?>

<div id="FotosUsu">
	
<div id="Usuario">
    
    <span class="TituloMyUsu">My Fotos</span><div></div>
  
  <div class="ImgPerfil">
    <?php

    $srcPerfil=$PerfilImg;
    $titlePerfil=$CmmPerf; 
        
    echo "<img class='MyImgPerf' src='".$UbicaRel. $srcPerfil . "' title='".$titlePerfil."'>";
    
    if($PerfilImg!=$PrincImg){
      ?>
      <a href="javascript:void(0);" title="Establecer como imágen de perfil de usuario">Perfil</a>
      <?
    }
    
    ?>
      
    <div id="RespImgPerf"></div>

    <div class="SubidorFile">
        <form id="frmSubirPerf" method="POST" enctype="multipart/form-data" name="frmSubirPerf">
            <input type="file" name="flImgNuevPer" id="FlImgNuevPerf" /> 
            <input type="hidden" name="idUsu" value="<? echo $Cod; ?>" />
        </form>
    
    </div>
    
  </div>
    
  <div class="ImgPrincipal">
    <?php

    $srcPrin=$PrincImg;
    $titlePrin=$CmmPrin;

    echo "<div id='imgImgPrin'><img class='MyImgPrin' src='".$UbicaRel. $srcPrin . "' title='".$titlePrin."' ></div>";
    
    if($PerfilImg!=$PrincImg){
      ?>
      <a href="javascript:void(0);" title="Establecer como imágen principal para los informes.">Principal</a>
      
      <?
    }
    ?>
      
      <div id="RespImgPrin"></div>
      
      <div class="SubidorFile">
          <form id="frmSubirPrin" method="POST" enctype="multipart/form-data" name="frmSubirPrin">
              <input type="file" name="flImgNuevPr" id="FlImgNuevPrin" />
              <input type="hidden" name="idUsu" value="<? echo $Cod; ?>" />
              <input type="hidden" name="OperUsu" value="ImgPrin" />
          </form>
      </div>
    
  </div>


</div>



</div>


    
<div id="ConteMyUsu">

  <span class="TituloMyUsu">Datos de Usuario</span>


    <form name="DatosUsu" action="#" id="frmDatosUsu">

        <div class="tbc">

        <div class="row">
            <div class="cell">
                <label>Codigo</label>
            </div>
            <div class="cell">
                <label><?php echo $Cod; ?></label>
            </div>
        </div>
        <div class="row">
            <div class="cell">
                <label>Usuario</label>
            </div>
            <div class="cell">
                <input type="text" name="txtNombres" value="<?php echo $Login; ?>">
            </div>
        </div>
        <div class="row">
            <div class="cell">
                <label>Contraseña Actual</label>
            </div>
            <div>
                <input type="password" name="txtApellidos" id="PassAnt">
            </div>
        </div>
        <div class="row">
            <div class="cell">
                <label>Contraseña nueva</label></div>
            <div class="cell"><input type="password" name="txtDocu" id="Pass1"> 
                Confirma contraseña
                <input type="password" name="txtDocu2" id="Pass2"></div>
        </div>  

        <div class="row">
            <div class="cell">
                <label>Activo</label>
            </div>
            <div class="cell">
                
              <select name="txtAct">
                <option value="1" <? if( $Activo == true) echo "Selected"; ?> >Si</option>
                <option value="0" <? if( $Activo == false) echo "Selected"; ?> >No</option>
              </select>

            </div>
        </div>  
        
        <div class="row">
            <div class="cell">
                <input type="reset" value="Restablecer" />
                <input type="submit" id="SubmitInfoUsu" value="Guardar" />
            </div>
        </div>

        </div><!-- CIERRE DE LA TABLA-->

    </form>

</div>
    
    
<div id="ConteMyUsuPersonales">

  <span class="TituloMyUsu">Datos personales</span>


    <form name="DatosPersonales" action="#" id="frmDatosPersonales">

    <div class="tbc">

        <div class="row">
            <div class="cell">
                <label>Codigo Alumno</label>
            </div>
            <div>
                <label><?php echo $Cod; ?></label>
            </div>
        </div>
        <div class="row">
            <div class="cell">
                <label>No Matricula</label>
            </div>
            <div class="cell">
                <input type="text" name="txtNombres" value="<?php echo $Login; ?>">
            </div>
        </div>
        <div class="row">
            <div class="cell">
                <label>Nombres</label>
            </div>
            <div class="cell">
                <label><?php echo $Cod; ?></label>
            </div>
        </div>
        <div class="row">
            <div class="cell">
                <label>Apellidos</label>
            </div>
            <div class="cell">
                <input type="text" name="txtNombres" value="<?php echo $Login; ?>">
            </div>
        </div>
        
        <div class="row">
            <div class="cell">
                <label>Documento de identidad</label>
            </div>
            <div class="cell">
                <select>
                    <?
                    $sqlTipDoc="select * from tbtipodocu";
                    $qSqlTipDoc=mysql_query($sqlTipDoc, $con);
                    while ($rSqlTipDoc = mysql_fetch_array($qSqlTipDoc)) {
                        
                    ?>
                    <option value="<? echo $rSqlTipDoc['idTipoDoc'];?>"><? echo $rSqlTipDoc['InicialesDoc'];?></option>
                    <?           
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="cell">
                <label>Departamento de nacimiento</label></div>
            <div class="cell">
                <select>
                    
                    <option value="0">Colombia</option>
                    <option value="0">Venezuela</option>
                    <option value="0">Perú</option>
                    
                </select>
                <select>
                    <?
                    $sqlTipDoc="select * from tbtipodocu";
                    $qSqlTipDoc=mysql_query($sqlTipDoc, $con);
                    while ($rSqlTipDoc = mysql_fetch_array($qSqlTipDoc)) {
                        
                    ?>
                    <option value="<? echo $rSqlTipDoc['idTipoDoc'];?>"><? echo $rSqlTipDoc['InicialesDoc'];?></option>
                    <?
                                    
                    }
                    ?>
                </select>
            </div>
        </div>  

        <div class="row">
            <div class="cell">
                <label>Activo</label>
            </div>
            <div class="cell">
                
              <select name="txtAct">
                <option value="1" <? if( $Activo == true) echo "Selected"; ?> >Si</option>
                <option value="0" <? if( $Activo == false) echo "Selected"; ?> >No</option>
              </select>

            </div>
        </div>  
        
        <div class="row">
            <div class="cell">
                <input type="reset" value="Restablecer" />
                <input type="submit" id="SubmitInfoPerso" value="Guardar" />
            </div>
        </div>

    </div><!-- CIERRE DE LA TABLA-->

    </form>

</div>

