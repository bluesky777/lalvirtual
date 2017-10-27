<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();

$sqlG="select * from tbgrupos where YearGrupo=".$_SESSION['Year']. " order by OrdenGrupo";

$qSlqG=$con->query($sqlG) or die ("No se pudo traer los grupos. " . mysql_error());

$rG=mysqli_num_rows($qSlqG);
?>
<center>
<h2>Modificar grupos</h2>

<form name="frmGrupos" ID="Formulario" action="" method="post" enctype="multipart/form-data">

<table id="tbGrupos" border="1px">
<thead>
	<tr id="Encabezados"  align="center">
    	<td colspan="3"><input type="hidden" name="CantGrupo" value="<?php echo $rG; ?>">
    	  Opciones</td>
    	<td>Orden</td>
        <td>Nivel</td>
        <td>Grupo</td>
        <td>Nombre</td>
        <td>Titular</td>
        <td>Descripción</td>
        <td>Horario</td>
        <td>Matrícula($)</td>
        <td>Pensión($)</td>
    </tr>
</thead>
<tbody>

<?php

while($rSqlG=mysqli_fetch_array($qSlqG)){
?>

	<tr>
    	<td>
            <a href="../Alumnos_Grupo.php?idGrupo=<?php echo $rSqlG['idGrupo']; ?>&NomGrupo=<?php echo $rSqlG['NombreGrupo']; ?>&Grupo=<?php echo $rSqlG['Grupo']; ?>">
                <img src="../img/ico-alumnos.gif" alt="Alumnos" class="imgOptions" title="Alumnos" />
            </a>
        </td>
    	<td>
            <a href="../Matricular_Alumno_Grupo.php?idGrupo=<?php echo $rSqlG['idGrupo']; ?>&NomGrupo=<?php echo $rSqlG['Grupo']; ?>">
                <img src="../img/icono-matricular.gif" alt="Matricular" class="imgOptions" title="Matricular alumnos" />
            </a>
        </td>
    	<td>
            <a href="javascript:void(0);" id="../Asignaturas_Grupo.php?idGrupo=<?php echo $rSqlG['idGrupo']; ?>" class="GruAsignaturas">
                <img src="../img/icono-materias.png" alt="Asignaturas" class="imgOptions" title="Modificar asignaturas" />
            </a>
        </td>
    	<td bgcolor="#D8D7EE">
            <select name="OrdenGrupo<?php echo $rSqlG['idGrupo']; ?>" title="Cambie el orden del grupo">
            	<?php
                for($i=0; $i<=$rG; $i++){
                    if($i==$rSqlG['OrdenGrupo']){
                ?>
           	  <option selected><?php echo $i; ?></option>
                <?php
                    } else {
                ?>
            	<option><?php echo $i; ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </td>
        <td>
            <?php
            $sqlNiv="Select * from tbnivel";
            $qSqlNiv=$con->query($sqlNiv) or die ("No se trajeron los niveles. " . mysql_error());
            ?>
            <select name="NivelGrupo<?php echo $rSqlG['idGrupo']; ?>" title="Cambie el nivel del grupo">
				<?php
                while($rSqlNiv=mysqli_fetch_array($qSqlNiv)){
                    if($rSqlNiv['OrdenNivel']==$rSqlG['NivelGrupo']){
                ?>
                    <option selected value="<?php echo $rSqlNiv['OrdenNivel']; ?>"><?php echo $rSqlNiv['NombreNivel']; ?></option>
                <?php
                        } else {
                        ?>
                    <option value="<?php echo $rSqlNiv['OrdenNivel']; ?>"><?php echo $rSqlNiv['NombreNivel']; ?></option>
                <?php
                        }
                }
				
                ?>
            </select>
        </td>
        <td><input type="text" value="<?php echo $rSqlG['Grupo']; ?>" name="Grupo<?php echo $rSqlG['idGrupo']; ?>" size="2"></td>
        <td><input type="text" value="<?php echo $rSqlG['NombreGrupo']; ?>" name="NombreGrupo<?php echo $rSqlG['idGrupo']; ?>" size="7"></td>
        <td>
        <select name="TitularGrupo<?php echo $rSqlG['idGrupo']; ?>" class="SeleTitular">
        <?php
		$sqlProf="select * from tbprofesores p, tbyearprofesores yp where yp.idYear=".$_SESSION['Year']. " and p.idProf=yp.idProfesor";
		$qSqlProf=$con->query($sqlProf) or die ("Pailander con los profesores" . mysqli_error( $con));
		
		while($rSqlProf= mysqli_fetch_array($qSqlProf)){
			if ($rSqlProf['idProf']==$rSqlG['TitularGrupo']){
		?>
            <option selected value="<?php echo $rSqlProf['idProf']; ?>"><?php echo $rSqlProf['NombresProf']; ?> <?php echo $rSqlProf['ApellidosProf']; ?>
            </option>
		<?php
			} else {
		?>
			<option value="<?php echo $rSqlProf['idProf']; ?>"><?php echo $rSqlProf['NombresProf']; ?> <?php echo $rSqlProf['ApellidosProf']; ?>
            </option>
		<?php	
			}
		}
        ?>
        </select>
        </td>
        <td><input type="text" value="<?php echo $rSqlG['DescripNombreGrupo']; ?>" name="DescripNombreGrupo<?php echo $rSqlG['idGrupo']; ?>" size="8"></td>
        <td>
            <img src="../img/Horarios/<?php echo $rSqlG['Grupo']; ?>-<?php echo $_SESSION['Year'];?>.PNG" class="imgHorar" title="Click para ver grande" />
        </td>
        <td><input type="text" value="<?php echo $rSqlG['ValorMatricula']; ?>" name="ValorMatricula<?php echo $rSqlG['idGrupo']; ?>" size="5"></td>
        <td><input type="text" value="<?php echo $rSqlG['ValorPension']; ?>" name="ValorPension<?php echo $rSqlG['idGrupo']; ?>"  size="5"></td>
    </tr>
<?php
}
?>
</tbody>
</table>

<input class="btFrmGrup" type="reset" value="Restablecer" id="Restablecer" >
<input class="btFrmGrup" type="submit" value="GUARDAR TODO">
</form>

<div id="Resultado"></div>
</center>
