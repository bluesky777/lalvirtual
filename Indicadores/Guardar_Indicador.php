<?php
require_once('../verificar_sesion.php');
require_once('../Indicadores/clsIndicadores.php');


$Idc=new clsIndicadores();

//Cambiar la hora del servidor por la de Colombia
putenv ("TZ=America/Bogota");

if(isset($_POST['OrdenI'])){  //Quiere decir que solo está guardando el orden de los indicadores
    

    $OrdenI = $_POST['OrdenI'];
    $Idc->GuardarOrdenInd($OrdenI);

    echo "OrdenadoExitoso";
    

}elseif ($_POST['txtOperIndic'] == "GuardarNuevo"){
    
    $Orden = $_POST['txtOrdenIndic'];
    $Indicador = $_POST['txtIndicadorIndic'];
    $Porcentaje = $_POST['txtPorcentajeIndic'];
    $Defec = $_POST['txtDefecIndic'];
    $FechaIni = $_POST['txtFechaIniIndic'];
    $FechaFin = $_POST['txtFechaFinIndic'];
    $IdComp = $_POST['txtIdCompIndic'];

    $Idc->AgregarInd($Orden, $Indicador, $Porcentaje, $Defec, $FechaIni, $FechaFin, $IdComp);

    
} else {
    $idIndic = $_POST['txtIDIndic'];
    $Orden = $_POST['txtOrdenIndic'];
    $Indicador = $_POST['txtIndicadorIndic'];
    $Porcentaje = $_POST['txtPorcentajeIndic'];
    $Defec = $_POST['txtDefecIndic'];
    $FechaIni = $_POST['txtFechaIniIndic'];
    $FechaFin = $_POST['txtFechaFinIndic'];

    $Idc->ActualizarInd($idIndic, $Orden, $Indicador, $Porcentaje, $Defec, $FechaIni, $FechaFin);
    echo "Edición exitosa";
}

$Idc->Cerrar();

?>

