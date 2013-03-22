<?php
require_once("../../verificar_sesion.php");
require_once("../clsCalcularPorc.php");
require_once("../../php/pdf-php/class.ezpdf.php");


$Calcs=new clsCalcularPorc();
$Calcs->Conectar();

$pdf = new Cezpdf();
$pdf->selectFont('fonts/Helvetica-Bold.afm');


$idGrupo=$_GET['idGrupo'];

$rSqlDef=$Calcs->gDefAlumnosxGrupo($idGrupo, $_SESSION['PeriodoUsu']);

foreach ($rSqlDef as $key => $value) {
	$pdf->ezText("Mi Primer Archivo PDF en PHP.");
}


$pdf->ezStream();


?>
