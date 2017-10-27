<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery.jeditable.js"></script>
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>

	<style type="text/css" title="currentStyle" media="screen">
		@import "css/demo_page.css";
		@import "css/demo_table.css";
		@import "css/jquery.dataTables.css";
	</style>
	<title>Matricular Alumnos</title>
</head>

<body>

<h2>Matricular o desmatricular alumnos para el grupo <?php echo $_GET['NomGrupo']; ?></h2>


<table border="1" class="display">
  <thead>
      <tr>
        <th>Cod</th>
        <th>No Matricula</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Fecha Nac</th>
        <th>Matricular</th>
      </tr>
  </thead>
  <tbody>
  <?php


  $sql="select idAlum, NoMatriculaAlum, NombresAlum, ApellidosAlum, FechaNacAlum from tbalumnos";
  $qSql=$con->query($sql) or die("No se trajeron los alumnos de este grupo. " .mysqli_error($con));

  while($rSql=mysqli_fetch_array($qSql)){
  ?>
      <tr>
        <td><?php echo $rSql['idAlum']; ?></td>
        <td><?php echo $rSql['NoMatriculaAlum']; ?></td>
        <td><a href="Editar_Alumnos.php?idAlum=<?php echo $rSql['idAlum']; ?>&NoMat=<?php echo $rSql['NoMatriculaAlum']; ?>&Nom=<?php echo $rSql['NombresAlum']; ?>&Ape=<?php echo $rSql['ApellidosAlum']; ?>"><?php echo $rSql['NombresAlum']; ?></a></td>
        <td><?php echo $rSql['ApellidosAlum']; ?></td>
        <td><?php echo $rSql['FechaNacAlum']; ?></td>
     	<td>
        	<?php
			$sqlGrAlum="SELECT * from tbgrupoalumnos 
				where idAlumno='".$rSql['idAlum']."' and idGrupo='".$_GET['idGrupo']."' 
					and idPeriodo='".$_SESSION['PeriodoUsu']."'";

  			$qSqlGrAlum=$con->query($sqlGrAlum) or die("No se pudo hacer la verificación del alumno en el grupo. " . mysqli_error($con) . $sqlGrAlum);
			
			$rSqlGrAlum=mysqli_num_rows($qSqlGrAlum);

			$sw="";
			$idGrTemp="";
			if($rSqlGrAlum==0){
				
				$sqlGr="SELECT ga.idGrupo, Grupo from tbgrupos g, tbgrupoalumnos ga 
				where g.idGrupo=ga.idGrupo and idAlumno='".$rSql['idAlum']."' and idPeriodo='".$_SESSION['PeriodoUsu']."'";
				
				$qSqlGr=$con->query($sqlGr) or die ("No se pudo traer los grupos. " .mysqli_error($con));
				$numgr=mysqli_num_rows($qSqlGr);
				
				while($rSqlGr=mysqli_fetch_array($qSqlGr)){
					
					if(!($rSqlGr['idGrupo']==$_GET['idGrupo'])){
						$sw=$rSqlGr['Grupo'];
						$idGrTemp=$rSqlGr['idGrupo'];
						//return false;
					}
				}
				
				if($sw!=""){
					echo "Grupo ". $sw . " <a href='Desmatricular_Alumno.php?idAlum=".$rSql['idAlum']."&idGrup=". $idGrTemp ."' id='' class='Matriculador'>Desmatricular</a>";
					$sw="";
				} else {
					?>
					<a class="Matricular" href="Matricular_Alumno_Guardar.php?idAlum=<?php echo $rSql['idAlum']; ?>&idGrup=<?php echo $_GET['idGrupo']; ?>">Matricular</a>
					<?php
				}
			?>
            
            <?php
			}else{
			?>
				<b><a href="Desmatricular_Alumno.php?idAlum=<?php echo $rSql['idAlum']; ?>&idGrup=<?php echo $_GET['idGrupo']; ?>" id="Desmatricular_Alumno.php?idAlum=<?php echo $rSql['idAlum']; ?>&idGrup=<?php echo $_GET['idGrupo']; ?>" class="Matriculador">Desmatricular</a></b>
            <?php
			}
			?>
        	
        </td>
      </tr>
   <?php
   }
   ?>
   </tbody>
</table>



<script type="text/javascript">
	$(document).on("ready", function() {
		$('.display').dataTable();
		
		$(".Matriculador").on("click", function(evt){
			console.log("Presionado el link");
			evt.preventDefault();
			$.get($(this).attr("id"), function(e){
				alert(e);
				//window.location.reload();
			});
		});
		
		
	});
</script>
</body>
</html>