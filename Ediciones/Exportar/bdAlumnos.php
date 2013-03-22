<?php
require_once("../verificar_sesion.php");
require_once("../../php/clsSqliteConexion.php");


$Exp=new clsSqliteConexion();
$Exp->Conectar();
$Mybd=$Exp->CrearSqlite("miprueba.db");
$Exp->CrearTablaAlumnos($Mybd);
$Exp->ExportarAlumnos($Mybd);
?>


<?php
//$Exp->CerrarSqlite();
?>