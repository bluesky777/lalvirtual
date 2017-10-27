<?php
require_once('conexion.php');
require_once('verificar_sesion.php');

$con=Conectar();

$num=0;
$mensajes=array();

$sqlG="select * from tbgrupos where YearGrupo='".$_SESSION['Year']."'";
$qSqlG=mysql_query($sqlG, $con) or die ("Nada que ver con los grupos a guardar. ". mysql_error());
while($rSqlG=mysqli_fetch_array($qSqlG)){
    $cNivel="NivelGrupo".$rSqlG['idGrupo'];
    $cGrupo="Grupo".$rSqlG['idGrupo'];
    $cTitular="TitularGrupo".$rSqlG['idGrupo'];
    $cNombre="NombreGrupo".$rSqlG['idGrupo'];
    $cDescrip="DescripNombreGrupo".$rSqlG['idGrupo'];
    $cImgHorario="ImgHorarioGrupo".$rSqlG['idGrupo'];
    $cValorMat="ValorMatricula".$rSqlG['idGrupo'];
    $cValorPen="ValorPension".$rSqlG['idGrupo'];
    $cOrden="OrdenGrupo".$rSqlG['idGrupo'];
    /*
    $nombreImg = $_FILES[$cImgHorario]['name'];
    $tipoImg = $_FILES[$cImgHorario]['type'];
    $tamanoImg = $_FILES[$cImgHorario]['size'];
    move_uploaded_file ($_FILES[$cImgHorario]['tmp_name'],$directorio.$nombre);
    */

    $sqlEdit="update tbgrupos set 
        NivelGrupo='".$_POST[$cNivel]."', NivelGrupo='".$_POST[$cNivel]."', 
        Grupo='".$_POST[$cGrupo]."', TitularGrupo='$_POST[$cTitular]',
        NombreGrupo='$_POST[$cNombre]', DescripNombreGrupo='$_POST[$cDescrip]',
        ValorMatricula='$_POST[$cValorMat]',
        ValorPension='$_POST[$cValorPen]'
        where idGrupo='".$rSqlG['idGrupo']."'";

    $qSqlEdit=mysql_query($sqlEdit, $con);

    if (mysql_affected_rows()!=0){
            //No se pudo hacer el cambio
        $num++;
        $mensajes[$num] = $_POST[$cNombre];
    }
}

if ($num == 1){
	echo "Se modificó el grupo: " . $mensajes[$num];

}else if ($num > 1){
	echo "Se modificaron los grupos: ";
	$j=0;
	while($j < count($mensajes)){
		$j++;
		echo $mensajes[$j] . "- ";
	}
} else {
	echo "No se hizo ninguna modificación";
}
?>