<?php

require_once('conexion.php');
require_once('verificar_sesion.php');

$con=Conectar();

$sql="delete from tbfrases where idFrase='".$_POST['idFrase']."'";
$qSql=mysql_query($sql,$con)or die ("No se pudo eliminar la competencia.".mysql_error());

echo "Frase eliminada con éxito.";

?>