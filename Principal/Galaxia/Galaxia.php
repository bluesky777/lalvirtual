<?php
require_once('../../verificar_sesion.php');
require_once('../../php/clsAnuncios.php');
require_once('../../Actividades/clsActividades.php');
require_once('../../Materias/clsMaterias.php');
require_once('clsGalaxia.php');

$Anu=new clsAnuncios();
$Gx=new clsGalaxia();

$my=false;
$tipoU=$_SESSION['TipoUsu'];


if (isset($_GET['My'])){
	$my=true;
}

$idUsu=$_SESSION['idUsuar'];
$TipoUsu = $_SESSION['TipoUsu'];

?>
<div>

<div id="gxAn" class="">
	<div class="xven">
		<div class="xti">
			<?php
			$qSqlAn = $Anu->gAnuncios($idUsu, $TipoUsu);
			$numAn = mysql_num_rows($qSqlAn);

			?>
			<span class="xtit"><a href="#">Anuncios</a></span>
			<span class="xnoty">
				<?php
				if ($numAn > 0){
					?>
					<span class="xnotyCont">(<span class="xContAnu"><?php echo $numAn; ?></span>)
					</span>
					<?php
				}
				?>
			</span>
		</div>
		<div class="xcont">
			<div class="xlis">
				<ul>
			<?php
			while ($rSqlAn=mysql_fetch_array($qSqlAn)) {
				?>
					<li><a href="#" id="idAn:<?php echo $rSqlAn['idAnu']; ?>" class="xkAnu"><?php echo $rSqlAn['ComentEnvioAnu']; ?></a></li>
				<?php
			}
			?>
				</ul>
			</div>
		</div>
	</div>

</div>


<?php
if (!isAdm() and !isAcud()){
?>
<div id="gxAn" class="">
	<div class="xven">
		<div class="xti">
			<?php
			$numAct = $Gx->gNumActividades($idUsu, $TipoUsu);

			?>
			<span class="xtit"><a href="#">Actividades</a></span>
			<span class="xnoty">
				<?php
				if ($numAct > 0){
					?>
					<span class="xnotyCont">(<span class="xContAnu"><?php echo $numAct; ?></span>)
					</span>
					<?php
				}
				?>
			</span>
		</div>
		<div class="xcont">
			<div class="xlis">
				<ul>
			<?php
			$Act= new clsActividades();
			$qSqlAc = $Act->gActVigentesxUsu($idUsu, $TipoUsu);

			while ($rSqlAc=mysql_fetch_array($qSqlAc)) {
				?>
					<li><a href="#"><?php echo $rSqlAc['TituloAct']; ?></a></li>
				<?php
			}
			?>
				</ul>
			</div>
		</div>
	</div>

</div>
<?php
}
?>

</div>


<?php if($tipoU==2 or $tipoU==1) { ?>
<input type="button" id="btBolFinales" class="bt1" value="Boletines finales" />
<?php
}
?>


