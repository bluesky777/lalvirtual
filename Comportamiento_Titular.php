<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();

$sqlA="SELECT a.idAlum, a.NombresAlum, a.ApellidosAlum, ga.idGrupo, g.NombreGrupo 
		from tbgrupos g, tbgrupoalumnos ga, tbalumnos a
		where g.idGrupo=ga.idGrupo and g.idGrupo=".$_GET['idGrupo']." and a.idAlum=ga.idAlumno 
		and ga.idPeriodo='".$_SESSION['PeriodoUsu']."' order by ApellidosAlum, NombresAlum";

$qSqlA=mysql_query($sqlA, $con) or die ("No se trajeron los alumnos".mysql_error());

$Grupo="";
$idGrupo="";



while($rSqlA=mysqli_fetch_array($qSqlA)){
	
	$sqlComport="SELECT idComport from tbcomportamiento 
		where MateriaGrupoComport=".$rSqlA['idGrupo']." and PeriodoComport='".$_SESSION['PeriodoUsu']."' 
		and AlumnoComport=".$rSqlA['idAlum'];

	
	$qSqlComport=mysql_query($sqlComport, $con) or die("No se pudo comprobar si el alumno ".$rSqlA['idAlum']." tiene comportamiento de este periodo. ".mysql_error());
	
	$nSqlComport=mysqli_num_rows($qSqlComport);
	
	if($nSqlComport==0){
		
		$sqlIns="INSERT into tbcomportamiento (MateriaGrupoComport, AlumnoComport, 
				NotaComport, PeriodoComport)
				values (".$rSqlA['idGrupo'].", ".$rSqlA['idAlum'].", '100', 
				".$_SESSION['PeriodoUsu'].")";

		$qSqlIns=mysql_query($sqlIns, $con) or die("No se pudo ingresar el comportamiento al alumno ".$rSqlA['idAlum'].". ".mysql_error());
		
	}
	$Grupo=$rSqlA['NombreGrupo'];
	$idGrupo=$rSqlA['idGrupo'];
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<script language="javascript" src="js/jquery.js"></script>
<script language="javascript" src="js/jquery-ui-1.8.18.js"></script>

<script language="javascript">
	$(document).ready(function() {
		
		$("#frmComport").submit(function(){
			$.ajax({
				type: "POST",
				url: "Guardar_Notas_a_Comportamiento.php",
				data: $(this).serialize(),
				success: function(resp){
					alert(resp);
					$('#Resultado').html("");
					if(resp=="Notas guardadas."){
						//alert("Notas guardadas.");
						window.location.refresh();
					}
					return false;
				},
				beforeSend: function(){
					$('#Resultado').html("<img src='img/loader-mini.gif'/><br/>");
				},
				error: function(data){
					$('#Resultado').html("Hubo problemillas " + data);
				}
			})
			return false;
		});
		
		$(".RemoverFrase").click(function(){
			
			Datos=$(this).attr("href");

			if(confirm("¿Seguro que desea quitar esta frase?")){
				$.ajax({
					url: "Eliminar_Frase_a_Comportamiento.php",
					data: Datos,
					type: "POST",
					success: function(resp){
						//alert(resp);
						if(resp=="Removido satisfactoriamente"){
							$(this).parent().parent().attr('display','hidden');
							$('#Resultado').html(resp);
							window.document.refresh();
						}
						return false;
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
		
	});
		
</script>

<title>Comportamiento</title>
</head>

<body>
<h2>Comportamiento del grupo <?php echo $Grupo; ?></h2>
<form name="frmComport" id="frmComport" action="#" method="post">
  <table border="1">
  <tr>
	<td>No</td>
    <td>Nombres<input type="hidden" name="txtGrupo" value="<?php echo $idGrupo; ?>"></td>
    <td>Nota</td>
  </tr>

	<?php
	$i=1;

	$sqlDis="SELECT * from tbcomportamiento, tbalumnos, tbgrupoalumnos ga 
			where MateriaGrupoComport=".$idGrupo." and PeriodoComport=".$_SESSION['PeriodoUsu']." 
			and AlumnoComport=idAlum and ga.idAlumno=idAlum and ga.idPeriodo=".$_SESSION['PeriodoUsu'];


	$idComport=0;

	$qSqlDis=mysql_query($sqlDis, $con) or die ("No se trajeron los alumnos. ".mysql_error());

	while($rSqlDis=mysqli_fetch_array($qSqlDis)){

		$idComport=$rSqlDis['idComport'];
		{
		  ?>	
		  <tr>
		  	<td valign="top"><?php echo $i++; ?></td>
		    <td><?php echo $rSqlDis['ApellidosAlum'] ." ". $rSqlDis['NombresAlum']; ?>
		   	  <ol>
		      	<?php
				$sqlFra="SELECT c.idComport, c.NotaComport, f.idFrase, f.Frase  FROM tbcomportamiento c, tbfrases f, tbfrasescomportamiento fc
						WHERE c.MateriaGrupoComport=".$idGrupo." 
						and fc.idFrase=f.idFrase and fc.idComportamiento=c.idComport 
						and c.AlumnoComport=".$rSqlDis['idAlum'] ." and f.YearFrase=".$_SESSION['Year']."  
						and PeriodoComport='".$_SESSION['PeriodoUsu']."'";
				
				$qSqlFra=mysql_query($sqlFra,$con)or die("No se trajeron las frases.".mysql_error());
				
				while($rSqlFra=mysqli_fetch_array($qSqlFra)){
				?>
				<li>
				<?php echo $rSqlFra['Frase']; ?> 
		        <a href="idFrase=<?php echo $rSqlFra['idFrase']; ?>&idComport=<?php echo $rSqlFra['idComport']; ?>&NomAlum=<?php echo $rSqlDis['NombresAlum']; ?>" class="RemoverFrase"><img src="img/icono_eliminar.gif" width="21" height="21" style="cursor:pointer" /></a>
		        </li>
		        <?php
				}
				?>
		      </ol>
		      <a href="Frases.php?idComport=<?php echo $idComport."&idAlum=".$rSqlDis['idAlum']; ?>" style="display:block; text-decoration:none;" ><img src="img/agregar.png" width="24" height="24">Agregar frase</a>
		    </td>
		    <td valign="top">
		    	<input name="idComport<?php echo $idComport; ?>" type="text" value="<?php echo $rSqlDis['NotaComport']; ?>" size="3" maxlength="3">
		    </td>
		  </tr>
		<?php
		}
	}
	?>

	</table>
	<div id="Resultado">

	</div>
	<p>
	  <input type="submit" name="button" id="btGuardar" value="Guardar notas">
	</p>
	<p><a href="Frases.php" id="IrFrases">Editar todas las frases.</a></p>
</form>

</body>
</html>