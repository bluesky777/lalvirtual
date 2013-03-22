<?php
require_once('../verificar_sesion.php');
require_once('../Indicadores/clsIndicadores.php');


$Idc=new clsIndicadores();

$Idc->ElimInd($_POST['idInd']);

echo "Indicador eliminado satisfactoriamente.";

$Idc->Cerrar();
?>