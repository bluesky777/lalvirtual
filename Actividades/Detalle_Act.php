<?php
require_once("../verificar_sesion.php");
require_once("clsActividades.php");
 

if (isset($_GET['idMat'])){
	$idMat=$_GET['idMat'];
}

$Act = new clsActividades();

if (isset($_GET['Oper'])){
	$Cr=false;

	switch ($_GET['Oper']) {
		case 'Guardar':
			echo $Act->CrearActividad($idMat, $_GET['Tipo'], $_GET['Titu'], $_GET['Desc'], $_GET['FhIn'], $_GET['FhFi'], $_GET['Ocul'], $_GET['Arch']);
			break;

		case 'Editar':
			echo $Act->GuardActivEdit($_GET['idAct'], $_GET['Tipo'], $_GET['Titu'], $_GET['Desc'], $_GET['FhIn'], $_GET['FhFi'], $_GET['Ocul'], $_GET['Arch']);
			break;

		case 'Eliminar':
			echo $Act->ElimActiv($_GET['idAct'], $_GET['Tipo'], $_GET['Titu'], $_GET['Desc'], $_GET['FhIn'], $_GET['FhFi'], $_GET['Ocul'], $_GET['Arch']);
			break;
		
		default:
			# code...
			break;
	}
	
	die();
}else{
	$Cr=true;
}

?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $_SESSION['Usuario']; ?> | Crear actividad</title>

	<link href="../img/favicon.ico" type="image/x-icon" rel="shortcut icon" />
	<link href='../css/jquery-ui-1.8.23.custom.css' rel='stylesheet' type='text/css'>
	<link href='../Principal/reset.css' rel='stylesheet' type='text/css'>
	<link href='css/Detalle_Act.css' rel='stylesheet' type='text/css'>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="../js/tiny_mce/tiny_mce.js"></script>
	
    <!-- <script type="text/javascript" src="../js/jquery-1.7.2.min.js" ></script> -->
    <!-- <script type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script> -->

    <script type="text/javascript" src="../js/jquery-1.7.2.min.js" ></script> 
    <script type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script> 
<!--
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" ></script> 
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script> 
    <link href='http://fonts.googleapis.com/css?family=Signika:300,600' rel='stylesheet' type='text/css'>
-->
    <script type="text/javascript" src="../js/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-sliderAccess.js"></script>
	<script type="text/javascript" src="Detalle_Act.js"></script>

</head>
<body>
<div id="ConteAct">

<h1 id="tituA">CREAR ACTIVIDADES</h1>

<form id="frmCrAct">
	<span class="tit" title="Influye en la forma en que los estudiantes responderán a la actividad">Tipo de actividad</span>
	<select name="Tipo">
		<?php
		$qSqlTip = $Act->TiposActividad();
	while($rSqlTip = mysqli_fetch_array($qSqlTip)){
		?>
		<option value="<?php echo $rSqlTip['idTipAct']; ?>" title="<?php echo $rSqlTip['DescTipAct']; ?>"><?php echo $rSqlTip['TipoActividad']; ?></option>
		<?php
	}
		?>
	</select>
	<div>
		<span>Titulo</span>
		<span><input type="text" name="Titu" placeholder="Escriba el título" /></span>
	</div>
	
	<textarea id="elm1" name="Desc" rows="15" cols="80" style="width: 80%">
	</textarea>

	<div>
		<label for="datIni">Fecha inicio</label>
		<span><input type="text" name="FhIn" class="dattim" id="datIni" /></label>

		<label for="datFin">Fecha entrega</label>
		<input type="text" name="FhFi" class="dattim" id="datFin"/>

		<input type="hidden" name="Oper" id="hdOper" value="<?php if($Cr){echo "Guardar";}else{echo "Editar";} ?>" />
		<input type="hidden" name="idMat" id="hdIdMat" value="<?php echo $idMat; ?>" />
	</div>

	<div class="ui-widget">
		<div id="rdOcul">
			<input type="radio" id="radio1" value="1" name="Ocul" /><label for="radio1">Ocultar hasta la fecha</label>
			<input type="radio" id="radio2" value="0" name="Ocul" checked="checked" /><label for="radio2">Mostrar siempre</label>
		</div>
	</div>
	<div>
		<label for="filAd">Adjuntar archivo(en construcción)</label>
		<span>
			<input type="file" name="ArchAdjunto" id="filAd"/>
			<input type="hidden" name="Arch" id="hdIdMat" value="" />
		</span>
	</div>
	<div>
		<input type="submit" class="bt" id="GuarAct" value="Guardar" />
		<input type="reset" class="bt" id="Restablecer" value="Restablecer" />
	</div>
</form>

<div id="RespAct"></div>

</div>

</body>
</html>