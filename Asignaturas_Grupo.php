<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();

$sqlG="select idGrupo, Grupo, NombreGrupo from tbgrupos where YearGrupo=".$_SESSION['Year'];
$qSqlG=mysql_query($sqlG);

?>
<div class="Titulo2">
Seleccione un grupo: 
</div>

<select name="Grupo" id="Grupos">
	<?php
	while($rSqlG=mysql_fetch_array($qSqlG)){
		
		if($rSqlG['idGrupo']==$_GET['idGrupo']){
			    
	?>
	<option selected="selected" value="<?php echo $rSqlG['idGrupo']; ?>"><?php echo $rSqlG['NombreGrupo']; ?></option>
    <?php
		} else {
			?>	
	<option value="<?php echo $rSqlG['idGrupo']; ?>"><?php echo $rSqlG['NombreGrupo']; ?></option>
            <?php
		}
	}
	?>
</select>

<a href="#" id="CargarAsignaturas">Cargar asignaturas</a>

<div id="tbGrupoAsig">
    
</div>

