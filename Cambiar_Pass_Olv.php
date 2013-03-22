<?php

require_once("conexion.php");


$con=Conectar();

?>


<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Recordar contraseña MyVc</title>

<script type="text/javascript" src="js/jquery.js"></script>

<script language="javascript">

$(document).ready(function () {
	
	var emailreg = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
	
	$("#frmObligadoEmail").submit(function (){
            
            if($(".email").val() == "" && $(".email").val() == ""){
                if( $(".email").val() == "" || !emailreg.test($(".email").val()) ){
                    $(".email").focus().after("<span class='error'>Ingrese un email correcto</span>");
                    return false;			
                }
                
            } else {
                
            }
                
                $.ajax({
                    type: 'POST',
                    url: "Cambiar_Pass_Olv_Guardar.php",
                    data: $(this).serialize(),
                    success: function(data){
                        switch(data){
                            case 'Exitoso':
                                Verificar(Pass);
                                break;
                            case 'ReCaptchaError':
                                $('#RespuestaFrmEmail').html("Error");
                                alert("El valor de seguridad que introduciste no coincide, intentalo de nuevo.");
                                window.location.reload();
                            default:
                                $('#RespuestaFrmEmail').html(data);

                        }

                    },
                    beforeSend: function(){
                            $('#RespuestaFrmEmail').html("<img src='img/loader-mini.gif'/><br/>");
                    },
                    error: function(){
                            $('#RespuestaFrmEmail').html("Hubo problemillas en la red");
                    }
                })
	
            return false;
	});
	

	$(".email").keyup(function(){
            if( $(this).val() != "" && emailreg.test($(this).val())){
                $(".error").fadeOut();			
                return false;
            }		
	});
});



</script>

<link href="css/CambiaPassOlv.css" rel="stylesheet" />


</head>

<body>
    <img src="img/MyVc.png" />
<h2>Recordar contraseña.</h2>
<p>Se enviará un mensaje a su correo con su usuario y su nueva contraseña al igual que un link para confirmar el cambio o rechazarlo
<a href="http://www.maestrosdelweb.com/editorial/md5/" target="_blank"></a></p>
            


<form name="frmObligadoEmail" action="#" class="contacto" id="frmObligadoEmail">
         
    <div>
        <label for="txtEmail">Coloque su dirección de correo electrónico</label>
        <input type="text" name="email" id="txtEmail" class="email" />
        <input type="hidden" name="Oper" value="PediEmail" />
    </div>
    
<?php
require_once('php/recaptchalib.php');
$publickey = "6Lem-dMSAAAAANt83F4PWZPL9WIqdpajOEeLaeQ6"; // you got this from the signup page
echo recaptcha_get_html($publickey);
?>
    
    <input type="submit" value="Enviar correo" class="boton"  />
    
    <div id="RespuestaFrmEmail"></div>
</form>



</body>
</html>
