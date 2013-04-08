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

	$qsqlGr=mysql_query($sqlGr, $con) or die("Pailitas, no hay materias. " . mysql_error() . "<br />" . $sqlGr);
	while($rsqlGr=mysql_fetch_array($qsqlGr)){
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
		<a id="btAllAnio" href="../Informes/Puestos_Alumnos/All_Anio_Puestos.php" title="Puestos por grupos en el año">Puestos por grupos en el año</a>
	</p>
	<p>
		<a id="btAllPeriod" href="../Informes/Puestos_Alumnos/All_Periodo_Puestos.php" title="Puestos por grupos en el periodo">Puestos por grupos en el periodo</a>
	</p>
	<p>
		<a id="btTrPeriod" class='lightajax' href="../Informes/Tres_Primeros.php" title="Tres primeros puestos del periodo">Tres primeros del periodo actual</a>
	</p>
	<p>
		<a id="btTrAnio" class='lightajax' href="../Informes/Tres_Primeros_Anio.php" title="Tres primeros puestos del año">Tres primeros del año</a>
	</p>
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