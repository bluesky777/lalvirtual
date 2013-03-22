<?php 
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script language="javascript" src="js/jquery.js"></script>
<script language="javascript" src="js/jquery.jeditable.js"></script>
<script language="javascript" src="js/jquery.dataTables.min.js"></script>
<script language="javascript" src="js/jquery.dataTables.js"></script>

<style type="text/css" title="currentStyle">
	@import "css/demo_page.css";
	@import "css/demo_table.css";
</style>

<script language="javascript">
	$(document).ready(function() {
		
		$('#TablaFrases').dataTable();

		$("#AgregarFrase").hide();
		
		$("#btAgregar").click(function() {
			$("#AgregarFrase").show("fast");
			$("#Titulo").text()="Crear frase";
			$("#txtOpe").text()="Nuevo";
			
        });
		
		$("#Cancelar").click(function(e) {
            $("#AgregarFrase").hide("fast");
        });

		$(".AgregarFraseAlum").click(function(e) {
          
			var Datos=$(this).attr("href");
			var NomAlum= $(this).attr("id");
			if (confirm("¿Desea agregar esta frase al alumno "+ NomAlum +"?")){
				
				$.ajax({
					url: "Guardar_Frase_a_Comportamiento.php",
					data: Datos,
					type: "POST",
					success: function(resp){
						alert(resp+". Puede seguir agregando frases a " + NomAlum);
						//history.back();
					},
					beforeSend: function(){
						$('#Resultado').html("<img src='img/loader-mini.gif'/><br/>");
					},
					error: function(data){
						$('#Resultado').html("Hubo problemillas " + data);
					}
				})
			}
			return false; 
        });

		
		$("#frmFrase").submit(function(){
			
			if( vacio($("#txtFrase").val()) == false ) {  
				alert("Introduzca la frase.");
				$("#txtFrase").focus(); 
				return false  

			} else {
				$.ajax({
					type: 'POST',
					url: 'Guardar_Frase.php',
					data: $(this).serialize(),
					success: function(data){
						$("#Resultado").html(data);
						$("#AgregarFrase").hide('fast', function(){
							document.location.reload(true);
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
	}); //Cierre del document.ready
		
		function Editar(idFrase, Frase, TipoFrase){
			alert("Editando");
			return false;
		}		
		
		function vacio(q) {  
				for ( i = 0; i < q.length; i++ ) {  
						if ( q.charAt(i) != " " ) {  
								return true  
						}
				}  
				return false  
		}  
	
	function eliminar(idFrase){
		if (confirm("¿Está seguro de que desea eliminar esta frase?")){
			var idFras= "idFrase=" + idFrase + "&idComport=" +<?php echo $_GET['idComport']?>;
			$.ajax({
				url: "Eliminar_Frase.php",
				data: idFras,
				type: "POST",
				success: function(resp){
					location.reload(true);
					//alert(resp);
				}
			});
		}
	}
</script>

<title>Frases</title>
</head>

<body>

Frases de observaciones
<table border="1" id="">
	<thead>
      <tr>
        <th>No</th>
        <th>Frase</th>
        <th>Tipo</th>
        <th>Opc</th>
      </tr>
	</thead>
    <tbody>
<?php
$sqlF="Select * from tbfrases where YearFrase='".$_SESSION['Year'] ."'";
$qSqlF=mysql_query($sqlF, $con)or die("No se trajeron las frases. ".mysql_error());
while($rSql=mysql_fetch_array($qSqlF)){
?>
  <tr>
	<td><?php echo $rSql["idFrase"]; ?></td>
    <td>
    <?php
	if (isset($_GET['idAlum'])){
		
		$sqlA="select NombresAlum from tbalumnos where idAlum=".$_GET['idAlum'];

		$qSqlA=mysql_query($sqlA, $con) or die("No se tiene al alumno ".$_GET['idAlum']. ". ".mysql_error());
		
		$rSqlA=mysql_fetch_array($qSqlA);
		
	?>
    <a href="NomAlum='<?php echo $rSqlA["NombresAlum"]; ?>'&idFrase=<?php echo $rSql["idFrase"]; ?>&idComport=<?php echo $_GET["idComport"]; ?>" id="<?php echo $rSqlA["NombresAlum"]?>" class="AgregarFraseAlum"><?php echo $rSql["Frase"]; ?></a>
    <?php
	}else{
		echo $rSql["Frase"];
    }
	?>
    </td>
    <td><?php echo $rSql["TipoFrase"]; ?></td>
    <td>
    	<img src="img/icono_eliminar.gif" width="16" height="21" style="cursor:pointer" onClick="eliminar(<?php echo $rSql['idFrase']; ?>);"/> 
    	<a href="javascript:void(0);" onClick="Editar(<?php echo $rSql["idFrase"]; ?>, <?php echo $rSql["Frase"]; ?>, <?php echo $rSql["TipoFrase"]; ?>);" ><img src="img/icono_editar.jpg" width="20" height="22" /></a>
    </td>
  </tr>
<?php
}
?>
	</tbody>
</table>
<p>&nbsp;</p>
<p><a href="javascript:void(0);" style="display:block; text-decoration:none;" id="btAgregar"><img src="img/agregar.png" width="24" height="24">Agregar Frase</a>
</p>
<br>
<br>
<div class="AgregarFraseAlum">
</div>
<center>
<div id="AgregarFrase">
<label id="Titulo">Crear frase</label>
<form name="frmFrase" id="frmFrase" action="" method="post">
<table border="1" id="TablaAgregar">
	<tr>
	  <td>Cod</td>
	  <td><input type="hidden" name="txtId" id="txtId">
	    <input type="hidden" name="txtOpe" id="txtOpe" value="Nuevo">
        <input type="text" name="cod" id="txtId2" size="3" disabled></td>
	  </tr>
	<tr>
    	<td>Frase</td>
        <td><input type="text" name="txtFrase" id="txtFrase" ></td>
    </tr>
    <tr>
    	<td>Tipo</td>
        <td>
        	<select name="txtTipo" id="txtTipo">
        		<option value="Fortaleza">Fortaleza</option>
                <option value="Oportunidad">Oportunidad</option>
                <option value="Debilidad">Debilidad</option>
        	</select>
        </td>
    </tr>

</table>
<br>
<input type="submit" id="btGuardar" value="Guardar">
<input type="button" id="Cancelar" value="Cancelar">
</form>

<div id="Resultado">
</div>

</div>

</center>
</body>
</html>