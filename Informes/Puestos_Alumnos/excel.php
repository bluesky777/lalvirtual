<?php
//conexión con la Base de Datos 
require_once("config.php");
$config = new Config();
$filas = $config->getClientes();

//Traemos las librerias necesarias
require_once("public/phpexcel/Classes/PHPExcel.php");
require_once("public/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");

//objeto de PHP Excel
$objPHPExcel = new PHPExcel();

//algunos datos sobre autoría
$objPHPExcel->getProperties()->setCreator("Francisco Mora(@Itrativo)");
$objPHPExcel->getProperties()->setLastModifiedBy("Francisco Mora(@itrativo)");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Reporte de Clientes");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Reporte de Clientes");
$objPHPExcel->getProperties()->setDescription("Reporte de Clientes para Office 2007 XLSX, Usando PHPExcel.");

//Trabajamos con la hoja activa principal
$objPHPExcel->setActiveSheetIndex(0);

//iteramos para los resultados
foreach($filas as $row){
    $objPHPExcel->getActiveSheet()->SetCellValue("B".$row["id_cli"], $row["rut_cli"]);
    $objPHPExcel->getActiveSheet()->SetCellValue("C".$row["id_cli"], $row["nombre_cli"]);
    $objPHPExcel->getActiveSheet()->setCellValue("D".$row["id_cli"], $row["correo_cli"]);
    $objPHPExcel->getActiveSheet()->setCellValue("E".$row["id_cli"], $row["telefono_cli"]);
    $objPHPExcel->getActiveSheet()->setCellValue("F".$row["id_cli"], $row["pais_cli"]);
}

//Titulo del libro y seguridad 
$objPHPExcel->getActiveSheet()->setTitle('Reporte');
$objPHPExcel->getSecurity()->setLockWindows(true);
$objPHPExcel->getSecurity()->setLockStructure(true);


// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporteClientes.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
