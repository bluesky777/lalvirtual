<?php
require_once("verificar_sesion_arriba.php");
require_once("../conexion.php");

$con=Conectar();
if(in_array("Ver boletines", $_SESSION['Privilegios'][0]) or ($_SESSION['TipoUsu']==1)){ 

$sqlAlu="select * from tbalumnos a, tbgrupoalumnos ga 
		where a.idAlum=ga.idAlumno and ga.idPeriodo=".$_SESSION['PeriodoUsu']." 
		ORDER BY ApellidosAlum";
$qSqlAlu=mysql_query($sqlAlu, $con)or die("No se pudo traer los alumnos.".mysql_error());

$CantiAlu=mysql_num_rows($qSqlAlu);

if($CantiAlu==0){
    echo "<div class='AnunNoAlum'>Aun no hay alumnos matriculados en el periodo <b>". $_SESSION['Per'] ."</b></div>";
    die();
}

?>
<form name="frmBoletinAlumno" id="frmBoletinAlumno" action="../Informes/Boletin_Alumno.php" method="post">

<p>Ver boletin de un alumno:</p>
<p>
<select name="txtIdAlum" id="txtIdAlum">
<?php

while($rSqlAlu=mysql_fetch_array($qSqlAlu)){

?>
	<option value="<?php echo $rSqlAlu['idAlum']; ?>"><?php echo $rSqlAlu['ApellidosAlum']." ".$rSqlAlu['NombresAlum'];?></option>
<?php
}
?>
</select>
</p>
<input type="submit" value="Ver boletin"> 

</form>


<hr>

<form name="frmBoletinesAlumnos" id="frmBoletinesAlumnos" action="../Informes/Boletines_Alumnos.php" method="post">
Ver boletines de un grupo:
<select name="cbGrupos" id="idGrupos">
<?php
$sqlGr="select * from tbgrupos";
$qSqlGr=mysql_query($sqlGr, $con)or die("No se pudieron traer los grupos.".mysql_error());
while($rSqlGr=mysql_fetch_array($qSqlGr)){

?>
	<option value="<?php echo $rSqlGr['idGrupo']; ?>"><?php echo $rSqlGr['NombreGrupo'];?></option>
<?php
}
?>
</select>
</p>

<input type="submit" value="Ver boletines">
</form>
 
    
<?

}else{
	echo "No tiene permisos para ver los boletines";
}
?>
