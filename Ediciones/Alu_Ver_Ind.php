<?php
require_once("../verificar_sesion.php");
require_once("../php/clsConexion.php");
 
$Cn = new clsConexion();
$Cn->Conectar();
		
$CantiAlu=mysqli_num_rows( $Cn->gAlPer() );

if($CantiAlu==0){
    echo "<div class='AnunNoAlum'>Aun no hay alumnos matriculados en el periodo <b>". $_SESSION['Per'] ."</b></div>";
    die();
}

switch ($_SESSION['TipoUsu']) {
	case 1:
	case 2:
		?>
		<div class="Titulo1">
			<p>Ver notas de un alumno:</p>

			<div class="ui-widget">
				<label for="AutcmAl">Alumno:</label>
				<input id="AutcmAl" placeholder="Buscar alumno" />

				<div id="DisNom">
					<span id="DsNom"></span>
				</div>
				<div id="rdPerdT">
					<input checked="checked" type="radio" id="radio1" value="perdidos" name="rdPerdT" /><label for="radio1">Solo perdidas</label>
					<input type="radio" id="radio2" value="todos" name="rdPerdT" /><label for="radio2">Todas las notas</label>
				</div>
				<a href="javascript:void(0);" id="busAl" class="busAl">Buscar</a>
			</div>

		<hr>
		<div class="CntInds"></div>
		<?php
		break;

	case 3:
		?>
		<div class="Titulo1">
			<p>Ver mis notas</p>

			<div class="ui-widget">

				<div id="DisNom">
					<span id="DsNom">Esta operación puede ser muy demorada, por favor tenga paciencia.</span>
				</div>
				<div id="rdPerdT">
					<input checked="checked" type="radio" id="radio1" value="perdidos" name="rdPerdT" /><label for="radio1">Solo perdidas</label>
					<input type="radio" id="radio2" value="todos" name="rdPerdT" /><label for="radio2">Todas las notas</label>
				</div>
				<a href="javascript:void(0);" id="busAl2" class="busAl">Buscar</a>
			</div>

		<hr>
		<div class="CntInds"></div>
		<?php
		break;
	case 4:
		
		break;
	
	default:
		# code...
		break;
}

