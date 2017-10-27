
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
    
            <div id="YearNav">
        
                <span class="YearNavMenu">
                    <a href="javascript:void(0);" class="myYear" title="Cambie de año a su gusto y recuerde verificar si es el correcto">Año <?php echo $_SESSION['Year']; ?></a>
                </span>

                <div class="YearOpciones">
                    
                    <span id='2012' class="Yea">
                        <a href="javascript:void(0);">2012</a>
                    </span>
                    <span id='2013' class="Yea">
                        <a href="javascript:void(0);">2013</a>
                    </span>
                    <span id='2014' class="Yea">
                        <a href="javascript:void(0);">2014</a>
                    </span>
                    
                </div>
        
            </div>

        </div>


        <div class="Opt2">
    
            <div id="PeriodoNav">
        
                <span class="PeriodoNavMenu">
                    <a href="javascript:void(0);" class="myPer" title="Cambie de periodo a su gusto y recuerde verificar si es el correcto">Per <?php echo $_SESSION['Per']; ?></a>
                </span>

                <div class="PeriodoOpciones">
                
                    <div class="PeriodoOpcion">
                
                        <div class="PeriodoPer">
                            <?php
                            $sqlper="select idPer, Periodo from tbperiodos where Year=" . $_SESSION['Year'];
                            $qper=$con->query($sqlper) or die("Problema al consultar Periodos. " . mysqli_error($con));
                            
                            while($regper=mysqli_fetch_array($qper)){
                            ?>
                                <span id='<?php echo $regper['idPer']; ?>' class="Period">
                                    <a href="javascript:void(0);">Periodo <?php echo $regper['Periodo']; ?></a>
                                </span>
                            <?php
                            } //while regper
                            ?>
                        </div>
                    </div>
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
            <a href="javascript:void(0);" id="../MyUsuario.php?idUsu=<?php echo $_SESSION['idUsuar']; ?>">
            
                <span class="imgUsu">
                    <img src="../img/<?php echo $_SESSION['PerfilImg']; ?>" id="FotoUsu" title="Imagen de perfil.">
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