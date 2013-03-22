<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$RegPosit=0;
$RegNegat=0;

$con=Conectar();
$opt=$_POST['txtTipoUsu'];

//////////////////////////////////  USUARIOS PROFESORES ///////////////////////////////////////////////////
switch($opt){
  case 1:
  
  ?>
  <table border="1">
	<tr>
    	<td>Profesor</td>
        <td>Login</td>
        <td>Password</td>
    </tr>
    
  <?php
	$sqlProf="select idProf, NombresProf, ApellidosProf, UsuarioProf from tbprofesores p, tbyearprofesores yp
		where p.idProf=yp.idProfesor and idYear=" . $_SESSION['Year'];
	$qSqlProf=mysql_query($sqlProf, $con) or die ("No se trajeron los profesores de este año. " . mysql_error());
	
	while($rSqlProf=mysql_fetch_array($qSqlProf)){
		
		$sqlUsu="select idUsu from tbusuarios where idUsu='".$rSqlProf['UsuarioProf']."'";
		$qSqlUsu=mysql_query($sqlUsu, $con) or die ("No se pudo comprobar si el profesor -(".$rSqlProf['idProf']. ")" . $rSqlProf['NombresProf']. "- tiene usuario. " . mysql_error());
		
		$num=mysql_num_rows($qSqlUsu);
		if($num == 0){
			
			$nom = str_replace(' ', '', $rSqlProf['NombresProf']);
			//$cadena = ereg_replace ( "([     ]+)", "", $nom);
			$login=$nom . $rSqlProf['idProf'];
			$pass=$rSqlProf['idProf']. $nom;
			
			$sqlIns="Insert into tbusuarios(LoginUsu, PassUsu, TipoUsu, ActivoUsu) values('". $login ."', '". $pass ."', '2', '1')";
			
			
			$qSqlInsUsu=mysql_query($sqlIns, $con) or die ("No se pudo general el usuario para el profesor -(".$rSqlProf['idProf']. ")" . $rSqlProf['NombresProf']. "-. " . mysql_error());
			
			$id=mysql_insert_id();
			
			$sqlInsProf="update tbprofesores set UsuarioProf='" . $id ."' where idProf='" . $rSqlProf['idProf'] ."'";
			$qSqlInsProf=mysql_query($sqlInsProf, $con) or die ("No se pudo asociar el usuario para el profesor -(".$rSqlProf['idProf']. ")" . $rSqlProf['NombresProf']. "-. " . mysql_error());
			?>
    <tr>
    	<td><?php echo $rSqlProf['NombresProf']; ?></td>
        <td><?php echo $login; ?></td>
        <td><?php echo $pass; ?></td>
    </tr>
            <?php
			
			$RegPosit++;
		} else {
			$RegNegat++;
		}
		
	}
?>    
</table>


<?php
	break;
	/////////////////////////////////////////////////// USUARIOS ALUMNOS //////////////////////////////////
	case 2:
	?>
	  <table border="1">
	<tr>
    	<td>Alumno</td>
        <td>Login</td>
        <td>Password</td>
    </tr>
    
  <?php
	$sqlAlum="select idAlum, NombresAlum, ApellidosAlum, UsuarioAlum 
		from tbalumnos a, tbgrupoalumnos ga, tbperiodos p
		where a.idAlum=ga.idAlumno and ga.idPeriodo=p.idPer and p.Year=" . $_SESSION['Year'];
	$qSqlAlum=mysql_query($sqlAlum, $con) or die ("No se trajeron los alumnos de este año. " . mysql_error());
	
	while($rSqlAlum=mysql_fetch_array($qSqlAlum)){
		
		$sqlUsu="select idUsu from tbusuarios where idUsu='".$rSqlAlum['UsuarioAlum']."'";
		$qSqlUsu=mysql_query($sqlUsu, $con) or die ("No se pudo comprobar si el alumno -(".$rSqlAlum['idAlum']. ")" . $rSqlAlum['NombresAlum']. "- tiene usuario. " . mysql_error());
		
		$num=mysql_num_rows($qSqlUsu);
		if($num == 0){
			
			$nom = str_replace(' ', '', $rSqlAlum['NombresAlum']);
			//$cadena = ereg_replace ("([ ]+)", "",  $rSqlAlum['NombresAlum']);  //Este con expresiones regulares también debe servir.
			$login=$nom . $rSqlAlum['idAlum'];
			$pass=$rSqlAlum['idAlum']. $nom;
			
			$sqlIns="Insert into tbusuarios(LoginUsu, PassUsu, TipoUsu, ActivoUsu) values('". $login ."', '". $pass ."', '2', '1')";
			
			
			$qSqlInsUsu=mysql_query($sqlIns, $con) or die ("No se pudo general el usuario para el alumno -(".$rSqlAlum['idAlum']. ")" . $rSqlAlum['NombresAlum']. "-. " . mysql_error());
			
			$id=mysql_insert_id();
			
			$sqlInsAlum="update tbalumnos set UsuarioAlum='" . $id ."' where idAlum='" . $rSqlAlum['idAlum'] ."'";
			$qSqlInsAlum=mysql_query($sqlInsAlum, $con) or die ("No se pudo asociar el usuario para el Alumno -(".$rSqlAlum['idAlum']. ")" . $rSqlAlum['NombresAlum']. "-. " . mysql_error());
			?>
    <tr>
    	<td><?php echo $rSqlAlum['NombresAlum']; ?></td>
        <td><?php echo $login; ?></td>
        <td><?php echo $pass; ?></td>
    </tr>
            <?php
			
			$RegPosit++;
		} else {
			$RegNegat++;
		}
		
	}
?>    
</table>


<?PHP
	
	break;
//////////////////////////////////////////////////// USUARIOS ACUDIENTES ////////////////////////////////////////////////////
	case 3:
	
	break;
}

echo "Usuarios creados: " . $RegPosit . "<br>Usuarios ignorados: " . $RegNegat;
?>

