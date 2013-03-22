<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<title>Notas de alumnos</title>

<script type="text/javascript" src="js/jquery.js" ></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#tableind').dataTable();

		$("#Agregar").click(function() {
			window.open("Agregar_Indicador.php?idComp=<?php  echo $_GET['idComp'];?>", "Agregador", "width=670,height=440, top=100,left=100");
			return false;
        });
		
		$(".Editar").click(function(){
			window.open($(this).attr("href"), "Editor", "width=700,height=450, top=100,left=100");
			return false;
		});
		
    });
	
	function eliminar(idInd){
		if (confirm("¿Está seguro de que desea eliminar este indicador?")){
			var idInd = "idInd="+idInd;
			$.ajax({
				url: "Eliminar_Indicador.php",
				data: idInd,
				type: "POST",
				success: function(resp){
					location.reload(true);
					alert(resp);
				}
			})
		}
	}

</script>
</head>

<body>

<?php

$idCompe=$_GET['idComp'] ;

$sql="select Competencia from tbcompetencias where idCompet='" .$idCompe . "'";
$qSql=mysql_query($sql, $con) or die ("No se pudo traee el nombre de la competencia;" . mysql_error());
$rSql=mysql_fetch_array($qSql);
?>
Competencia: <i><b><?php echo $rSql['Competencia']; ?></b></i>
<br>

<table border="1">
	<tr>
    	<td>No</td>
        <td>Alumno</td>
        <td></td>
    </tr>
</table>

</body>
</html>