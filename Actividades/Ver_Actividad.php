<?php
require_once("../verificar_sesion.php");
require_once("clsActividades.php");
 

$idAct=$_GET['idAct'];
$Act = new clsActividades();

$rSqlA = $Act->gActiv($idAct);

?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $_SESSION['Usuario']; ?> | Crear actividad</title>

	<link href="../img/favicon.ico" type="image/x-icon" rel="shortcut icon" />
	<link href='css/Ver_Actividad.css' rel='stylesheet' type='text/css'>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="../js/tiny_mce/tiny_mce.js"></script>
	
    <!-- <script type="text/javascript" src="../js/jquery-1.7.2.min.js" ></script> -->
    <!-- <script type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script> -->

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" ></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Signika:300,600' rel='stylesheet' type='text/css'>

	<script type="text/javascript" src="Ver_Actividad.js"></script>

</head>
<body>
<div id="ConteAct">

	<div id="tituA"><?php echo $rSqlA['TituloAct'];?></div>
	<div id="DescA"><?php echo $rSqlA['DescAct'];?><div style="clear: both;"></div></div>

	<input type="hidden" id="idAct" value="<?php echo $idAct; ?>" />

	<div><span>Fecha de inicio: </span><span id="FhIni"><?php echo $rSqlA['FechaInicioAct'];?></span></div>
	<div><span>Plazo máximo: </span><span id="FhFin"><?php echo $rSqlA['FechaFinAct'];?></span></div>
	
	<div>
		<?php
		switch ($_SESSION['TipoUsu']) {
		 	case 1:
		 		?>
		 		<span id="EdA"><a href="javascript:void(0);">Editar</a></span>
		 		<span id="ElA"><a href="javascript:void(0);">Eliminar</a></span>
		 		<span id="TitA">Respondieron</span>

		 		<?php

		 		break;

		 	case 2:
		 		?>
		 		<span id="EdA"><a href="javascript:void(0);">Editar</a></span>
		 		<span id="ElA"><a href="javascript:void(0);">Eliminar</a></span>
		 		<div id="TitA">Estudiantes que respondieron</div>

		 		<?php

		 		break;

		 	case 3:
		 		?>

		 		<div id="TitA">Responder a la actividad</div>

		 		<?php
		 		
		 		
		 		break;

		 	default:
		 		# code...
		 		break;
		 } 
		?>
		
		
	</div>

</div>

</body>
</html>
