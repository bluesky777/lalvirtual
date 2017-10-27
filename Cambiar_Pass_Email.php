
<?php

require_once("conexion.php");


$con=Conectar();

$Oper=$_GET['Oper'];
$idUsu=$_GET['idU'];

switch ($Oper) {
    case "y": 
        
        $sqlEm="select LoginUsu, ConfirmCodUsu from tbusuarios where ConfirmCodUsu='".$_GET['conf']."'";
        $qSqlEm=mysql_query($sqlEm, $con)or die("No se consultó el codigo de confirmación. ".  mysql_error());
        $rSqlEm=mysqli_fetch_array($qSqlEm);
        $Login=$rSqlEm['LoginUsu'];
        
        $num=mysql_numrows($qSqlEm);
        
        if($num>0){
            
            $pagina=' 
        <!DOCTYPE html>
        <html>
        <head>
            <title>Cambio de contraseña-MyVc</title>
            
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" ></script>
            <link href="css/CambiaPassOlv.css" rel="stylesheet" type="text/css" />
                
            <script>
                
            $(document).ready(function () {
                $("#frmGuaPass").submit(function (){
                
                    if($(".passw").val() == ""){
                        
                        $(this).focus().after("<span class=\'error\'>Ingrese contraseña</span>");
                        return false;
                    } else {
                    
                        if( !($("#txtPass").val() == $("#txtPassConf").val()) ){
                            $("#txtPassConf").focus().after("<span class=\'error\'>Las contraseñas deben coincidir</span>");
                            return false;			
                        } else {   

                            $.ajax({
                                type: "GET",
                                url: "Cambiar_Pass_Email.php",
                                data: $(this).serialize(),
                                success: function(data){

                                    $("#Resultado").html("Datos guardados exitosamente. <a href=\'http://www.lalvirtual.com\'>Entrar</a><br/>");

                                    $("#Resultado").html(data);

                                },
                                beforeSend: function(){
                                        $("#Resultado").html("<img src=\'img/loader-mini.gif\'/><br/>");
                                },
                                error: function(){
                                        $("#Resultado").html("Hubo problemillas en la red");
                                }
                            });
                            
                        }
                   }
                    
                   return false;
                });
                
            	$(".passw").keyup(function(){
                    if( $(this).val() != ""){
                        $(".error").fadeOut();			
                        return false;
                    }		
                });
                
            });
            </script>
        </head>
        <body>
        
            <form id="frmGuaPass" name="frmGuaPass">
            
            <h2>MyVc-Cambiar datos</h2>

            <label for="txtUsuar">Nombre de usuario</label>
            <input id="txtUsuar" type="text" value="'.$Login.'" name="txtUsuar" />
            <br />
            <label for="txtPass">Contraseña nueva</label>
            <input id="txtPass" type="password" value="" name="txtPass" class="passw" />
            <br />
            <label for="txtPassConf">Confirmar contraseña</label>
            <input id="txtPassConf" type="password" value="" name="txtPassConf" class="passw" />
            <br />
            <input type="hidden" value="Guardar" name="Oper" />
            <input type="hidden" value="'.$idUsu.'" name="idUsu" />
            
            <input type="submit" value="Guardar" class="boton" />

            </form>
            
            <div id="Resultado"></div>
            
        </body>
        </html>';
        }
        
        echo $pagina;
        
        break;
    
    case "n":
        $sqlEli="UPDATE `tbusuarios` SET `ConfirmCodUsu`='', `RejectCodUsu`='' WHERE `idUsu`='".$idUsu."';";
        $qSqlEli=  mysql_query($sqlEli, $con)or die("No se pudo eliminar la solicitud. ".  mysql_error());
        
        echo "Se ha eliminado la solicitud de cambio de contraseña. Recuerda que puedes bloquear temporalmente la opción 
            de resturar contraseña por seguridad.";
        
        break;
    
    case "Guardar";
        
        $idUsu=$_GET['idUsu'];
        $pass=md5($_GET['txtPass']);
        $Log=$_GET['txtUsuar'];
        
        $sqlUAnt="select LoginUsu from tbusuarios where `idUsu`='".$idUsu."';";
        $qSqlUAnt=  mysql_query($sqlUAnt, $con);
        $rSqlUAnt=  mysqli_fetch_array($qSqlUAnt);
        
        $sqlCam="UPDATE `tbusuarios` SET LoginUsu='".$Log."', `PassUsu`='".$pass."', `ConfirmCodUsu`='', `RejectCodUsu`='' WHERE `idUsu`='".$idUsu."';";
        
        $qSqlCam=  mysql_query($sqlCam, $con)or die("No se pudo guardar los datos. ".  mysql_error());
        
        $Carp="img/Usuarios/";
        
        rename($Carp.$rSqlUAnt['LoginUsu']."_".$idUsu, $Carp.$Log."_".$idUsu);
        
        echo "Exitoso";
        break;
}

?>
