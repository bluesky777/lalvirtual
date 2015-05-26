<?php
/*
session_name("LoginUsuario"); 
session_start(); //iniciamos la sesión 

//Compruebo que el usuario está logueado 
if (!isset($_SESSION)){
	header("location: ../../index.php"); //Nos vamos al menú si ya inicio sesión.
} else { 
    //sino, calculamos el tiempo transcurrido 
    $fechaGuardada = $_SESSION["UltimoAcceso"]; 
    $ahora = date("Y-n-j H:i:s"); 
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 
	
	if($tiempo_transcurrido >= 1200) { 
     //si pasaron 10 minutos (600 seg) o más 
      session_destroy(); // destruyo la sesión 
      header("Location: ../../index.php"); //envío al usuario a la pag. de autenticación 
	}else { 
	//sino, actualizo la fecha de la sesión 
	$_SESSION["UltimoAcceso"] = $ahora; 
	} 
} 
*/

require_once("../../php/clsConexion.php");

$Con=new clsConexion();
$Con->Conectar();


?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Boletines finales</title>
	<meta charset="utf-8" />
	<script type="text/javascript" src="../../js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../Boletin_Final/css/bol_final.css">
	<link rel="stylesheet" type="text/css" href="../Boletin_Final/css/bol_final_print.css" media="print">

<script type="text/javascript">

$(document).on('ready', function(){


	traerGrupos = function (){
		$.ajax({
			type: 'GET',
			url: 'grupos_del_year.php',
			data: "year=" + $('#selYear').val(),
			success: function(data){
				$('#selector-grupo').html(data);
			},
			beforeSend: function(){
				$('#selector-grupo').html("<img src='../../img/loader-mini.gif'/><br/>");
			},
			error: function(data){
				$('#selector-grupo').html("No se trajo los grupos. <br>" + data);
			}
		})
	}

	traerGrupos()

	$('#selYear').change(function(){
		traerGrupos()
	});



	$('#cargaCerts').on('click', function(){


		$.ajax({
			type: 'GET',
			url: 'certificado.php',
			data: "idGr=" + $('#selGrupo').val() + "&year=" + $('#selYear').val(),
			dataType: 'json',
			success: function(data){
				console.log(data);
				$('.cargaCerts').html("Cargar certificados");
				$('.paginas').html('');

				for (var alum in data) {
					


					var datosEnv = "idAlum=" + data[alum].idAlum + "&year=" + $('#selYear').val();


					// Traemos el boletin de cada alumno:
					$.ajax({
						type: 'GET',
						url: 'certificado.php',
						data: datosEnv,
						//dataType: 'json',
						success: function(boletin){
							$('.paginas').append(boletin);
						},
						error: function(data){
							$('.paginas').html("Hubo problemas con el certificado " + boletin);
						}
					});
				};
			},
			beforeSend: function(){
				$('.paginas').html("<img src='../../img/loader-mini.gif'/><br/>");
			},
			error: function(data){
				$('.paginas').html("No se llamó los ids de alumnos. " + data);
			}
		});
	});
})

</script>
</head>
<body>

<div class="noPrint container">
<h2>Certificados de estudio</h2>

<div class="form-inline">


	<div class="form-group">
		<label for="selYear">Escoja año: </label>
		<select class="form-control" id="selYear">
			<?php
			$sqlG = "select * from tbyearcolegio";
			//echo $sqlG;
			$qSqlG = $Con->queryx($sqlG, "No se pudo traer los años. ");

			while($rSqlG = mysql_fetch_array($qSqlG)){
				
			?>
			<option value="<?php echo $rSqlG['Year']; ?>"><?php echo $rSqlG['Year']; ?></option>
			<?php
			}
			?>
		</select>
	</div>

	<div id="selector-grupo" class="form-group">
		...
	</div>

	<a href="javascript:void(0)" id="cargaCerts" class="btn btn-primary">Cargar certificados</a>
</div>
	<hr >
  
</div>

<div class="paginas">
	<h1>Aquí se verán los certificados</h1>
</div>


<style type="text/css">
	.encabezado-membrete, .pie-membrete{
  		width: 21cm;
  		position: absolute;
  		left: 0px;
  		z-index: 1;
	}

	.contenido{
		position: relative;
		z-index: 3;
		top: -25px;
	}

</style>
</body>
</html>