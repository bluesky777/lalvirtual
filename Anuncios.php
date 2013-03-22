<?php
require_once("verificar_sesion.php");
require_once("php/clsAnuncios.php");
include_once ("php/clsPersonaTotal.php");

$anun = new clsAnuncios();

$idUsu=$_SESSION['idUsuar'];
$TipoUsu = $_SESSION['TipoUsu'];
?>
<section id="MyAnuns">
	<h2>Mis anuncios</h2>

	<?php
    $qSqlAnu=$anun->gAnuncios($idUsu, $TipoUsu);
    while ($rSqlAnu=mysql_fetch_array($qSqlAnu)) {

    	$idAnu=$rSqlAnu['idAnu'];
    	$Person = new clsPersona();
    	$Person->DatosxUsu($rSqlAnu['RemitenteAnu']);
	?>
	<article class="listAnu">
		<div class="cuaMsgAnu">
			<a href="javascript:void(0);" class="ImgEmiAnu">
				<img src="../img/<?php echo $Person->ImgPerf(); ?>" width="48px" height="48px" />
			</a>
			<div class="headAnu">
				<header>
					<h3><a href="" class="lk"><?php echo ucfirst($Person->gNomApe()); ?></a></h3>
					<span class="Ct1">
						<span class="TipoAnu">
							<span class="tittipoA" title="Define a quienes les llega este anuncio">Tipo: </span>
							<span class="tipoA" title="<?php echo $rSqlAnu['AnuncioTipoAnun']; ?>&#10;<?php echo $rSqlAnu['DescripcionTipoAnun']; ?>"><?php echo $rSqlAnu['AnuncioTipoAnun']; ?></span>
						</span>
					</span>
					<span class="FecAnu">
						<span>
							<a href="" title="<?php echo $rSqlAnu['FechaAnu']; ?>" class="lk"><?php $date=new DateTime($rSqlAnu['FechaAnu']); echo $date->format('Y/m/d'); ?></a>
						</span>
					</span>
				</header>
				<div class="cntAnu">
					<div>
						<div class="ComentAnu"><?php echo $rSqlAnu['ComentEnvioAnu']; ?></div>
					</div>
					
				</div>
			</div>
			<div class="ieAnu"></div>
			<div class="CtAnu">
				<div class="Ct2">
					<span class="StarAnu StGr" title="Regalar estrella"></span>
					<span class="Aprob Desa" title="No puedes aprobar este anuncio."></span>
				</div>				
			</div>

		</div>
		<div class="pieAnu">
			<div class="espAnu"></div>
			<div class="Cmts" id="Cmt_<?php echo $idAnu; ?>">
				<?php
				echo $anun->gCommentsxAnun($idAnu);
				?>
			</div>

			<div class="AgrCmt" id="AgrC<?php echo $idAnu; ?>" style="display:none;">
				<a href="">
					<img src="../img/<?php echo $_SESSION['PerfilImg']; ?>" />
				</a>
				<form class="frmAgCm">
					<input type="hidden" value="" />
					<div class="ctEdiAnu">
						<div class="EdAnu">
							<textarea class="EdAnu" id="EdA<?php echo $idAnu; ?>"></textarea>
						</div>
					</div>
					<div clase="btsEdAn">
						<input type="submit" value="Comentar" class="bt1 EnvCmAn" id="evC:<?php echo $idAnu; ?>" />
						<input type="reset" value="Cancelar" id="rst:<?php echo $idAnu; ?>" class="bt2 rstCmAn" />
						<span id="loadA<?php echo $idAnu; ?>"></span>
					</div>
				<form>
			</div>
			<div class="hhAnu" id="rollA<?php echo $idAnu; ?>">
				<div class="rollbtAnu" id="rollbtA:<?php echo $idAnu; ?>">Añadir comentario</div>
			</div>
		</div>
	</article>

	<?php
	}
	?>


</section>