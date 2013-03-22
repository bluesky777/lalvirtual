<?php
require_once("verificar_sesion.php");
require_once("conexion.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/jquery-ui-1.8.18.js"></script>
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script language="javascript">

	$(document).ready(function() {
		$("#Resultado").html("<?php echo $_GET['idInd']; ?>");
		$("#formInd").hide();
		$("#formInd").show('slow');
		/*		$("#FecIni").dataPicker(); ////Este picker no me quiere funcionar*/
		$("#Formulario").submit(function(){
			/*busca caracteres que no sean espacio en blanco*/
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
			} else if ( vacio($("#Ind").val()) == false ){  
				alert("Introduzca el indicador.");
				$("#Ind").focus(); 
				return false
			} else if ( vacio($("#Porc").val()) == false ){  
				alert("Introduzca el porcentaje.");
				$("#Porc").focus(); 
				return false
			} else if ( vacio($("#Defec").val()) == false ){  
				alert("Introduzca el valor que se agregará automaticamente a este indicador.");
				$("#Defec").focus(); 
				return false
			/*} else if ( vacio($("#FecIni").val()) == false ){  
				alert("Introduzca la Fecha de inicio.");
				$("#FecIni").focus(); 
				return false
			} else if ( vacio($("#FecFin").val()) == false ){  
				alert("Introduzca la Fecha plazo.");
				$("#FecFin").focus(); 
				return false*/
			} else {
				$.ajax({
					type: 'POST',
					url: 'guardar_indicador_Editado.php',
					data: $(this).serialize(),
					success: function(data){
						$("#Resultado").html(data);
						$("#formInd").hide('fast', function(){
							//alert(data);
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

<div  id="formInd">
<form name="form1" id="Formulario" method="post" action="">
<fieldset>
  <p>
  <h1>EDITAR INDICADOR.</h1>  
  </p>
  <table width="80%" border="1">
    <tr>
      <td width="22%"><label>No</label></td>
      <td width="78%">
      	<input class="text-input" name="txtNo"  type="text" id="No" size="5" maxlength="3" value="<?php echo $_GET['Ord']; ?>">
      </td>
    </tr>
    <tr>
      <td><label>Indicador </label></td>
      <td><input  name="txtIndic" class="text-input" type="text" id="Ind" size="50" value="<?php echo $_GET['Indic']; ?>"></td>
    </tr>
    <tr>
      <td><label>Porcentaje</label></td>
      <td><input class="text-input" name="txtPorc" type="text" id="Porc" size="5" maxlength="3" value="<?php echo $_GET['Porc']; ?>">
        % </td>
    </tr>
    <tr>
      <td><label>Nota por defecto</label></td>
      <td><input class="text-input" name="txtNotaDef" type="text" id="Defec" size="5" maxlength="3" value="<?php echo $_GET['Def']; ?>">
        </td>
    </tr>
    <tr>
      <td><label>Fecha de Inicio</label></td>
	  <td><input class="text-input" name="txtFecIni" type="text" id="FecIni" size="10" value="<?php echo $_GET['FecI']; ?>"></td>
    </tr>
    <tr>
      <td><label>Fecha Entrega</label></td>
	  <td><input class="text-input" name="txtFecFin" type="text" id="FecFin" size="10" value="<?php echo $_GET['FecF']; ?>">
      <input type="hidden" name="idInd" value="<?php echo $_GET['idInd']; ?>">
      </td>
      
    </tr>
        
  </table>
  <input type="Submit" name="btSumit" value="Guardar">
  <input type="button" name="btCancelar" value="Cancelar" onClick="cerrar();">

<br />
</fieldset>
</form>
</div>

<div id="Resultado">
  <p>Editar indicador.</p>
</div>

</center>
</body>
</html>