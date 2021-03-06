<?php
require_once("../../verificar_sesion.php");

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Bogota');

if (PHP_SAPI == 'cli')
	die('Este ejemplo solo debe ser corrido en un navegador');

require_once '../clsPorcentajesAnio.php';
require_once '../../php/PHPExcel/PHPExcel.php';
require_once("../../php/PHPExcel/PHPExcel/Writer/Excel2007.php");

$objPHPExcel = new PHPExcel();

$Calcs=new clsPorcentajesAnio();
$Calcs->Conectar();

$idGrupo = (isset($_GET['idGrupo'])) ? $_GET['idGrupo'] : 10 ;

$doble = false;
if(isset($_GET['doble'])){
    $doble = true;
}

$Period= $Calcs->gLastPeriodo($idGrupo);
$NomGr = $Calcs->gNombreGrupo($idGrupo);

$TablaPuestos=array();
$TablaMaterias=array();
$PromGrupo=0;
$Calcs->gtbPuestos($idGrupo, $TablaPuestos, $TablaMaterias, $PromGrupo);

$objPHPExcel->getProperties()->setCreator("My Virtual College")
							 ->setLastModifiedBy("My Virtual College")
							 ->setTitle("Reporte de Colegio Virtual")
							 ->setSubject("Puestos de grupo por año para entregar a cada estudiante")
							 ->setDescription("Informe de los puestos del año")
							 ->setKeywords("pdf MyVc")
							 ->setCategory("Reporte MyVc");

$CellTit='C2';
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($CellTit, 'PUESTOS DE '.$NomGr.' - '.$_SESSION['Year']);
$hoja = $objPHPExcel->getActiveSheet();
$hoja->mergeCellsByColumnAndRow(2,2,8,2);

$ColTotal=2; //NO mas ALUMNOS    
foreach($TablaMaterias as $key => $valMat){
    $ColTotal++;
}
$ColTotal++;

// Renombrar worksheet
$hoja->setTitle('Puestos');
$hoja->setShowGridLines(true);

$FilaHead=-1;
$ColHead=0;
$Col=$ColHead;

$Fil=$FilaHead+1;
foreach($TablaPuestos as $key => $value){
    /*
    Original:
    $FilaHead+=2;
    $Fil=$FilaHead+1;
    */

    $FilaHead += 5;
    $Fil=$FilaHead+1;
    $Colu=0;
    

    // CABECERAS
    $hoja->setCellValueByColumnAndRow($Colu, $FilaHead, 'Psto');
    $hoja->getColumnDimensionByColumn($Colu)->setWidth(4); 
    $hoja->setCellValueByColumnAndRow(++$Colu, $FilaHead, 'ALUMNO');
    $hoja->getColumnDimensionByColumn($Colu)->setWidth(20); 
    
    foreach($TablaMaterias as $key => $valMat){
        $hoja->setCellValueByColumnAndRow(++$Colu, $FilaHead, $valMat['AbreviaturaMateria']);
    }
    $hoja->setCellValueByColumnAndRow(++$Colu, $FilaHead, 'TOTAL');


    //Bordes a las dos filas
    //Primero el rango
    $letraUltCol = chr(ord('A') + $ColTotal - 1);

    // Ahora pintamos los bordes
    $hoja->getStyle('A'.$FilaHead.':'.$letraUltCol.$Fil)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


    //Dar negrita al encabezado
    $hoja->getStyle('A'.$FilaHead.':'.$letraUltCol.$FilaHead)->getFont()->setBold(true);
    // FIN CABECERAS


    //UN ALUMNO
    $Colu=0;
    $hoja->setCellValueByColumnAndRow($Colu, $Fil, $value['NO']);
    $hoja->setCellValueByColumnAndRow(++$Colu, $Fil, $value['NombresAlum'].' '.$value['ApellidosAlum']);

    foreach ($value['Materias'] as $keym => $valuem) {
    	$hoja->setCellValueByColumnAndRow(++$Colu, $Fil, $valuem['Definitiva']);
    	$hoja->getColumnDimensionByColumn($Colu)->setWidth(5);
    }
    $hoja->setCellValueByColumnAndRow(++$Colu, $Fil, $value['PromedioAlum']);
    $hoja->setCellValueByColumnAndRow(1, ++$Fil, date(" Y/m/d",time()) );
    $hoja->setCellValueByColumnAndRow(2, $Fil, "Firma:" );

    // FIN UN ALUMNO
    // $Fil++;

    if($doble){
        
        $FilaHead += 5;
        $Fil=$FilaHead+1;
        $Colu=0;
        

        // CABECERAS
        $hoja->setCellValueByColumnAndRow($Colu, $FilaHead, 'Psto');
        $hoja->getColumnDimensionByColumn($Colu)->setWidth(4); 
        $hoja->setCellValueByColumnAndRow(++$Colu, $FilaHead, 'ALUMNO');
        $hoja->getColumnDimensionByColumn($Colu)->setWidth(20); 
        
        foreach($TablaMaterias as $key => $valMat){
            $hoja->setCellValueByColumnAndRow(++$Colu, $FilaHead, $valMat['AbreviaturaMateria']);
        }
        $hoja->setCellValueByColumnAndRow(++$Colu, $FilaHead, 'TOTAL');


        //Bordes a las dos filas
        //Primero el rango
        $letraUltCol = chr(ord('A') + $ColTotal - 1);

        // Ahora pintamos los bordes
        $hoja->getStyle('A'.$FilaHead.':'.$letraUltCol.$Fil)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        //Dar negrita al encabezado
        $hoja->getStyle('A'.$FilaHead.':'.$letraUltCol.$FilaHead)->getFont()->setBold(true);
        // FIN CABECERAS


        //UN ALUMNO
        $Colu=0;
        $hoja->setCellValueByColumnAndRow($Colu, $Fil, $value['NO']);
        $hoja->setCellValueByColumnAndRow(++$Colu, $Fil, $value['NombresAlum'].' '.$value['ApellidosAlum']);

        foreach ($value['Materias'] as $keym => $valuem) {
            $hoja->setCellValueByColumnAndRow(++$Colu, $Fil, $valuem['Definitiva']);
            $hoja->getColumnDimensionByColumn($Colu)->setWidth(5);
        }
        $hoja->setCellValueByColumnAndRow(++$Colu, $Fil, $value['PromedioAlum']);
        $hoja->setCellValueByColumnAndRow(1, ++$Fil, date(" Y/m/d",time()) );
        $hoja->setCellValueByColumnAndRow(2, $Fil, "Firma:" );

        // FIN UN ALUMNO
        // $Fil++;
    }
}

//exit;
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Puestos-MyVc-'.date(" Y/m/d h:i:s",time()).'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;






// Funciones para convertir de números a letras del abecedario y viceversa.
function LetraANo($letra)
{
   if ($letra)
     return ord(strtolower($letra)) - 96;
   else return 0;
}
 
function NoALetra($numero)
{
   if($numero)
     return chr($numero);
   else return 0;
}
?>
