<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/basico.css">
<link type="text/css" href="css/grupos.css">
<script type="text/javascript" src="js/jquery.js"></script>

<script type="text/javascript">

	$(document).ready(function() {
		$("#Alumno").click(function() {
			$.ajax({
				type: 'POST',
				url: 'Editar_Alumno.php',
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
		
		$(".frmPaz").submit(function(){
			$.ajax({
				type: 'POST',
				url: 'Editar_Alumnos_Guardar2.php',
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

<title>Grupos</title>
</head>

<body>
<?php

$sql="SELECT * from tbalumnos a, tbgrupoalumnos ga 
	WHERE a.idAlum=ga.idAlumno and ga.idGrupo='".$_GET['idGrupo']. "' 
		and ga.idPeriodo='".$_SESSION['PeriodoUsu']."' 
	ORDER BY ApellidosAlum;";

// echo $sql;
?>
<center>
<i>Grupo: </i><b><?php echo $_GET['Grupo']." - ".$_GET['NomGrupo']; ?></b><br>
<i>Titular: </i><b>
<?php 
$sqlProf="select NombresProf, ApellidosProf from tbprofesores, tbgrupos where idGrupo='".$_GET['idGrupo']."' and TitularGrupo=idProf";

$qSqlProf=mysql_query($sqlProf, $con) or die("Lo sentimos, no se trajo al titular.".mysql_error());
$rSqlProf=mysql_fetch_array($qSqlProf);
echo $rSqlProf['NombresProf']." ".$rSqlProf['ApellidosProf'];
 ?></b><br><br>

<!--
<form name="frmGrupos" ID="Formulario" action="" method="post" enctype="multipart/form-data">

-->
<table id="tbGrupos" border="1px">
<thead>
	<tr id="Encabezados"  align="center" bgcolor="#0066FF" >
    	
    	<td>Orden</td>
        <td>No Matr</td>
        <td>Apellidos y Nombres</td>
        <td>No documento</td>
        <td>Sexo</td>
        <td>Fecha Nacimiento</td>
        <td>Telefono o Celular</td>
        <td>Paz y salvo y deuda($)</td>
        </tr>
</thead>
<tbody>

<?php
$qSql=mysql_query($sql, $con) or die ("No se pudo traer los grupos. " . mysql_error());

$r=mysql_num_rows($qSql);
$i=1;
while($rSql=mysql_fetch_array($qSql)){
?>

	<tr>

    	<td bgcolor="#D8D7EE"><?php echo $i++;?></td>
    	<td bgcolor="#D8D7EE"><input type="hidden" value="<?php echo $rSql['idAlum']; ?>" name="txtIdAlum"><?php echo $rSql['NoMatriculaAlum']; ?></td>
        <td>
        	<a href="Editar_Alumnos.php?idAlum=<?php echo $rSql['idAlum']; ?>&NoMat=<?php echo $rSql['NoMatriculaAlum']; ?>&Nom=<?php echo $rSql['NombresAlum']; ?>&Ape=<?php echo $rSql['ApellidosAlum']; ?>&Pazy=<?php echo $rSql['PazySalvoAlum']."&Debe=".$rSql['DeudaAlum']; ?>">
		
			<?php echo $rSql['ApellidosAlum']; ?> <?php echo $rSql['NombresAlum']; ?>
        	</a>
        </td>
        <td><?php echo $rSql['DocAlum']; ?></td>
        <td><?php echo $rSql['SexoAlum']; ?></td>
        <td><?php echo $rSql['FechaNacAlum']; ?></td>
        <td><?php echo $rSql['TelefonoAlum']." - ".$rSql['CelularAlum']; ?></td>
        <td align="center">
        
        <form name="frmPaz" action="Editar_Alumnos_Guardar2.php" method="post" class="frmPaz">
            
            
        <input type="hidden" name="txtIdAlum" value="<?php echo $rSql['idAlum']; ?>">A paz: 
          <select name="PazySalvo" id="PazySalvo">
            <option value="1" <?php if ($rSql['PazySalvoAlum']==1){echo "selected";} ?>>Si</option>
            <option value="0" <?php if ($rSql['PazySalvoAlum']==0){echo "selected";} ?>>No</option>
          </select>
	$<input name="txtPension" type="text" value="<?php echo $rSql['DeudaAlum']; ?>" size="5" maxlength="6">          
        <input type="submit" value="Guardar">
        </form>
        </td>
        </tr>
<?php
}
?>
</tbody>
</table>
<br />
<input type="button" value="Atrás" id="Atras" >

<!--
</form>

-->
<div id="Resultado"></div>
</center>
</body>
</html>