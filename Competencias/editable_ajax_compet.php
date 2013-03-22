<?php
require_once("conexion.php");
  
$posicion = $_POST['row_column'];
$id = $_POST['row_id'];
$valor = $_POST['value'];
$con=Conectar(); 
if($posicion == 0){ $campo = 'OrdenCompt'; }elseif($posicion == 1){ $campo = ''; }elseif($posicion == 2){ $campo = 'PorcCompet'; }
$sql = "update tbcompetencias set $campo='$valor' where idCompet=$id";    
$query = mysql_query($sql,$con);
echo $_POST['value'];
?>