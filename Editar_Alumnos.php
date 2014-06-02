<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();

?>
<html lang="es">
<head>
<script type="text/javascript" src="js/jquery.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">

	$(document).ready(function() {
		$("#FormularioAlum").submit(function() {
			$.ajax({
				type: 'POST',
				url: 'Editar_Alumnos_Guardar.php',
				data: $(this).serialize(),
				success: function(data){
					$("#Resultado").html(data);
				},
				beforeSend: function(){
					$('#Resultado').html("<img src='img/loader-mini.gif'/><br/>");
				},
				error: function(data){
					$('#Resultado').html("Hubo problemillas " + data);
				}
			});
            return false;
        });
		
		$("#Atras").click(function(e) {
            history.back();
        });
		
	});
	
</script>

<title>Editar:<?php echo $_GET['Nom'];?><?php echo $_GET['Nom']; ?></title>
<style type="text/css">
.Titulos {
	color: #FFF;
}
#Sex{
  width: 30px;
}

</style>
</head>

<body>
<center>
<form name="frmAlumnos" action="" method="post" id="FormularioAlum">
<table border="1">
  <tr>
    <td width="94" align="right" bgcolor="#9D0000" class="Titulos">Cod</td>
    <td width="10"><input type="text" name="txtId" value="<?php echo $_GET['idAlum'];?>" size="3" disabled="disabled" /><input type="hidden" value="<?php echo $_GET['idAlum'];?>" name="txtId" /></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#9D0000" class="Titulos">No de Matricula</td>
    <td><input type="text" value="<?php echo $_GET['NoMat'];?>" name="txtMat" size="5" id="No" /></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#9D0000" class="Titulos">Nombres</td>
    <td><input type="text" value="<?php echo $_GET['Nom'];?>" name="txtNombres" id="Nombres" /></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#9D0000" class="Titulos">Apellidos</td>
    <td><input type="text" value="<?php echo $_GET['Ape'];?>" name="txtApellidos" id="Apellidos" /></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#9D0000" class="Titulos">Sexo(M o F)</td>
    <td><input type="text" value="<?php if($_GET['Sex']=='') echo 'M'; else echo $_GET['Sex'];?>" name="txtSex" id="Sex" maxlength="1" /></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#9D0000" class="Titulos">Paz y salvo</td>
    <td>
    	<select name="PazySalvo" id="PazySalvo">
        	<option value="1" <?php if(isset($_GET['Pazy'])){ if ($_GET['Pazy']==1){echo "selected";} } ?>>Si</option>
          <option value="0" <?php if(isset($_GET['Pazy'])){ if ($_GET['Pazy']==0){echo "selected";} } ?>>No</option>
		</select></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#9D0000" class="Titulos">Pensión acumulada</td>
    <td>$
      <input name="txtPension" type="text" id="txtPension" value="<?php if(isset($_GET['Debe'])){ echo $_GET['Debe']; } ?>" size="6" maxlength="6" /></td>
  </tr>
</table>
<p>
  <input type="submit" value="Guardar">
  <input type="button" value="Atrás" id="Atras" >

</p>
</form>
<div id="Resultado">
</div>
</center>
</body>
</html>