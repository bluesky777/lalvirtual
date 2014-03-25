<?php
require_once("../../verificar_sesion.php");
require_once("../clsCalcularPorc.php");
require_once("../../php/pdf-php/class.ezpdf.php");

set_time_limit(0);

$Calcs=new clsCalcularPorc();
$Calcs->Conectar();

$pdf = new Cezpdf();


$datacreator = array (
                    'Title'=>'Boletin final',
                    'Subject'=>'Resumen de todas las materias del año.',
                    'Author'=>'MyVC',
                    'Producer'=>'BlueSky Tech'
                    );
$pdf->addInfo($datacreator);


$idGrupo=$_GET['idGrupo'];

$qSqlDef=$Calcs->gDefAlumnosxGrupo($idGrupo);

//Arreglemos los arrays
$TablaArreglada=array();
$TablaxAlum=array();
$st=0;
while ($rSqlDef=mysql_fetch_assoc($qSqlDef)) {
	if($st==0){
		$st=$rSqlDef["idAlumno"];
	}
	if($st==$rSqlDef["idAlumno"]){
		$TablaArreglada[]=$rSqlDef;
	}else{
		$st=$rSqlDef["idAlumno"];
		$TablaxAlum[]=$TablaArreglada;

		unset($TablaArreglada);
		$TablaArreglada = array();

	}
	
}



//Materias del grupo con sus datos en una tabla
$tbMaterias=$Calcs->MateriasxGrupo($TablaxAlum);

$Titles = array(
	"idAlumno"=>"idAlumno",
	"NoMatriculaAlum"=>"NoMatriculaAlum",
	"NombresAlum"=>"NombresAlum",
	"ApellidosAlum"=>"ApellidosAlum",
	"SexoAlum"=>"SexoAlum",
	"UsuarioAlum"=>"UsuarioAlum",
	"NombreMateria"=>"NombreMateria",
	"AliasMateria"=>"AliasMateria",
	"idMaterGrupo"=>"idMaterGrupo",
	"idMateria"=>"idMateria",
	"idProfesor"=>"idProfesor",
	"PeriodoCompet"=>"PeriodoCompet",
	"CreditosMater"=>"CreditosMater",
	"OrdenMater"=>"OrdenMater",
	"DefMateria"=>"DefMateria"
);

//echo '<pre>'; 
//print_r($TablaxAlum);
//exit;

//Para cada alumno, una hoja
foreach ($TablaxAlum as $keyA => $Alumno) {
	$pdf->selectFont('fonts/Times-Roman.afm');

	$pdf->ezImage("../../img/Colegio/LogoLAL.jpg", 0, 50, 'none', 'left');
	$pdf->addTextWrap(50,800,280,16,"<b>LICEO ADVENTISTA LIBERTAD</b>",'center');
die("Probando 1");	
	$pdf->addTextWrap(10,790,300,8,utf8_decode("La suscrita Rectora y secretaria del LAL, reconocida oficialmente por la Secretaria"),'center');
	$pdf->addTextWrap(10,782,300,8,utf8_decode("de Educación Departamental, mediante Resolución 503 de 2003,  los estudios correspondientes"),'center');
	$pdf->addTextWrap(10,774,300,8,utf8_decode("a los programas de Educación  Preescolar, Basica Primaria, Basica Secundaria y Media, con"),'center');
	$pdf->addTextWrap(10,766,300,8,utf8_decode("registro de Inscripción ante la Secretaría  de Educación  Departamental N.07 y Registro del DANE 381794004629."),'center');
	$pdf->addTextWrap(10,758,300,8,utf8_decode("Nit.900067684-0. Artículo 87 de la ley 115 de 1994 y el artículo 11 del Decreto 1860 de agosto 03 de 1994,"),'center');
	$pdf->addTextWrap(10,750,300,8,utf8_decode("con Registro Icfes Nº 099275."),'center');
    
    $pdf->line(20,748,570,748);
    $pdf->addTextWrap(50,735,400,12,"<b>RESUMEN ANUAL</b>",'center');

	
	
	$DetalA=array();
	foreach ($Alumno as $keyDa => $Detalle) {
		$DetalA=$Detalle;
		break;
	}

	$pdf->addText(30,720,12,utf8_decode("<b>NOMBRE:</b> ".$DetalA['ApellidosAlum']." ".$DetalA['NombresAlum']),'center');

	$pdf->selectFont('fonts/Helvetica-Bold.afm');

	$pdf->addTextWrap(25,694,300,10,utf8_decode("         MATERIAS                                       Per1         Per2        Per3        Per4        Definitiva        Indicador"),'left');
	$pdf->line(25,690,570,690);

	$y=680;
	foreach ($tbMaterias as $keyM => $Materia) {
		//$pdf->addTextWrap(25, $y, 100,9, substr($Materia["NombreMateria"], 0,30));
		$y-=15;
	}

	$tbPeriodos=array();
	$Pt=0;
	$TempMat=array();
	foreach ($Alumno as $keyMa => $Mater) {

		if($Pt==0) $Pt=$Periodos['PeriodoCompet'];

		if($Pt == $Periodos['PeriodoCompet']){
			$TempMat[]=$Periodos;
		}else{
			$tbPeriodos[]=$TempMat;
			unset($TempMat);
			$TempMat=array();
			$Pt=$Periodos['PeriodoCompet'];
		}
	}
print_r($tbPeriodos);
	$Pox=200;
	$Esx=20;
	$y=680;
	foreach ($tbPeriodos as $keyDef => $Defi) {
		$pdf->addTextWrap(25, $y, 100,9, substr($Materia["NombreMateria"], 0,30));

		foreach ($Defi as $keyDP => $DefiP) {
			$pdf->addText(30,720,12, $DefiP[""] );
		}
		$y-=15;
	}

	$pdf->ezNewPage();
}


//$pdf->addText(5, 800, 9, "<div>".$rSqlDef."</div>");

/*
//Para US letter of X=612.00 and Y=792.00.

$pdf->ezText("Tomalo \n ", 20);
$pdf->ezImage("../../img/Colegio/Logo.jpg", 0, 100, 'none', 'left', array('color'=>'black'));
$pdf->ezImage("../../img/icono_editar.jpg", 0, 30, 'none', 'left');

$pdf->addJpegFromFile("../../img/Colegio/Logo.jpg", 10, 10, 100);
$pdf->ezText(count($rSqlDef));
*/

/*

for($i=0; $i<5; $i++){
	$pdf->ezText("Bu\n");
	$pdf->ezNewPage();
}


$con=0;
while(true){
	$pdf->ezText("Bu");
	$con++;
	if ($con > 1000){
		break;
	}
}
*/
// Número de página
$pdf->ezStartPageNumbers(300, 200, 10,"","{PAGENUM} of {TOTALPAGENUM} pages");

//foreach ($rSqlDef as $key => $value) {
//	$pdf->ezText(print_r($key));
//}

ob_end_clean();
$pdf->ezStream();


?>
