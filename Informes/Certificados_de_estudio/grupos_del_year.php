<?php

require_once("../../php/clsConexion.php");

$Con=new clsConexion();
$Con->Conectar();

?>
<label for="selGrupo">Escoja un grupo: </label>
<select class="form-control" id="selGrupo">
	<?php
	$sqlG = "select * from tbgrupos where YearGrupo=".$_GET['year']. " order by OrdenGrupo";
	//echo $sqlG;
	$qSqlG = $Con->queryx($sqlG, "No se pudo traer los grupos. ");

	while($rSqlG = mysql_fetch_array($qSqlG)){
		
	?>
	<option value="<?php echo $rSqlG['idGrupo']; ?>"><?php echo $rSqlG['NombreGrupo']; ?></option>
	<?php
	}
	?>
</select>

