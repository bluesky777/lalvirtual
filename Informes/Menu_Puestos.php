<?php 
require_once("../verificar_sesion.php");
require_once("../conexion.php");

$con=Conectar();
$TipoUsu = $_SESSION['TipoUsu'];
?>

<form name="frmPorGrado" id="frmPorGrado" action="../Informes/Puestos_Alumnos.php" method="post">

<input type="hidden" value="<?php echo $_GET['TipoUsu']; ?>" id="hdPuesTipoU" />
<?php
if(isAdPr()){
?>
<span class="txtInfo">Puesto de los alumnos por grado</span>
	<select name="idGrupo">
	  <?php
		
	$sqlGr="select * from tbgrupos g where g.YearGrupo='". $_SESSION['Year']."'	order by g.OrdenGrupo";

	$qsqlGr=$con->query($sqlGr) or die("Pailitas, no hay materias. " . mysqli_error($con) . "<br />" . $sqlGr);
	while($rsqlGr=mysqli_fetch_array($qsqlGr)){
		?>
	  <option value="<?php echo $rsqlGr['idGrupo']; ?>"><?php echo $rsqlGr['NombreGrupo']; ?></option>
	  <?php
		}
		?>
  </select>
<?php
}
?>
<div id="OptPuntajes">

	<p>
	<?php 
	$Txt1 = $Txt2 = "";
	if(isAdPr()) {
		$Txt1 = "Puntaje del grupo por periodo"; 
		$Txt2 = "Puntaje del grupo en el año";
	}else{
		$Txt1 = "Mi puntaje en este periodo"; 
		$Txt2 = "Mi puntaje en este año";
	} 
	?>
		<a id="btPuntPeriod" class='lightajax' href="../Informes/Puestos_Alumnos.php" title="<?php echo $Txt1; ?>"><?php echo $Txt1; ?></a>
	</p>
	<p>
		<a id="btPuntAnio" class='lightajax' href="../Informes/Puestos_Alumnos_Anio.php" title="<?php echo $Txt2; ?>"><?php echo $Txt2; ?></a>
	</p>
	<?php
	if(isAdPr()){
	?>
	<p>
		<a id="btPuntAnioFir" class='lightajax' href="../Informes/Puestos_Alumnos/Puestos_Alumnos_Anio_Fir.php" title="Informe para imprimir y recortar por alumnos">Puntaje del grupo en el año para recortar por alumnos</a>
	</p>
	<?php
	}
	?>
</div>
</form>

<hr>


</body>
</html>
