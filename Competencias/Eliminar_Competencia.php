<?php
require_once('../verificar_sesion.php');
require_once('../Competencias/clsCompetencias.php');

$Cmp=new clsCompetencias();
$Cmp->ElimCompetencia($_POST['idComp']);
?>
