<?php
require_once("verificar_sesion.php");
require_once("php/clsAnuncios.php");

$anun = new clsAnuncios();

if(isset($_GET['Op'])){
	$Op=$_GET['Op'];

	switch ($Op) {
		case 'AgrAnu':	//Agregar comentario en anuncio
			$anun->AgregarCmmtAnun($_GET['idAnu'], $_GET['Cmt']);
			break;

		case 'EliAnu':	//Agregar comentario en anuncio
			# code...
			break;
		
		default:
			# code...
			break;
	}

}




?>