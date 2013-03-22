<?php
require_once("verificar_sesion.php");
require_once("conexion.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/jquery.js"></script>
<script language="javascript">
	$(document).ready(function() {
			
			$("#formComp").hide();
			$("#formComp").show('slow');
			
	
	$("#Formulario").submit(function(){
		
		/*busca caracteres que no sean espacio en blanco en una cadena  */
		function vacio(q) {  
				for ( i = 0; i < q.length; i++ ) {  
						if ( q.charAt(i) != " " ) {  
								return true  
						}  
				}  
				return false  
		}  
		  
		/*valida que el campo no este vacio y no tenga solo espacios en blanco  */

		if( vacio($("#No").val()) == false ) {  
			alert("Introduzca el No para el orden.");
			$("#No").focus(); 
			return false  
		} else if ( vacio($("#Comp").val()) == false ){  
			alert("Introduzca la competencia.");
			$("#Comp").focus(); 
			return false
		} else if ( vacio($("#Porc").val()) == false ){  
			alert("Introduzca el porcentaje.");
			$("#Porc").focus(); 
			return false
		} else {
			$.ajax({
				type: 'POST',
				url: 'Guardar_Competencia_Editada.php',
				data: $(this).serialize(),
				success: function(data){
					//alert(data);
					$("#formComp").hide('fast',function(){
						opener.document.location.reload(true);
						window.close()
					});
				},
				beforeSend: function(){
					$('#Resultado').html("<img src='img/loader-mini.gif'/><br/>");
				},
				error: function(data){
					$('#Resultado').html("Hubo problemillas " + data);
				}
			});
			return false  
		}  
		return false;	
	});
			
	});
	
	function cerrar(){
		window.close();
	}
	
</script>
<link href="css/formato.css" media="all" type="text/css" rel="stylesheet">
</head>

<body>
<center>
<div  id="formComp">
<form name="form1" id="Formulario" method="post" action="">
<fieldset>
  <p>
  <h2><B>EDITAR COMPETENCIA.</B></h2></p>
  <table width="80%" border="1">
    <tr>
      <td width="22%"><label>No</label></td>
      <td width="78%"><input class="text-input" name="txtNo"  type="text" value="<?php echo $_GET['No']; ?>" id="No" size="3" maxlength="2" onKeyPress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;"></td>
    </tr>
    <tr>
      <td><label>Competencia </label></td>
      <td><input  name="txtComp" class="text-input" value="<?php echo $_GET['Comp']; ?>" type="text" id="Comp" size="50"></td>
    </tr>
    <tr>
      <td><input type="hidden" name="idComp" value="<?php echo $_GET['idComp']; ?>"><label>Porcentaje</label></td>
      <td><input class="text-input" name="txtPorc" type="text" value="<?php echo $_GET['Porc']; ?>" id="Porc" size="3" maxlength="3" onKeyPress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;">
        % </td>
    </tr>
  </table>
  <input type="Submit" name="btSumit" value="Guardar">
  <input type="button" name="btCancelar" value="Cancelar" onClick="cerrar();">

</fieldset>
</form>
</div>
<div id="Resultado"></div>
</center>
</body>
</html>