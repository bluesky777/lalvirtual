
<!-- - - - - - - - - - - - - INICIA LA BARRA SUPERIOR - - - - - - - - -->

<div id="myBarra">

    <div id="myBarraInt">

        <div id="OptLogoMyVc">
            <a href="javascript:void(0);" onclick="alert('La página oficial aun está en contrucción.');"><img src="../img/MyVc-1.gif" /></a>
        </div>

       
        <div class="OptInicio">
            <a href="#">Inicio</a>
        </div>
    
        <div class="Separador"></div>
    
        <div class="Opt1">
    
            <div id="PeriodoNav">
        
                <span class="PeriodoNavMenu">
                    <a href="javascript:void(0);" class="myPer" title="Cambie de periodo a su gusto y recuerde verificar si es el correcto">Per <?php echo $_SESSION['Per'] . "-" . $_SESSION['Year']; ?></a>
                </span>

                <div class="PeriodoOpciones">
                    <?php
                    $qyear=mysql_query("select Year from tbyearcolegio", $con);
                    while($regyear=mysql_fetch_array($qyear)){
                    ?>
                
                        <div class="PeriodoOpcion">
                    
                            <span class="PeriodoYear">
                                Año <?php echo $regyear['Year']; ?>
                            </span>
                        
                            <div class="PeriodoPer">
                                <?php
                                $sqlper="select idPer, Periodo from tbperiodos where Year=" . $regyear['Year'];
                                $qper=mysql_query($sqlper, $con) or die("Problema al consultar Periodos. " . mysql_error());
                                
                                while($regper=mysql_fetch_array($qper)){
                                ?>
                                    <span id='<?php echo $regper['idPer']; ?>' class="Period">
                                        <a href="javascript:void(0);">Periodo <?php echo $regper['Periodo']; ?></a>
                                    </span>
                                <?php
                                } //while regper
                                ?>
                            </div>
                        </div>
                    <?php
                    } //while regyear
                    ?> 
                </div>
        
            </div>

        </div>


        <div id="BuscadorPeople">
            
            <div class="ui-widget">
              <label for="txtBuscardorPeople">Buscar</label>
              <input type="text" name="txtBuscardorPeople" id="txtBuscardorPeople" placeholder="Buscar personas" title="Esta busqueda puede ser demorada, la paciencia es una virtud." /> 
            </div>
          
        </div>

    	
        <div id="OptUsuario" title="Cambie sus opciones de usuario">
            <a href="javascript:void(0);" id="../MyUsuario.php?idUsu=<? echo $_SESSION['idUsuar']; ?>">
            
                <span class="imgUsu">
                    <img src="../img/<? echo $_SESSION['PerfilImg']; ?>" id="FotoUsu" title="Imagen de perfil.">
                </span> <!-- Termina .imgUsu -->
                
                
                <span class="TinyName">
                
                    <?php
                    if ($tipoU == 1)        {
                        echo ucfirst( "Manager: " . $_SESSION['Usuario'] );
                    } else {
                        $nombre=$_SESSION['NombresUsuar'] ." ". $_SESSION['ApellidosUsuar'];
                        echo ucfirst($nombre); 
                    }

                    ?>

            	</span>

            </a>

            <!-- <div class="clear"></div> -->
            
        </div>

        <div class="Separador"></div>

      
        <div id="OptCerrar">
            <a href="../cerrar_sesion.php">Salir</a>
        </div>


        <div class="clear"></div>

    </div>
</div>



<!-- - - - - - - - - - - - - FIN DE LA BARRA SUPERIOR - - - - - - - - -->