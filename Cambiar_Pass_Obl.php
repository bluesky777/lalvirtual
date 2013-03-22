<?php

require_once("verificar_sesion.php");
require_once("conexion.php");


$con=Conectar();



if (($_SESSION['Cifrado'] == 0 or $_SESSION['Cifrado'] == null) and $_SESSION['Logueado']==1){ //Usuario logueado y obligado a cambiar contraseña.
?>


<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Cambiar contraseña</title>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/EncripMD5.js"></script>

<script language="javascript">

$(document).ready(function () {
	
	var emailreg = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
	
	$(".boton").click(function (){
		
		$(".error").remove();
				
		if( $(".pass1").val() == "" ){
			$(".pass1").focus().after("<span class='error'>Ingrese contraseña</span>");
			return false;

		}else if( $(".pass2").val() == "" || $(".pass2").val() != $(".pass1").val()){
			$(".pass2").focus().after("<span class='error'>Deben coincidir</span>");
			return false;
		/*	
		}else if( $(".email").val() == "" || !emailreg.test($(".email").val()) ){
			$(".email").focus().after("<span class='error'>Ingrese un email correcto</span>");
			return false;
						
		}else if( $(".mensaje").val() == "" ){
			$(".mensaje").focus().after("<span class='error'>Ingrese un mensaje</span>");
			return false; */
		}
		
		Guardar();
		return false;
	});
	
	$(".pass1, .pass2, .mensaje").keyup(function(){
		if( $(this).val() != "" ){
			$(".error").fadeOut();			
			return false;
		}		
	});
	
	$(".email").keyup(function(){
		if( $(this).val() != "" && emailreg.test($(this).val())){
			$(".error").fadeOut();			
			return false;
		}		
	});
});


function Guardar(){

		   
	var Pass = calcMD5($('#txtPass1').val());

	var datos = "txtPass=" + Pass;

	$.ajax({
		type: 'POST',
		url: "Cambiar_Pass_Obl_Guardar.php",
		data: datos,
		success: function(data){
				switch(data){
					case 'Exitoso':
						Verificar(Pass);
						break;
					default:
						$('#Respuesta').html(data);
						
				}

		},
		beforeSend: function(){
			$('#Respuesta').html("<img src='img/loader-mini.gif'/><br/>");
		},
		error: function(){
			$('#Respuesta').html("Hubo problemillas en la red");
		}
	})
	
    return false;	

}

function Verificar(pass){
	
	var datos = "txtLogin=<? echo $_SESSION['Usuario']; ?>&txtPass=" + pass;
	
	$.ajax({
		type: 'POST',
		url: "verificarEn.php",
		data: datos,
		success: function(data){
			switch(data){
				case 'Exitoso':
					document.location.href='Principal/';
					break;
				default:
					$('#Respuesta').html(data);
					
			}

		},
		beforeSend: function(){
			$('#Respuesta').html("<img src='img/loader-mini.gif'/><br/>");
		},
		error: function(){
			$('#Respuesta').html("Hubo problemillas en la red");
		}
	})
	
    return false;	
}


</script>

<link href="css/formulario.css" rel="stylesheet">

<style type="text/css">


#FotoUsu{
	width:100px;
	height:100px;
}

</style>
</head>

<body>
    
<img src="img/MyVc.png" />

<h3>Antes de continuar...</h3>
<p>
La contraseña actual aun no ha sido encriptada, por su seguridad debe crear otra contraseña para aumentar la seguridad en la red.
</p>

<p>
En caso de olvido, tendrá que solicitar contraseña nueva que se generará de forma aleatoria y será enviada a su correo electrónico.
Para mayor información sobre contraseñas cifradas <a href="http://www.maestrosdelweb.com/editorial/md5/" target="_blank">click aquí</a></p>


<form name="frmObligado" action="#" class="contacto">

 <div>

	
    <div class="NombreUsu">
    <b><label>
    <?
    echo $_SESSION['Usuario'];
    ?>
    </label></b>
    
    </div>
      </div>
                
	<div>
		<label>Contraseña nueva*</label>
		<input type="password" name="txtPass1" id="txtPass1" class="pass1" />
        </div>
    <div>
		<label>Confirmar contraseña</label>
		<input type="password" name="txtPass2" id="txtPass2" class="pass2" />

  </div>

	<label>*Te recomendamos usar minúsculas, mayúscula y números.</label>

<div id="Respuesta">
</div>
          <input type="submit" value="Guardar y continuar" class="boton"  />


</form>




</body>
</html>
<?php
	
} elseif($_SESSION['Cifrado'] == 1 and $_SESSION['Logueado']==1) {  //Usuario desea cambiar contraseña voluntariamente
	
	
}



?>


