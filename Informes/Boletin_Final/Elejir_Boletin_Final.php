<?php
require_once("../../verificar_sesion.php");
require_once("../../Alumnos/clsAlumnos.php");

$Alu=new clsAlumnos();

if(in_array("Ver boletines finales", $_SESSION['Privilegios'][0]) or ($_SESSION['TipoUsu']==1)){ 

$qSqlAlu = $Alu->gAlumxPer($_SESSION['PeriodoUsu']); //Traer todos los alumnos de un periodo

$CantiAlu=mysql_num_rows($qSqlAlu);

if($CantiAlu==0){
    echo "<div class='AnunNoAlum'>Aun no hay alumnos matriculados en el periodo <b>". $_SESSION['Per'] ."</b></div>";
    die();
}

?>
<br><br>
<form name="frmBoletinAlumno" id="frmBoletinAlumno" action="../Informes/Boletin_Final/Boletin_Final_Pag.php" method="post">

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
<br>
<input type="checkbox" id="ckFirmas" value="1" name="ckFirmas">
<label for="ckFirmas">Con firmas</label>
<br><br>
<input type="submit" value="Ver boletin"> 

</form>
<br><br>

<hr>

<form name="frmBoletinesAlumnos" id="frmBoletinesAlumnos" action="../Informes/Boletin_Final/Boletines_Finales.php" method="post">
Ver boletines de un grupo:
<select name="cbGrupos" id="idGrupos">
<?php

$qSqlGr = $Alu->gGrupos($_SESSION['Year']);

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
