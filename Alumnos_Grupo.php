<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con = Conectar();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/alumnos_grupo.css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>

<script type="text/javascript">

	$(document).on('ready', function() {

		$('.btn, .txtPension, label').popover({
			trigger: 'hover',
			placement: 'top'
		})


		$("#Alumno").click(function() {
			$.ajax({
				type: 'POST',
				url: 'Editar_Alumno.php',
				data: $(this).serialize(),
				success: function(data){
					$("#Resultado").html(data);
					$("#Resultado").alert()
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


		$('.chkAPaz').change(function(e){
			e.preventDefault();

			var datoId = $(this).parent().parent().parent().find('.txtIdAlum').val();
			var datoPaz = $(this).parent().parent().parent().find('.chkAPaz').is(':checked');
			datoPaz = datoPaz ? 1 : 0;
			var datoPension = $(this).parent().parent().parent().find('.txtPension').val();

			var datos = 'PazySalvo=' + datoPaz + '&txtPension=' + datoPension + '&txtIdAlum=' + datoId;
			console.log(datos);

			$.ajax({
				type: 'POST',
				url: 'Editar_Alumnos_Guardar2.php',
				data: datos,
				success: function(data){
					$("#Resultado").html(data);
					$("#Resultado").alert();
				},
				beforeSend: function(){
					$('#Resultado').html("<img src='img/loader-mini.gif'/><br/>");
				},
				error: function(data){
					$('#Resultado').html("Hubo problemillas " + data);
				}
			});

		});

		
		$("#Atras").click(function(e) {
            history.back();
        });
		
	});
	
</script>

<title>Grupos</title>
</head>

<body class="container">
<?php

$sql="SELECT * from tbalumnos a, tbgrupoalumnos ga 
	WHERE a.idAlum=ga.idAlumno and ga.idGrupo='".$_GET['idGrupo']. "' 
		and ga.idPeriodo='".$_SESSION['PeriodoUsu']."' 
	ORDER BY ApellidosAlum;";

// echo $sql;
?>


<div class="page-header">
	<h2><?php echo $_GET['NomGrupo'] . " - " . $_GET['Grupo']; ?>
		<small>Titular: 

			<?php 
			$sqlProf="select NombresProf, ApellidosProf from tbprofesores, tbgrupos where idGrupo='".$_GET['idGrupo']."' and TitularGrupo=idProf";

			$qSqlProf=mysql_query($sqlProf, $con) or die("Lo sentimos, no se trajo al titular.".mysql_error());
			$rSqlProf=mysql_fetch_array($qSqlProf);
			echo $rSqlProf['NombresProf']." ".$rSqlProf['ApellidosProf'];
			 ?>
	 	</small>
 	</h2>
 </div>



<!--
<form name="frmGrupos" ID="Formulario" action="" method="post" enctype="multipart/form-data">

-->
<table id="tbGrupos" class="table table-striped table-hover">
<thead>
	<tr>
    	
    	<th>No</th>
        <!--<th>No Matr</th> -->
        <th>Apellidos y Nombres</th>
        <th>Boletin</th>
        <th>Sexo</th>
        <!--<th>Fecha Nacimiento</th>-->
        <th>Telefono o Celular</th>
        <th>Paz y salvo y deuda($)</th>
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

    	<td><?php echo $i++;?></td>
    	<!--<td><input type="hidden" value="<?php echo $rSql['idAlum']; ?>" name="txtIdAlum"><?php echo $rSql['NoMatriculaAlum']; ?></td>
        -->
        <td>
        	<a href="Editar_Alumnos.php?idAlum=<?php echo $rSql['idAlum']; ?>&NoMat=<?php echo $rSql['NoMatriculaAlum']; ?>&Nom=<?php echo $rSql['NombresAlum']; ?>&Ape=<?php echo $rSql['ApellidosAlum']; ?>&Pazy=<?php echo $rSql['PazySalvoAlum']."&Debe=".$rSql['DeudaAlum']; ?>">
		
			<?php echo $rSql['ApellidosAlum']; ?> <?php echo $rSql['NombresAlum']; ?>
        	</a>
        </td>
        <td>
    	  <div class="btn-group">
        	<a href="Informes/Boletin_Alumno.php?txtIdAlum=<?php echo $rSql['idAlum']; ?>" 
        		class="btn btn-default btn-sm" data-content="Ver boletin del periodo actual">Boletin</a>
        	<a href="Informes/Boletin_Final/Boletin_Final_Pag.php?idAlum=<?php echo $rSql['idAlum']; ?>&Firm=1" 
        		class="btn btn-default btn-sm" data-content="Ver boletin final">Final</a>
        	<a href="Informes/Boletin_Final/Boletin_Final_Pag.php?idAlum=<?php echo $rSql['idAlum']; ?>&Firm=0" 
        		class="btn btn-default btn-sm" data-content="Ver boletin final sin firma">Sin firma</a>
          </div>
        </td>
        <td><?php echo $rSql['SexoAlum']; ?></td>
        <!--<td><?php //echo $rSql['FechaNacAlum']; ?></td>-->
        <td><?php echo $rSql['TelefonoAlum']." - ".$rSql['CelularAlum']; ?></td>
        <td align="center">
        
        <form name="frmPaz" action="Editar_Alumnos_Guardar2.php" method="post" role="form" class="frmPaz form-inline">
          <div class="form-group">
            
	        <input type="hidden" class="txtIdAlum" name="txtIdAlum" value="<?php echo $rSql['idAlum']; ?>">
	        <div class="checkbox">
		        <label data-content="Desactive para bloquear el acceso al estudiante">
		          <input name="PazySalvo" type="checkbox" class="chkAPaz"  <?php if ($rSql['PazySalvoAlum']==1){echo "checked";}?>>A paz
		        </label> 
				$<input class="txtPension form-control" data-content="Aunque haya deuda, solo se bloquea si Paz y Salvo no está chuliado." name="txtPension" type="text" value="<?php echo $rSql['DeudaAlum']; ?>" size="5" maxlength="6">          
		        <input type="submit" value="Guardar" class="btn btn-success btn-sm">
	        </div>

	      </div>
        </form>

        </td>
        </tr>
<?php
}
?>
</tbody>
</table>

<br />
<div id="Resultado"></div>
<input type="button" class="btn btn-primary btn-lg" value="Atrás" id="Atras" >


<hr>
<footer><p class="text-muted">Power by Joseth</p>
</footer>



</body>
</html>