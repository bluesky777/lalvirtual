<?php
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();


if(isset($_POST['SelTipoAnun'])){  //Si se viene del formulario ESPECÍFICO

	$sql="INSERT INTO `tbanuncios` (`RemitenteAnu`, `TipoAnuncioAnu`, `ComentEnvioAnu`, `idGrupoAnu`, `FechaAnu`) 
		 VALUES ('".$_SESSION['idUsuar']."', '".$_POST['SelTipoAnun']."', '".$_POST['txtComent_Esp']."', 
		 '".$_POST['hdAnuGrupo']."', '".date('Y/m/d h:i:s',time())."');";

	$qSql=mysql_query($sql, $con)or die("No se pudo anunciar. ".mysql_error());

} else {

}




echo "Guardado.";


?>