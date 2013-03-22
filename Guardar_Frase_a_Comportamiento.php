<?php

require_once('conexion.php');
require_once('verificar_sesion.php');

$con=Conectar();


$sql="insert into tbfrasescomportamiento(idFrase, idComportamiento) values('".$_POST['idFrase']."','".$_POST['idComport']."');";

$qSql=mysql_query($sql, $con) or die ("No se pudo ingresar la frase. ".mysql_error().$sql);

echo "Frase asignada con éxito";

?>