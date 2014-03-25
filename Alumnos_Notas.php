<?php
require_once("verificar_sesion.php");
require_once("conexion.php");
include("php/clsHallarDatos.php");

$con=Conectar();
$idComp=$_GET['idComp'];

$idGr = new clsHallarDatos();
$idGru = $idGr->idGrxIdComp($idComp);

$sqlComp="select c.Competencia, m.NombreMateria, NombreGrupo from tbcompetencias c, tbmateriagrupo mg, tbmaterias m, tbgrupos g
		where idCompet=".$idComp." and c.MateriaGrupoCompet=mg.idMaterGrupo and m.idMateria=mg.idMateria and g.idGrupo=mg.idGrupo";

$qSqlComp=mysql_query($sqlComp, $con) or die("No se pudo consultar la competencia ".$idComp);
$rSqlComp=mysql_fetch_array($qSqlComp);

function Promediar($idComp, $idAlum){
	
	$sqlPr="select count(n.idIndic) as totalInd, avg(n.Nota) as Prom, c.Competencia
		from tbnotas n, tbindicadores i, tbcompetencias c 
		where c.idCompet=".$idComp. " and n.idAlumno=".$idAlum." 
		and n.idIndic=i.idIndic and i.CompetenciaIndic=c.idCompet";
			
	$qSqlPr=mysql_query($sqlPr, $con) or die("No se trajo las notas" . mysql_error());
	$rSqlPr=mysql_fetch_array($qSqlPr);

	return $rSqlPr['Prom'];
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Insertar Notas</title>
<script type="text/javascript" src="js/jquery.js" ></script>
<!-- <script type="text/javascript" src="js/cuenta_regresiva.js"></script> -->
<script type="text/javascript" src="js/Alumnos_Notas.js"></script>

<style>
	.LaNota{
		margin-left:1; 
		margin-right:1;
		width: 30px;
		margin: 0;
		border: none;
	}
	.NotaDef{
		background: #FCAEAE;
	}
	.ResFlot{
		position: fixed;
		background: rgb(42, 35, 228);
		padding: 4px 8px;
		border-radius: 4px;
		top: 10px;
		right: 10px;
		color: #fff;
	}
	td, th{
		border: none;
		margin: 0;
		padding: 0;
	}
</style>

</head>

<body background="img/fondito.png">


<span id='Resultado' class='ResFlot'></span>


<p>Materia: <b style="font-size:18px"><?php echo $rSqlComp['NombreMateria']; ?> - <b style="font-size:24px"><?php echo $rSqlComp['NombreGrupo']; ?></b></b>
</p>
<p>Competencia: <b><i style="font-size:24px"><?php echo $rSqlComp['Competencia']; ?></i></b><br>
  <?php
$sqlAlum="select a.idAlum, a.NombresAlum NomA, a.ApellidosAlum ApeA, g.idGrupo, 
	g.NombreGrupo NomG, mg.CreditosMater Cred,
	c.Competencia, c.PorcCompet, c.OrdenCompt
	from tbalumnos a, tbgrupoalumnos ga, tbgrupos g, tbmateriagrupo mg, tbcompetencias c
	where c.idCompet=".$idComp." and c.MateriaGrupoCompet=mg.idMaterGrupo 
	and mg.idGrupo=g.idGrupo 
	and g.idGrupo=ga.idGrupo 
	and ga.idPeriodo=".$_SESSION['PeriodoUsu']."
	AND a.idAlum = ga.idAlumno order by a.ApellidosAlum";

$sqlInd="select * from tbindicadores i where i.CompetenciaIndic=".$idComp." order by OrdenIndic;";


$qSqlInd = mysql_query($sqlInd, $con) or die ("Pailas con los indicadores. " . mysql_error());

if(isAdPr()){
?>
<br>
Cada nota se guardará automáticamente de forma individual, sin embargo puede presionar la tecla <b>Enter</b> o el botón <b>Guardar todo</b> para que guarde todas las notas de forma masiva.
</p>
<form name="tbNotasTodas" id="NotasTodas">
  <table border="1">
  <thead>
	<tr bgcolor="#FFBD35">
	  <th>&nbsp;</th>
    	<th>No</th>
    	<th><input type="hidden" value=<?php echo $idGru; ?> name="txtIdGrupo" >
    	  <input type="hidden" value=<?php echo $idComp; ?> name="txtIdComp" >
        Apellidos y nombres</th>
        
        <?php
		
	while ($rSqlInd = mysql_fetch_array($qSqlInd)){
			
		?>
        
       	<th id="CodInd<?php echo $rSqlInd['idIndic']; ?>" title="<?php echo $rSqlInd['Indicador']; ?>" class="<?php echo $rSqlInd['PorcIndic']; ?>"><?php echo $rSqlInd['OrdenIndic']; ?>
        	  </th>
        <?php
		
	} /// fin while
			
        ?>
        <th>Prom</th>
    </tr>
  </thead>
  <tbody>
  	<?php
	$i=1;
	$qSqlAlum = mysql_query($sqlAlum, $con) or die ("No se trajeron los alumnos". mysql_error());
	$sw=0;
	while ($rSqlAlum=mysql_fetch_array($qSqlAlum)){

		if($sw==0){
			$sw=1;
	?>
	<tr>
	  <td>
      	<img src="img/icono_eliminar.gif" width="17" height="22" style="cursor:pointer" onClick="eliminar(<?php echo "idAlum=".$rSqlAlum['idAlum']."&idComp=".$_GET['idComp']; ?>);"/></td>
    	<td><?php echo $i++; ?></td>
		<td><label title="Este es el estudiante" ><?php echo $rSqlAlum['ApeA']; ?> <?php echo $rSqlAlum['NomA']; ?></label></td>
        
		<?php

		$qSqlInd = mysql_query($sqlInd, $con) or die ("Pailas con los indicadores. " . mysql_error()); //Ejecutamos otra vez
		
		while($rSqlInd = mysql_fetch_array($qSqlInd)){
			
			$sqlNot="select * from tbnotas 
				where idIndic=".$rSqlInd['idIndic']." and idAlumno=".$rSqlAlum['idAlum'];
				
			$qSqlNot=mysql_query($sqlNot, $con) or die ("No se pudo traer 
				el indicador ".$rSqlInd['idIndic']." del alumno:".$rSqlAlum['idAlum']);
				
			$nSqlNot=mysql_num_rows($qSqlNot);

			if($nSqlNot==0){
				$sqlInsNot="insert into tbnotas(idIndic, idAlumno, Nota) values('". $rSqlInd['idIndic']."', '".$rSqlAlum['idAlum']."','". $rSqlInd['NotaPorDefecto']."')";
				
				$qSqlInsNot=mysql_query($sqlInsNot, $con) or die("No se ingresó nota por defecto en indicador " . $rSqlInd['idIndic'] . " - " . mysql_error(). $sqlInsNot);
				

			?>
        <td title="<?php echo $rSqlInd['Indicador']; ?>">
        <input type="text" name="idNotaA<?php echo $rSqlAlum['idAlum']."I".$rSqlInd['idIndic']; ?>" size="3" maxlength="3" value="<?php echo $rSqlInd['NotaPorDefecto']; ?>" class="NotaOnly LaNota"  title="<?php echo $rSqlInd['Indicador']; ?>">
        
        </td>
        	<?php
			} else {
				
				$rSqlNot=mysql_fetch_array($qSqlNot);

				
			?>
        <td><input type="text" name="idNotaA<?php echo $rSqlAlum['idAlum']."I".$rSqlInd['idIndic']; ?>" size="3" maxlength="3" value="<?php echo $rSqlNot['Nota']; ?>" class="NotaOnly LaNota"  title="<?php echo $rSqlInd['Indicador']; ?>" ></td>
        	<?php	
			}
		}
		
		?>
        <td align="center"><label id="Prom<?php echo $rSqlAlum['idAlum']; ?>"><?php //echo Promediar($idComp, $rSqlAlum['idAlum']); ?></label></td>
    </tr>
    <?php
		} else{
			$sw=0;
			?>
	<tr  bgcolor="#FDC4A8">
	  <td><img src="img/icono_eliminar.gif" width="17" height="22" style="cursor:pointer" onClick="eliminar(<?php echo "idAlum=".$rSqlAlum['idAlum']."&idComp=".$_GET['idComp']; ?>);"/></td>
    	<td><?php echo $i++; ?></td>
		<td><?php echo $rSqlAlum['ApeA']; ?> <?php echo $rSqlAlum['NomA']; ?></td>
        
		<?php

		$qSqlInd = mysql_query($sqlInd, $con) or die ("Pailas con los indicadores. " . mysql_error()); //Ejecutamos otra vez
		
		while($rSqlInd = mysql_fetch_array($qSqlInd)){
			$sqlNot="select * from tbnotas where idIndic=".$rSqlInd['idIndic']." and idAlumno=".$rSqlAlum['idAlum'];
			$qSqlNot=mysql_query($sqlNot, $con) or die ("No se pudo traer el indicador ".$rSqlInd['idIndic']." del alumno:".$rSqlAlum['idAlum']);
			$nSqlNot=mysql_num_rows($qSqlNot);

			if($nSqlNot==0){
				//echo ". ".$nSqlNot." esta vacio. ";
				$sqlInsNot="insert into tbnotas(idIndic, idAlumno, Nota) values('". $rSqlInd['idIndic']."', '".$rSqlAlum['idAlum']."','". $rSqlInd['NotaPorDefecto']."')";
				
				$qSqlInsNot=mysql_query($sqlInsNot, $con) or die("No se ingresó nota por defecto en indicador " . $rSqlInd['idIndic'] . " - " . mysql_error(). $sqlInsNot);


			?>
        <td title="<?php echo $rSqlInd['Indicador']; ?>"><input type="text" name="idNotaA<?php echo $rSqlAlum['idAlum']."I".$rSqlInd['idIndic']; ?>" size="3" maxlength="3" value="<?php echo $rSqlInd['NotaPorDefecto']; ?>" class="NotaOnly LaNota"  title="<?php echo $rSqlInd['Indicador']; ?>"></td>
        	<?php
			} else {
				
				$rSqlNot=mysql_fetch_array($qSqlNot);
	
				?>
        <td><input type="text" name="idNotaA<?php echo $rSqlAlum['idAlum']."I".$rSqlInd['idIndic']; ?>" size="3" maxlength="3" value="<?php echo $rSqlNot['Nota']; ?>" class="NotaOnly LaNota"  title="<?php echo $rSqlInd['Indicador']; ?>"></td>
        	<?php	
			}
		}

		?>
        <td align="center"><label id="Prom<?php echo $rSqlAlum['idAlum']; ?>"><?php //echo Promediar($idComp, $rSqlAlum['idAlum']); ?></label></td>
    </tr>
    <?php
		}
	}
	?>
  </tbody>
</table>
  <input type="button" value="Atrás" id="Atras" >
  <input type="submit" value="Guardar todo" id="btGuardar">
<a href="idComp=<?php echo $idComp; ?>" id="LinkEliminar">Eliminar todos</a>
</form>
<?php
}
?>
<br>

</body>
</html>