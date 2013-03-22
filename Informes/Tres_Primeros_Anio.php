<?php
require_once("../verificar_sesion.php");
require_once("clsPorcentajesAnio.php");


$Calcs=new clsPorcentajesAnio();
$Calcs->Conectar();

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="../Informes/css/Tres_Puestos.css">
<link rel="stylesheet" type="text/css" href="../Principal/reset.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Puestos</title>
</head>

<body>
<center style="display: inline-block; width:600px; height:400px;">
<div class="inforEnc">
	<h1>Tres primero puestos</h1>
	<h2>Año <?php echo $_SESSION['Year'] ; ?></h2>
</div>

<div id="CuadThreeP">
<?php
$idGrupo = (isset($_GET['idGrupo'])) ? $_GET['idGrupo'] : 10 ;

$Period=$Calcs->gLastPeriodo($idGrupo);
$qSqlContAl=$Calcs->gContAlumnosxNomGrupo($idGrupo, $Period);

$rSqlAl=mysql_fetch_array($qSqlContAl);

$NomGr=$rSqlAl['NombreGrupo'];

$ContAlu=$rSqlAl['cuantos'];

if ($ContAlu==0){
    echo "<div class='AnunNoAlum'>Aun no hay alumnos matriculados en el periodo <b>". $_SESSION['Per'] ."</b> para el grupo ".$NomGr=$rSqlAl['NombreGrupo']."</div>";
    die();
}
$qSqlAl=$Calcs->gPromedioxAlum($idGrupo, $Period, 3);
$DatosPuest = array();
while($rSqlAl=mysql_fetch_assoc($qSqlAl)){
	$DatosPuest[]=$rSqlAl;
}
?>
<div class="titulo1"><?php echo $NomGr; ?></div>
	<div class="InfoPuest">
		<div id="SegundoPuest">
			<?php
			
			?>
			<span class="SmallImgPerf"><img src="" title="<? echo $DatosPuest[1]["NombresAlum"];?>"></span>
			<span class="PorcUsu"><? echo number_format($DatosPuest[1]["PromedioAlumTotal"], 1);?></span>
			<span class="NomUsu"><? echo $DatosPuest[1]["NombresAlum"];?></span>
		</div>
		<div id="PrimerPuest">
			<span class="SmallImgPerf"><img src="" title="<? echo $DatosPuest[0]["NombresAlum"];?>"></span>
			<span class="PorcUsu"><? echo number_format($DatosPuest[0]["PromedioAlumTotal"], 1);?></span>
			<span class="NomUsu"><? echo $DatosPuest[0]["NombresAlum"];?></span>
		</div>
		<div id="TercerPuest">
			<span class="SmallImgPerf"><img src="" title="<? echo $DatosPuest[2]["NombresAlum"];?>"></span>
			<span class="PorcUsu"><? echo number_format($DatosPuest[2]["PromedioAlumTotal"], 1);?></span>
			<span class="NomUsu"><? echo $DatosPuest[2]["NombresAlum"];?></span>
		</div>

	</div>

	<div>Total alumnos: <?php echo $ContAlu; ?></div>
</div>

</center>
</body>
</html>

<?php
$Calcs->Cerrar();
?>