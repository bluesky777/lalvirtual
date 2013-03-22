<?
session_name("LoginUsuario"); 
session_start(); //iniciamos la sesión 

//Compruebo que el usuario está logueado 
if (isset($_SESSION["UltimoAcceso"])){

    $fechaGuardada = $_SESSION["UltimoAcceso"]; 
    $ahora = date("Y-n-j H:i:s"); 
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 
	
	if($tiempo_transcurrido >= 12121200) { 
		//si pasaron 10 minutos (600 seg) o más 
		session_destroy(); // destruyo la sesión 
	}else { 
		//sino, actualizo la fecha de la sesión 
		$_SESSION["UltimoAcceso"] = $ahora; 
		header("location: Principal/index.php"); //Nos vamos a la página principal.
	} 
} 
?>
<!DOCTYPE html>
<html>
<head>
<meta name="google-site-verification" content="8e__tSALMv-vNPjFkLWsi1APkJwO5Wc9cjPabYcVRR8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Colegio virtual</title>

<link href="img/favicon.ico" type="image/x-icon" rel="shortcut icon" />
<link href="Entrada/css/front.css" media="screen, projection" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/EncripMD5.js"></script>
<script src="Entrada/js/jquery.tipsy.js" type="text/javascript"></script>


    <!-- jmpress plugin -->
    <script type="text/javascript" src="Entrada/SlideshowJmpress/js/jmpress.min.js"></script>
    <!-- jmslideshow plugin : extends the jmpress plugin -->
    <script type="text/javascript" src="Entrada/SlideshowJmpress/js/jquery.jmslideshow.js"></script>
    <script type="text/javascript" src="Entrada/SlideshowJmpress/js/modernizr.custom.48780.js"></script>
    <noscript>
            <style>
            .step {
                    width: 100%;
                    position: relative;
            }
            .step:not(.active) {
                    opacity: 1;
                    filter: alpha(opacity=99);
                    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(opacity=99)";
            }
            .step:not(.active) a.jms-link{
                    opacity: 1;
                    margin-top: 40px;
            }
            </style>
    </noscript>

                
<script type="text/javascript">

$(document).ready(function(){
    
	$(".signin").click(function(e) {    
			e.preventDefault();
			$("fieldset#signin_menu").toggle();
			$(".signin").toggleClass("menu-open");
			$("#tLogin").focus();
		});
		
		$("fieldset#signin_menu").mouseup(function(e) {
			return false;
		});
                
                
		$(document).mouseup(function(e) {
			if($(e.target).parent("a.signin").length==0) {
				$(".signin").removeClass("menu-open");
				$("fieldset#signin_menu").hide();
			}
		});	
		
	
	$(function() {
	  $('#forgot_username_link').tipsy({gravity: 'w'}); 
          $('.EmailVc').tipsy({gravity: 'w'}); 
        });
 
	
    inicio();
    
    
    //Cargando la galería central
    
    urldir="Entrada/SlideshowJmpress/index.html";

    $("#wrapper").html("<img src='../img/loader-mini.gif'/><br/>");

    $("#wrapper").load(urldir);
    
    //Fin de cargando la galería central
    
});



function inicio(){
    
    $().ajaxStart(function() {
        //$('#Respuesta').hide();
    }).ajaxStop(function() {
        $('#Respuesta').fadeIn('slow');
    });
	
	
    $("#signin").submit(function(){

        var pag = $(this).attr('action');
               
        var Pass = calcMD5(document.getElementById('txtPass').value); 
        
        var datos = "txtLogin=" + document.getElementById('tLogin').value + "&txtPass=" + Pass;
        
		//alert(datos);
		
        $.ajax({
            type: 'POST',
            url: pag,
            data: datos,
            success: function(data){
                    switch(data){
                        case 'Exitoso':
                            document.location.href='Principal/';
                            break;
                        case 'VerificarSe':
                            VerificarEncrip("verificarSe.php");
                            break;
                        default:
                            $('#Respuesta').html(data);
                            $("#txtPass").select();
                    }

		
            },
            beforeSend: function(){
                $('#Respuesta').html("<img src='img/loader-mini.gif'/><br/>");
            },
            error: function(){
                $('#Respuesta').html("Hubo problemas en la red");
            }
        })
        return false;	
    })
}

function VerificarEncrip(pag){
	
    var Pass = document.getElementById('txtPass').value;
	
	var datos = "txtLogin=" + document.getElementById('tLogin').value + "&txtPass=" + Pass + "";

    $.ajax({
        type: 'POST',
        url: pag,
        data: datos,
        success: function(data){

            switch(data){
                case 'PassObli':
                    document.location.href='Cambiar_Pass_Obl.php';
                    break;
                default:
                    $('#Respuesta').html(data);
                    $("#txtPass").select();
            }
        },
        beforeSend: function(){
            $('#Respuesta').html("<img src='img/loader-mini.gif'/><br/>");
        },
        error: function(){
            $('#Respuesta').html("Hubo problemillas en la red");
        }
    })
}


</script>

</head>

<body>


<div id="container">
  <div id="topnav" class="topnav"><a href="http://email.lalvirtual.com" class="EmailVc" title="Administra el correo de tu colegio a través de gmail.">Email MyVc.</a>   ¿Tienes cuenta? <a href="login" class="signin"><span>Ingresar</span></a> </div>

  <fieldset id="signin_menu">
  
    <form method="post" id="signin" action="verificarEn.php">
      <label for="tLogin">Usuario</label>
      <input id="tLogin" name="txtLogin" value="" title="Nombre de usuario" tabindex="4" type="text">
      
      <p>
        <label for="txtPass">Contraseña</label>
        <input id="txtPass" name="txtPassSin" value="" title="Contraseña" tabindex="5" type="password">
      </p>
      <p class="remember">
        <input id="signin_submit" value="Entrar" tabindex="6" type="submit" />
        
        <div id="Respuesta" style="display: inline; "></div>
        
      </p>
      
      <p class="forgot-username"> <a id="forgot_username_link" title="Se enviará a tu correo tus datos de ingreso" href="Cambiar_Pass_Olv.php">¿Olvidaste tus datos?</a> </p> <!-- onclick="document.location='Cambiar_Pass_Olv.php?UsuTemp='+$('#tLogin').val();" -->

    </form>
    
  </fieldset>
  
</div>
    
    <header>
            <h1>Bienvenido a tu <span>Colegio Virtual</span></h1>
            <h2>My Virtual College</h2>
            <!--
            <nav class="codrops-demos">
                    <a href="http://licadli.interamerica.org">Liceo</a>
                    <a href="www.lalvirtual.com">lalvirtual.com</a>
            </nav>
            -->
            
    </header>
    <div id="wrapper">
        
        
        
    </div>
    
    <header>
    <h2>Escribeme a josethmaster@lalvirtual.com</h2>
    <h2>Copyrigth @2012</h2>
    <br /><br/>
    </header>
</body>
</html>
