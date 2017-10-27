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

$Calcs= new clsPorcentajesAnio();

$objPHPExcel->getProperties()->setCreator("My Virtual College")
                             ->setLastModifiedBy("My Virtual College")
                             ->setTitle("Reporte de Colegio Virtual")
                             ->setSubject("Puestos de grupo por año")
                             ->setDescription("Informe de los puestos del año")
                             ->setKeywords("pdf MyVc")
                             ->setCategory("Reporte MyVc");

$Grupos=$Calcs->gGrupos($_SESSION['Year']);

foreach ($Grupos as $key => $value) {
    set_time_limit(20);
    echo "Grupo:  ". $value['NombreGrupo'];
    $idGrupo=$value['idGrupo'];
    $Peri=$Calcs->gLastPeriodo($idGrupo);

    $qSqlContAl=$Calcs->gContAlumnosxNomGrupo($idGrupo, $Peri);   

    $rSqlAl=mysqli_fetch_array($qSqlContAl);
    $NomGr=$rSqlAl['NombreGrupo'];
    $ContAlu=$rSqlAl['cuantos'];

    $TablaPuestos=array();
    $TablaMaterias=array();
    $Calcs->gtbPuestos($idGrupo, $TablaPuestos, $TablaMaterias);


    $CellTit='C2';
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($CellTit, 'PUESTOS DE '.$NomGr.' - '.$_SESSION['Year']);
    $hoja = $objPHPExcel->getActiveSheet();
    $hoja->mergeCellsByColumnAndRow(2,2,4,2);


    // Renombrar worksheet
    $hoja->setTitle('Puestos');
    $hoja->setShowGridLines(true);

    $FilaHead=3;
    $ColHead=0;
    $ColTotal=$ColHead;
    $Col=$ColHead;
    $hoja->setCellValueByColumnAndRow($Col, $FilaHead, 'NO');
    $hoja->getColumnDimensionByColumn($Col)->setWidth(4); 
    $hoja->setCellValueByColumnAndRow(++$Col, $FilaHead, 'ALUMNOS');
    $hoja->getColumnDimensionByColumn($Col)->setWidth(25); 
    $ColTotal+=2;

    foreach($TablaMaterias as $key => $value){
        $hoja->setCellValueByColumnAndRow(++$Col, $FilaHead, $value['AbreviaturaMateria']);
        $ColTotal++;
    }
    $hoja->setCellValueByColumnAndRow(++$Col, $FilaHead, 'TOTAL');

    //Dar negrita al encabezado
    $letraUltCol = chr(ord('A') + $ColTotal);
    $hoja->getStyle('A'.$FilaHead.':'.$letraUltCol.$FilaHead)->getFont()->setBold(true);

    $Fil=$FilaHead+1;
    foreach($TablaPuestos as $key => $value){
        $Colu=0;
        $hoja->setCellValueByColumnAndRow($Colu, $Fil, $value['NO']);
        $hoja->setCellValueByColumnAndRow(++$Colu, $Fil, $value['NombresAlum'].' '.$value['ApellidosAlum']);

        foreach ($value['Materias'] as $keym => $valuem) {
            $hoja->setCellValueByColumnAndRow(++$Colu, $Fil, $valuem['Definitiva']);
            $hoja->getColumnDimensionByColumn($Colu)->setWidth(6);
        }
        $hoja->setCellValueByColumnAndRow(++$Colu, $Fil, $value['PromedioAlum']);
        $Fil++;
    }


}


//print_r( $Tabla);
//echo '<br><br>';
//$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($numColum,$numRow)->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);
//$sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
//$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->getRGB();


/*
$default_border = array(
    'style' => PHPExcel_Style_Border::BORDER_THIN,
    'color' => array('rgb'=>'1006A3')
);
$style_header = array(
    'borders' => array(
        'bottom' => $default_border,
        'left' => $default_border,
        'top' => $default_border,
        'right' => $default_border,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb'=>'E1E0F7'),
    ),
    'font' => array(
        'bold' => true,
    )
);
 
$sheet->getStyle('A1:A2')->applyFromArray( $style_header );
$sheet->getStyle('B1:B2')->applyFromArray( $style_header );
*/

exit;
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Puestos-MyVc-'.date(" Y/m/d h:i:s",time()).'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
