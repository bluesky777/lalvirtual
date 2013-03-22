<?
require_once('conexion.php');
require_once('verificar_sesion.php');

$con=Conectar();


if(($_SESSION['Per']==1) or ($_SESSION['Per']==2)){
	$Semestre=1;
} else {
	$Semestre=2;
}
		

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Insertar Notas</title>
<script type="text/javascript" src="js/jquery.js" ></script>
<script type="text/javascript" src="js/cuenta_regresiva.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		
		$("#NotasSem").submit(function() {

			$.ajax({
				type: 'POST',
				url: 'Guardar_Semestral.php',
				data: $(this).serialize(),
				success: function(data){
					$("#Resultado").html(data);
				},
				beforeSend: function(){
					$('#Resultado').html("<img src='img/loader-mini.gif'/><br/>");
				},
				error: function(data){
					$('#Resultado').html("Hubo problemillas " + data);
				}
			});
            return false;
		});
		
				
		$("#Atras").click(function(e) {
            history.back();
        });
		
    });



</script>

</head>

<body>

<?

$sqlS="select idSemest from tbsemestrales s 
	where s.Year=".$_SESSION['Year']." and s.Semestre=".$Semestre;

$qSqlS=mysql_query($sqlS, $con) or die("Pailas con el semestre. ". mysql_error());;

$NumS=mysql_num_rows($qSqlS);

if($NumS>0){
	$rSqlS=mysql_fetch_array($qSqlS);
}else{
	
	$sqlCre="INSERT INTO `tbsemestrales` (`Semestre`, `FechaInicio`, `FechaFin`, `ActualSemest`, `Year`) VALUES (".$Semestre.", '". date('Y-m-d') ."', '', 1, ".$_SESSION['Year'].");";
	
	$qSqlCre=mysql_query($sqlCre, $con);
	
	// YA QUE CREE semestre, vuelvo a consultar
	$qSqlS=mysql_query($sqlS, $con);
	
	$rSqlS=mysql_fetch_array($qSqlS);
}

$idSem=$rSqlS['idSemest'];

$sqlM="select NombreMateria, NombreGrupo, Semestral, ex.idExa, ex.Semestral 
	from tbgrupos g, tbmaterias m, tbsemestrales s, tbexamenessem ex, tbmateriagrupo mg 
	where g.idGrupo=mg.idGrupo and mg.idMateria=m.idMateria and ex.SemestreExa=s.idSemest 
	and ex.MateriaGrupoExa=".$_GET['idMat']. " 
	and s.idSemest=".$idSem."
	and mg.idMaterGrupo=".$_GET['idMat'];


$qSqlM=mysql_query($sqlM, $con) or die ("No se trajo datos de materia y examen. ".mysql_error());

$numM=mysql_num_rows($qSqlM);

$rSqlM; //Declaro

if ($numM>0){
	$rSqlM=mysql_fetch_array($qSqlM);
	
} else {
	
	$sqlIns="INSERT INTO tbexamenessem(`SemestreExa`, `Semestral`, `PorcExa`, 
		`MateriaGrupoExa`, `FechaExa`, `OrdenExa`) 
		VALUES (1, 'Examen semestral', 100, ".$_GET['idMat'].", '". date('Y-m-d') ."', 1);";
	
	$qSqlIns=mysql_query($sqlIns, $con) or die("No se creó el examen por defecto. " .mysql_error());
	
	$qSqlM=mysql_query($sqlM, $con) or die ("No se trajo los datos insertados. ".mysql_error());

	$rSqlM=mysql_fetch_array($qSqlM);
}

?>

<p>Materia: <b style="font-size:18px"><? echo $rSqlM['NombreMateria']; ?>- <b style="font-size:24px"><? echo $rSqlM['NombreGrupo']; ?></b></b>
</p>
<p>Semestral <b><? echo $Semestre . " - " . $_SESSION['Year']; ?></b><br>
  <br>
</p>
<form name="frmNotasSem" id="NotasSem">

  <p>Descripción:<br/>
    <input type="text" name="txtDesc" value="<? echo $rSqlM['Semestral']; ?>">
  </p>
  <p>&nbsp; </p>
  <table border="1">
    <thead>
	<tr bgcolor="#828282" style="color:#E2E2E2">
	  <th>No</th>
    	<th>
        <input type="hidden" value="<? echo $_GET['idMat'];?>" name="idMat" >
        <input type="hidden" value="<? echo $idSem;?>" name="idSem" >
    	  
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
	$sqlA="select n.Nota 
		from tbnotasemes n 
		where n.idExamen=".$rSqlM['idExa']." and n.idAlumno=".$rSqlL['idAlum'];
		
  $qSqlA=mysql_query($sqlA, $con) or die("No se trajo la nota del alumno ".$rSqlL['NombresAlum']."</br>".mysql_error());

  
  $num=mysql_num_rows($qSqlA);
  
  if ($num>0){
	$rSqlA=mysql_fetch_array($qSqlA);    
  } else {
	$rSqlA['Nota']=0;
  }
  
  ?>  	
	<tr <? if($sw==1){ echo 'bgcolor="#E1E1E1"';$sw=0;}else{$sw=1;} ?>>
		<td>
	  		<? echo $i; ?>
		</td>
      
		<td>
			<? echo $rSqlL['ApellidosAlum']." ".$rSqlL['NombresAlum'];?>
        </td>
        
        <td><input type="text" name="<? echo "Nota".$rSqlL['idAlum']; ?>" size="3" maxlength="3" value="<?php echo $rSqlA['Nota']; ?>" style="margin-left:1; margin-right:1;" >
        </td>
        
		<?

} // Listado While fetch_array $qsqlL

		?>
    </tr>

      </tbody>
</table>
<input type="submit" value="Guardar" id="btGuardar">
<input type="button" value="Atrás" id="Atras" >

</form>
<br>


<div id="Resultado">
	
</div>


</body>
</html>