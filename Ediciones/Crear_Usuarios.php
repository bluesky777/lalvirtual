<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script language="javascript" src="jquery.js"></script>

<script language="javascript">
	$(document).ready(function() {
	
		$("#TipoUsuarios").submit(function(){

			if(confirm("¿Seguro que desea general usuario a personas de este año que no lo tengan?")){
				
				$.ajax({
					type: 'POST',
					url: 'Generar_Usuarios.php',
					data: $(this).serialize(),
					success: function(data){
									
						$("#ProcesoUsu").html(data);
						
					},
					beforeSend: function(){
						$('#ProcesoUsu').html("<img src='../img/loader-mini.gif'/><br/>");
					},
					error: function(data){
						$('#ProcesoUsu').html("Hubo problemillas " + data);
					}
				});
			}
			return false;
		});
	});
</script>

<title>Generar usuarios</title>
</head>

<body>

<form name="TipoUsuario" id="TipoUsuarios" method="post" action="Generar_Usuarios.php">
    Elije a quien deseas crearle usuario  :
    <select name="txtTipoUsu" id="TipoUsu">
      <option value="1" selected>Profesores</option>
      <option value="2">Estudiantes</option>
      <option value="3">Acudientes</option>
    </select>
    <input type="submit" name="btsumit" value="Generar" >

</form>

<div id="ProcesoUsu"></div>
</body>
</html>