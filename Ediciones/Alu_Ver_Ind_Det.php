<?php
require_once("../verificar_sesion.php");
require_once("../php/clsConexion.php");
 
$Cn = new clsConexion();
$Cn->Conectar();

if(isset($_GET['term'])){

	$qSqlAlu = $Cn->BusAluxPerxProf( $_GET['term'] );

	if(mysqli_num_rows($qSqlAlu)==0){
	    echo "Sin alumnos matriculados";
	    die();
	}
	$r = array();
	while($row=mysqli_fetch_assoc($qSqlAlu)){
		$r[] = $row;
	}
	echo json_encode( $r );
	die();
}
if(isset($_GET['nota'])){

	$nota =  $_GET['nota'];
	$Resp = $Cn->EdiNotaAl( $nota ,  $_GET['idn']);

	if( $Resp ){
	    echo "Nota guardada (". $nota .")";
		die();
	}
}


$Filtro = $_GET['Flt'];
$Tp=true;

if (isset($_GET['IdAlu'])){
	$IdAlu = $_GET['IdAlu']; 
}else{	
	$IdAlu = $_SESSION['idUsuar']; 
	//Es un alumno y debo verificar si hay permisos para mostrar notas a los alumnos
	if (!$Cn->PermisoAlumnosVerNotas()){
		die("Temporalmente bloqueado, favor hablar con la linda Patty.");
	}

	// O si está a paz y salvo
	if (!$Cn->isPazYSalvo()) {
		list ($paz, $deuda) = $Cn->datosPazYSalvo();
		die('No puedes ver tus notas, no estás a paz y salvo. Debes: $' . $deuda);
	}

	$Tp=false; //Quiero indicar que No puede editar
}

	
$NtBas = $Cn->gNotaBasica();

if($Tp) {
?>
	<div class="ResFlot">Puedes editar las notas.</div>
<?php
}
?>
<input type="hidden" value="<?php echo $NtBas; ?>" id="NotaBasic" />
<?php 
$qSqlPer = $Cn->gPers();
$CantiPer=mysqli_num_rows( $qSqlPer );

if($CantiPer==0){
    echo "<div class='AnunNoPer'>Aun no hay periodos en este año <b>". $_SESSION['Year'] ."</b></div>";
    die();
}


while($rSqlPer=mysqli_fetch_array($qSqlPer)){
	
	$Per=$rSqlPer['idPer'];
	$Periodo=$rSqlPer['Periodo'];

	$qSqlMat = $Cn->gMatsxPer($Per, $IdAlu, $Filtro);
	$CantiMat=mysqli_num_rows( $qSqlMat );
	$TextMas="";
	if($CantiMat>0 and $Filtro=='perdidos'){
	    $TextMas=" (notas pendientes)";
	}

?>
	<div class="CnPer" id="CnP<?php echo $Per; ?>">
		<SPAN class="titPer Az" title="De click para ocultar o mostrar las materias." id="TiP_<?php echo $Per; ?>">PERIODO <?php echo $Periodo.$TextMas; ?></SPAN>

	<?php

		if($CantiMat==0){
		    echo "<div class='CnMt' style='display:none;'>No hay notas perdidas en este periodo</b></div>";
		}
		while($rSqlMat=mysqli_fetch_array($qSqlMat)){
		?>
		<div class="CnMt" style="display:none;">
			<div class="titMate">
				<?php echo $rSqlMat['NombreMateria']; ?>
			</div>
			<?php
			$qSqlInd = $Cn->gIndxMatxAlu($Per, $rSqlMat['idMaterGrupo'], $IdAlu, $Filtro);
			$CantiInd=mysqli_num_rows( $qSqlInd );

			if($CantiInd==0){
			    echo "<div class='AnunNoPer'>No hay indicadores para la materia <b> ". $rSqlMat['AliasMateria'].".</b></div>";

			}else{	//******************** SOLO SI HAY INDICADORES ********************

			$Cant=0;

			?>
			<div class="tbc">
				<div class="rowh">
					<div class="cell hNo">No</div>

					<?php //Solo el administradar verá el código de indicadores
				if ($_SESSION['TipoUsu']==1){
					?>
					<div class="cell hCod">Cod</div>
					<?php
				}
					?>
					<div class="cell hCod">Porc</div>
					<div class="cell hInd">Indicador</div>
					<div class="cell hCmp">Competencia</div>
					<div class="cell hNot">Nota</div>
					<?php if($Tp) { echo '<div class="cell hOpt"></div>';} ?> 
				</div>
				<?php

				while ( $rSqlInd=mysqli_fetch_array($qSqlInd)) {
				?>
				<div class="row">
					<div class="cell"><?php echo ++$Cant; ?></div>
					
					<?php //Solo el administradar verá el código de indicadores
				if ($_SESSION['TipoUsu']==1){
					?>
					<div class="cell"><?php echo $rSqlInd['idIndic']; ?></div>
					<?php
				}
					?>
					<div class="cell"><?php echo $rSqlInd['PorcIndic']; ?>%</div>
					<div class="cell" title="<?php echo $rSqlInd['Indicador']; ?>"><span class="DescrInd"><?php echo $rSqlInd['Indicador']; ?></span></div>
					<div class="cell" title="<?php echo $rSqlInd['Competencia']; ?>"><span class="DescrCmp"><?php echo $rSqlInd['Competencia']; ?></span></div>
					<?php if($Tp) { ?>
						<div class="cell"><input type="text" class="NtInd <?php if($rSqlInd['Nota']<$NtBas){ echo "perd";} ?>" value="<?php echo $rSqlInd['Nota']; ?>" id="NtIn:<?php echo $rSqlInd['idNota']; ?>" name="notIn" title="Presiona Escape para cancelar edición de la nota." /></div>
						<div class="cell">
							<?php //Solo el administradar puede borrar notas
						if ($_SESSION['TipoUsu']==1){
							?>
							<a href="../Ediciones/Alu_Ver_Ind_Det.php?DelA=<?php echo $rSqlInd['idIndic']; ?>" title="Eliminar nota para este alumno"><img src="../img/icono_eliminar.gif" width="20" height="22" /></a>
							<?php
						}
							?>
						</div>
					<?php
					}else{
					?>
						<div class="cell <?php if($rSqlInd['Nota']<$NtBas){ echo "perd";}?>" style="padding: 5px;"><?php echo $rSqlInd['Nota']; ?></div>
					<?php
					}
						
					?>

				</div>
				<?php
				} //********** Termina filas indicadores
				?>
			</div> <!-- Termina tabla ind -->

			<?php
			}	//******************** FIN SOLO SI HAY INDICADORES ******************** -->
			?>
		</div><!-- Cierra CnMt -->

	<?php
		} //********** CIERRA MATERIAS

	?>

	</div> <!-- Cierra CnPer -->

<?php
} //********** CIERRA PERIODOS

?>
