<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();
?>

<center>
<p>
</p>
<p>Asigne las materias de este grupo:</p>
<form id="frmMaterGrupo" method="post" action="">
<table id="tbGrupoAsig" border="1px">
  <thead>
	<tr>
    	<th>Orden</th>
        <th>Materia</th>
        <th>Area</th>
        <th>Profesor</th>
        <th>Creditos</th>
        <th></th>
    </tr>
  </thead>
  <tbody>
  <?php
  $sqlM="select * from tbmateriagrupo where idGrupo=" . $_GET['idGrupo'] . " order by OrdenMater";
  $qSqlM=mysql_query($sqlM, $con) or die("No se trajo las materias asignadas al grupo");
  while($rSqlM=mysql_fetch_array($qSqlM)){
  ?>
    <tr>
    	<td><?php echo $rSqlM['OrdenMater']; ?></td>
        <td>
		
        <select name="idMateria<?php echo $rSqlM['idMaterGrupo']; ?>"  style="width:200px;background-color:#DDFFFF;" >
        <?php
		$sqlMat="select * from tbmaterias m, tbyearmateria ym where ym.idYear=".$_SESSION['Year']. " and m.idMateria=ym.idMateria";
		
		$qSqlMat=mysql_query($sqlMat, $con) or die ("Pailander con las materias" . mysql_error());
		
		while($rSqlMat= mysql_fetch_array($qSqlMat)){
			if ($rSqlMat['idMateria']==$rSqlM['idMateria']){
		?>
            <option selected value="<?php echo $rSqlMat['idMateria']; ?>"><?php echo $rSqlMat['NombreMateria']; ?>
            </option>
		<?php
			} else {
		?>
			<option value="<?php echo $rSqlMat['idMateria']; ?>"><?php echo $rSqlMat['NombreMateria']; ?>
            </option>
		<?php	
			}
		}
        ?>
        </select>
        
        </td>
        <td>
        	<div id="Area<?php echo $rSqlM['idMaterGrupo']; ?>"></div>
        </td>
        <td>
            <select name="idProfesor<?php echo $rSqlM['idMaterGrupo']; ?>"  style="width:200px;background-color:#DDFFFF;">
            <?php
            $sqlProf="select * from tbprofesores p, tbyearprofesores yp where yp.idYear=".$_SESSION['Year']. " and p.idProf=yp.idProfesor";
            $qSqlProf=mysql_query($sqlProf, $con) or die ("Pailander con los profesores" . mysql_error());
            
            while($rSqlProf= mysql_fetch_array($qSqlProf)){
                if ($rSqlProf['idProf']==$rSqlM['idProfesor']){
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
        <td>
       	  <input type="text" name="CreditosMater<?php echo $rSqlM['idMaterGrupo']; ?>" value="<?php echo $rSqlM['CreditosMater']; ?>" size="3">
        </td>
        <td>
        	<a href="javascript:void(0);" id="../Editar_Materia_Inscrita.php?idMatGr=<?php echo $rSqlM['idMaterGrupo']; ?>" class="Editar" title="Editar asignatura asignada">
                <img src="../img/icono_editar.jpg" width="15" height="16" /></a> 
            <a href="javascript:void(0);" id="<?php echo $rSqlM['idMaterGrupo']; ?>" class="Eliminar" title="Editar asignatura asignada">
                <img src="../img/icono_eliminar.gif" width="16" height="19" /></a> 
        </td>
    </tr>
  <?php
  }
  ?>
  </tbody>
</table>
</form>

<input type="button" name="btNuevo" id="btNuevo" value="Inscribir materia">

<div id="NuevaMateria" style="width:400px">
<form name="NuevaMat" action="" method="post" id="NuevaMat">
<fieldset>
<legend>Inscribir materias</legend>
	
    	<table border="1px">
          <tr>
          	<td  bgcolor="#0066FF">Orden<input type="hidden" name="txtGrupo" value="<?php echo $_GET['idGrupo']; ?>">
            </td>
            
            <td>
                <input type="text" name="txtOrden" size="5" id="txtOrden" maxlength="2">
            </td>
          </tr>
            <td bgcolor="#0066FF">Materia</td>
            <td>
            <select name="txtMateria"  style="width:200px;background-color:#DDFFFF;" >
            <?php
            $sqlMat="select * from tbmaterias m, tbyearmateria ym where ym.idYear=".$_SESSION['Year']. " and m.idMateria=ym.idMateria";
            
            $qSqlMat=mysql_query($sqlMat, $con) or die ("Pailander con las materias" . mysql_error());
            
            while($rSqlMat= mysql_fetch_array($qSqlMat)){
            ?>
				<option value="<?php echo $rSqlMat['idMateria']; ?>"><?php echo $rSqlMat['NombreMateria']; ?>
                </option>
            <?php	
            }
            ?>
            </select>
          </td>
          </tr>
          <tr>
          	<td bgcolor="#0066FF">Profesor</td>
            <td>
				<select name="txtProfesor" style="width:200px;background-color:#DDFFFF;">
					<?php
                    $sqlProf="select * from tbprofesores p, tbyearprofesores yp where yp.idYear=".$_SESSION['Year']. " and p.idProf=yp.idProfesor";

                    $qSqlProf=mysql_query($sqlProf, $con) or die ("Pailander con los profesores" . mysql_error());
                    
                    while($rSqlProf= mysql_fetch_array($qSqlProf)){
                   	?>
                    <option value="<?php echo $rSqlProf['idProf']; ?>"><?php echo $rSqlProf['NombresProf']; ?> <?php echo $rSqlProf['ApellidosProf']; ?>
                    </option>
                    <?php	
                    }
                ?>
                </select>
            </td>
          </tr>
          <tr>
          	<td bgcolor="#0066FF">Créditos</td>
            <td><input type="text" name="txtCreditos" size="5" id="txtCreditos" maxlength="2"></td>
          </tr>
        </table>
        
        <input type="submit" value="Guardar" id="Guardar">
        <input type="reset" value="Cancelar" id="Cancelar">
    
</fieldset>
</form>


</div>
<div id="Resultado"></div>
</center>
