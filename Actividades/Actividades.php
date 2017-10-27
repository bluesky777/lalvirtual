<?php
require_once("../verificar_sesion.php");
require_once("clsActividades.php");
 

$idMat=$_GET['idMat'];
$Act = new clsActividades();



?>
<div>ACTIVIDADES</div>
<?php
if(isAdPr()){
?>
<div class="ab AgrAct" id="idMat:<?php echo $idMat; ?>"><a href="javascript:void(0);">Crear actividad</a></div>
<?php
}
$qSqlAct = $Act->gActividades($idMat);
if(mysqli_num_rows($qSqlAct) == 0){
	echo "No hay actividades propuestas en esta materia.";
	die();
}
?>
<div>
	<ul id="ltAct">
		<?php
		$cont=0;
		while($rSqlAct=mysqli_fetch_array($qSqlAct)){
			$cont++;
			$idA=$rSqlAct['idAct'];
		?>
		<li>
			<span class="NoA"><?php echo $cont; ?></span>
			<span class="TitA"><a href="javascript:void(0);" class="" id="Tit:<?php echo $idA; ?>"><?php echo $rSqlAct['TituloAct']; ?></a></span>
			<?php
			if(isAdPr()){
			?>
			<span class="EliA"><img src="../img/icono_eliminar.gif" id="Eli:<?php echo $idA; ?>" /></span>
			<span class="EliA"><img src="../img/icono_editar.png" id="Edi:<?php echo $idA; ?>" /></span>
			<?php
			}
			?>
		</li>
		<?php
		}
		?>

	</ul>

</div>
