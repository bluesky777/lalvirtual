<?php
//require_once("../conexion.php");
//require_once("../verificar_sesion.php");

//$con=Conectar()

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Editar privilegios</title>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#buu {
	list-style-position: inside;
	list-style-type: decimal;
}
</style></head>

<body>
<?

$sqlUsu

?>
<table border="1">

<tr>
	<td>
	    <?
		
		
		?>
    </td>
    <td>
	    Hola
    </td>
    <td>
	    Hola
	      <form id="form1" name="form1" method="post" action="">
	        <span id="sprytextfield1">
            <label for="buu"></label>
            <input type="text" name="buu" id="buu" />
            <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span><span class="textfieldMinValueMsg">El valor introducido es inferior al mínimo permitido.</span><span class="textfieldMinCharsMsg">No se cumple el mínimo de caracteres requerido.</span><span class="textfieldMaxCharsMsg">Se ha superado el número máximo de caracteres.</span><span class="textfieldMaxValueMsg">El valor introducido es superior al máximo permitido.</span></span>
    </form></td>
</tr>

</table>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer", {validateOn:["change"], minValue:0, minChars:1, maxChars:3, maxValue:100});
</script>
</body>
</html>