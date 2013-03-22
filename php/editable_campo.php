<?php
require_once ("../conexion.php"); 

$con=Conectar();
 
$posicion = $_POST['row_column'];
$idUsu = $_POST['row_id'];
$valor = $_POST['value'];
$campo;



if($posicion == 0){ $campo = 'idUsu'; }elseif($posicion == 1){ $campo = 'LoginUsu'; }elseif($posicion == 2){ $campo = 'CifradoUsu'; }elseif($posicion == 3){ $campo = 'TipoUsu'; }elseif($posicion == 4){ $campo = 'ActivoUsu'; }

$sql = "update tbusuarios set $campo='$valor' where idUsu=$idUsu";    
$query = mysql_query($sql,$con);

echo $_POST['value'];
?>