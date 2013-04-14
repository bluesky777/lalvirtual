<?php
require_once('conexion.php');
require_once('verificar_sesion.php');

$con=Conectar();

$sqlM="select NombreMateria, g.idGrupo, NombreGrupo from tbgrupos g, tbmaterias m, tbmateriagrupo mg
	 where g.idGrupo=mg.idGrupo and mg.idMateria=m.idMateria and mg.idMaterGrupo=".$_GET['idMat'];
	 
$qSqlM=mysql_query($sqlM, $con)or die ("No se trajo la información del grupo.".mysql_error());

$rSqlM=mysql_fetch_array($qSqlM);

?>

<p>AUSENCIAS</p>

<p>Materia: <b style="font-size:18px"><?php echo $rSqlM['NombreMateria']; ?>- <b style="font-size:24px"><?php echo $rSqlM['NombreGrupo']; ?></b></b></p>

<form name="frmAusencias" id="frmAusencias">

<table border="1">
    <thead>
	<tr bgcolor="#828282" style="color:#E2E2E2">
	  <th>No</th>
    	<th>
        <input type="hidden" value="<?php echo $_GET['idMat'];?>" name="idMat" >
        <input type="hidden" value="<?php echo $rSqlM['idGrupo']; ?>" name="idGrupo" >
    	  
        Apellidos y nombres</th>
           <th title="">Nota</th>
    </tr>
  </thead>

  <tbody>
  <?php

$sqlL="select idAlum, NombresAlum, ApellidosAlum 
	from tbalumnos a, tbgrupoalumnos ga, tbmateriagrupo mg, tbgrupos g 
	where ga.idGrupo=g.idGrupo and ga.idAlumno=a.idAlum and g.idGrupo=mg.idGrupo 
	and mg.idMaterGrupo=".$_GET['idMat'] . " and ga.idPeriodo=".$_SESSION['PeriodoUsu'];

$qSqlL=mysql_query($sqlL, $con) or die ("No se trajo el listado de alumnos. ".mysql_error());

$i=0;
$sw=0;

while($rSqlL=mysql_fetch_array($qSqlL)){
	
	$i++;
	
	$sqlA="select CantidadAus from tbausencias where idAlumno=". $rSqlL['idAlum']. " 
		and idPeriodo=".$_SESSION['PeriodoUsu']." and idMaterGrupo=". $_GET['idMat'];

  $qSqlA=mysql_query($sqlA, $con) or die("No se trajo la nota del alumno ".$rSqlL['NombresAlum']."</br>".mysql_error());

  
  $num=mysql_num_rows($qSqlA);
  
  if ($num>0){
	$rSqlA=mysql_fetch_array($qSqlA);    
  } else {
	$rSqlA['CantidadAus']=0;
  }
  
  ?>  	
	<tr <?php if($sw==1){ echo 'bgcolor="#E1E1E1"';$sw=0;}else{$sw=1;} ?>>
		<td>
	  		<?php echo $i; ?>
		</td>
      
		<td>
			<?php echo $rSqlL['ApellidosAlum']." ".$rSqlL['NombresAlum'];?>
        </td>
        
        <td><input type="text" name="<?php echo "Nota".$rSqlL['idAlum']; ?>" size="3" maxlength="3" value="<?php echo $rSqlA['CantidadAus']; ?>" style="margin-left:1; margin-right:1;" >
        </td>
        
		<?

} // Listado While fetch_array $qsqlL

		?>
    </tr>

  </tbody>
</table>

<p>
  <input type="submit" value="Guardar" id="btGuardar">
  <input type="button" value="Atrás" id="Atras" >
  
</p>

<div id="ResultadoAus">
	
</div>

</form>
