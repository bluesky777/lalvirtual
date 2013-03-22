<?php

require_once('conexion.php');
require_once('verificar_sesion.php');

$con=Conectar();

$sql="delete from tbfrasescomportamiento where idFrase=".$_POST['idFrase']." and idComportamiento='".$_POST['idComport']."'";

$qSql=mysql_query($sql, $con) or die("No se pudo quitar la frase ".$_POST['idFrase'].". ".mysql_error());

echo "Removido satisfactoriamente";

?>