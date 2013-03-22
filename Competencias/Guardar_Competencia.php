<?php
require_once('../verificar_sesion.php');
require_once('../Competencias/clsCompetencias.php');


$Cmp=new clsCompetencias();
//Cambiar la hora del servidor por la de Colombia
putenv ("TZ=America/Bogota");

if(isset($_POST['OrdenC'])){
    
    $Cmp->GuardarOrden( $_POST['OrdenC'] );
    echo "OrdenadoExitoso";
    
}elseif($_POST['txtOperCompet'] == "GuardarNueva"){

    $Cmp->AgregarCompetencia($_SESSION['PeriodoUsu'], $_POST['txtCompetenciaCompet'], $_POST['txtPorcentajeCompet'], $_POST['txtIdMater'], $_POST['txtOrdenCompet']);

}else{

    $sql="update tbcompetencias set Competencia='". $_POST['txtCompetenciaCompet']."', PorcCompet='".$_POST['txtPorcentajeCompet']."',
        FechaCreacionCompet='".date(" Y/m/d h:i:s",time())."' where idCompet=".$_POST['txtIDCompet'];

    $Cmp->ActualizarCompetencia($sql);

}

$Cmp->Cerrar();
?>