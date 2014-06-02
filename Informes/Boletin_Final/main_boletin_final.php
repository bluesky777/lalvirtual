<?php
require_once("../../verificar_sesion.php");
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
	<link rel="stylesheet" type="text/css" href="css/bol_final.css">
	<link rel="stylesheet" type="text/css" href="css/bol_final_print.css" media="print">

<script type="text/javascript">

$(document).on('ready', function(){
	$('#cargaBols').on('click', function(){


		$.ajax({
			type: 'GET',
			url: 'bol_final.php',
			data: "idGr=" + $('#selGrupo').val(),
			dataType: 'json',
			success: function(data){
				console.log(data);
				$('.cargaBols').html("Cargar boletines");
				$('.paginas').html('');

				for (var alum in data) {
					


					var datosEnv = "idAlum=" + data[alum].idAlum;

					if ($('#chkFirmas:checked').length == 1){
						datosEnv += "&Firm=1";
					}


					// Traemos el boletin de cada alumno:
					$.ajax({
						type: 'GET',
						url: 'bol_final.php',
						data: datosEnv,
						//dataType: 'json',
						success: function(boletin){
							$('.paginas').append(boletin);
						},
						error: function(data){
							$('.paginas').html("Hubo problemas con el Boletin " + boletin);
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
<h2>Boletines finales <?php echo $_SESSION['Year']; ?></h2>

<div class="form-inline">
  <div class="form-group">
	<label for="selGrupo">Escoja un grupo: </label>
	<select class="form-control" id="selGrupo">
		<?php
		$sqlG = "select * from tbgrupos where YearGrupo=".$_SESSION['Year']. " order by OrdenGrupo";
		//echo $sqlG;
		$qSqlG = $Con->queryx($sqlG, "No se pudo traer los grupos. ");

		while($rSqlG = mysql_fetch_array($qSqlG)){
			
		?>
		<option value="<?php echo $rSqlG['idGrupo']; ?>"><?php echo $rSqlG['NombreGrupo']; ?></option>
		<?php
		}
		?>
	</select></div>
	<div class="checkbox">
	    <label>
	      <input type="checkbox" id="chkFirmas" checked> Con firmas
	    </label>
	</div>
	<a href="javascript:void(0)" id="cargaBols" class="btn btn-primary">Cargar boletines</a>
</div>
	<hr >
  
</div>

<div class="paginas">
	<h1>Aquí se verán los boletines</h1>
</div>

</body>
</html>