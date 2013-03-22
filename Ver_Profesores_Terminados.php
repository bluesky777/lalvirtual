<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();

?>
<html xmlns="http://www.w3.org/1999/xhtml">

<?php
if($_SESSION['TipoUsu'] == 1){  /// Vista del administrador
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="css/Ver_Prof_Terminados.css"  rel="stylesheet">
<script language="javascript" src="js/jquery.js"></script>
<script language="javascript">
	$(document).ready(function() {
		$(".Anuncio").mousemove(function(e) {
            $(this).css("height", "auto");
			$(this).css("background-color","#D0CCFF");
        });
		
		$(".Anuncio").mouseleave(function(e) {
            $(this).css("height", "40");
			$(this).css("background-color", "#FFF");
        });		

		$(".AnuncioNuevo").mousemove(function(e) {
            $(this).css("height", "auto");
			$(this).css("background-color","#D0CCFF");
        });
		
		$(".AnuncioNuevo").mouseleave(function(e) {
            $(this).css("height", "40");
			$(this).css("background-color", "#C8AAFF");
        });		
		
		
		$(".AnuncioNuevo").click(function(){
			var x=$(this);
			var Cod=x.attr("id") + "&Oper=0"
			//alert(Cod);
			$.ajax({
				type: 'POST',
				url: 'Ver_Profesores_Termi_Guardar.php',
				data: Cod,
				success: function(data){
					//alert(data);
					//alert(x.attr("id"));
					x.css("background-color","#FFF");
				},
				beforeSend: function(){
					$('#Resultado').html("<img src='img/loader-mini.gif'/><br/>");
				},
				error: function(data){
					$('#Resultado').html("Hubo problemillas " + data);
				}
			});
		});

	});

</script>

<title>Anuncios Profesores</title>
</head>

<body>
<B>Profesores que dicen que han terminado el proceso de notas este periodo.</B>
<BR><BR>
<?php
$sqlA="select * from tbanuncios where ReceptoresAnu='Administradores' order by FechaAnu desc";

$qSqlA=mysql_query($sqlP, $con)or die("No se pudo traer los profesores.".mysql_query().". ".$sqlP);

while($rSqlA=mysql_fetch_array($qSqlA)){
	
	if($rSqlA['VistoAdmin']==0){
		?>
		<div class="AnuncioNuevo" id="idBitFinP=<?php echo $rSqlA['idBitFinP']; ?>" style="background-color:#DDE1FB">
		<?php
		echo "<b>".$rSqlP['NombresProf']." ".$rSqlP['ApellidosProf'].":</b><br>";
		
		if ($rSqlP['MensajeBitFinP']==""){
			echo "<i>Sin mensaje.</i>";
		} else {
			echo "<b>Mensaje: </b>".$rSqlP['MensajeBitFinP']."<br><br>";
		}
		echo $rSqlP['FechaBitFinP']."<br>";
		?>
		</div>
        <br>
		<?php
	} else {
		?>
		
		<div class="Anuncio" id="<?php echo $rSqlP['idBitFinP']; ?>">
		<?php
		echo "<b>".$rSqlP['NombresProf'].":</b><br>";
		if ($rSqlP['MensajeBitFinP']==""){
			echo "<i>Sin mensaje.</i>";
		} else {
			echo "<b>Mensaje: </b>".$rSqlP['MensajeBitFinP']."<br><br>";
		}
		echo $rSqlP['FechaBitFinP']."<br>";
		?>
		</div>
        <br>
		<?php
		}
		?>
		</body>
		<?php
	} 
	?>


<?php
}else {
?>


<?php
}
?>

</html>