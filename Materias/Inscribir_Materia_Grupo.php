<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript">

	$(document).ready(function() {
		alert("Hola");
		
		$("#btNuevo").click(function() {
            $("#NuevaMateria").show("fast");
			$(this).hide();
        });
		
		function vacio(q) {  
				for ( i = 0; i < q.length; i++ ) {  
						if ( q.charAt(i) != " " ) {  
								return true  
						}  
				}  
				return false  
		}  
		
		
		
		$("#NuevaMat").submit(function() {
			
			if( vacio($("#txtOrden").val()) == false ) {  
				alert("Introduzca el No para el orden.");
				$("#txtOrden").focus(); 
				return false  
			} else if ( vacio($("#txtCreditos").val()) == false ){ 
				alert("Introduzca las horas semanales.");
				$("#txtCreditos").focus(); 
				return false
			}
			
            $.ajax({
				type: 'POST',
				url: '../Materias/Guardar_Materia_Inscripcion.php',
				data: $(this).serialize(),
				success: function(data){
					$("#Resultado").html(data);
					document.location.reload();
				},
				beforeSend: function(){
					$('#Resultado').html("<img src='../img/loader-mini.gif'/><br/>");
				},
				error: function(data){
					$('#Resultado').html("Hubo problemillas " + data);
				}
			});
            return false;
        });
		
		$("#Cancelar").click(function(){
			window.close();
		});
		
    });
	

</script>
<title>Tabla de asignaturas asignadas</title>
</head>

<body>
<center>

</center>
</body>
</html>
