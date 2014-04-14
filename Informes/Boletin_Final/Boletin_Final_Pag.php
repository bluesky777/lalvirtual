<?php
require_once("../../verificar_sesion.php");
require_once("../clsCalcularPorc.php");
require_once("../../php/funciones.php");

set_time_limit(0);

$Calcs=new clsCalcularPorc();
$Calcs->Conectar();

$MiJuicio = new JuicioVal($_SESSION['Year']);

$idAlumno = $_GET['idAlum'];

$qSqlA = $Calcs->DatosAlumGrupo($idAlumno);
$qSqlC = $Calcs->DatosColegio();

$DataAlum = mysql_fetch_assoc($qSqlA);
$DataColeg = mysql_fetch_assoc($qSqlC);


$qSqlM=$Calcs->gMaterxPerio($idAlumno);

$MateriaDef=$Calcs->tbMateriaxPer($qSqlM);

?>
<!DOTYPE html>
<html lang="es">
<head>
	<title><?php echo $DataAlum['NombresAlum']; ?>- Boletin Final</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/Boletin_Final.css">
</head>

<body>

<br><br><br><br><br><br><br><br><br><br>
	<header>
		<span class="imgLog">
			<img src="../../img/Colegio/Logo.jpg">
		</span>
		<span class="HeCol">
			<h2><?php echo $DataColeg['NombreColegio']; ?></h2>
			<h3>RESUMEN ANUAL <?php echo $DataColeg['Year']; ?></h3>
			<p><?php echo $DataColeg['ResolucionCol']; ?></p>
		</span>

	</header>

	<br>
	<section id="Info">
		<hr>
		<div id="nom">
			<span class="tnom">NOMBRE: </span>
			<span class="nom"><?php echo $DataAlum['ApellidosAlum']." ".$DataAlum['NombresAlum']; ?></span>
		</div>
		<div id="gr">
			<span class="tgr">Grupo: </span>
			<span class="gr"><?php echo $DataAlum['NombreGrupo']; ?></span>
		</div>
	</section>

	<section>
		<div class="tbc">
			
			<div class="rowhf">
				<div class="cell hNo">NO</div>
				<div class="cell hMat">MATERIAS</div>
				<div class="cell hCre">Cred</div>
				<div class="cell hPer">Per1</div>
				<div class="cell hPer">Per2</div>
				<div class="cell hPer">Per3</div>
				<div class="cell hPer">Per4</div>
				<div class="cell hInd">Indicador</div>
				<div class="cell hDef">Definitiva</div>
				<div class="cell hAus">Aus</div>

			</div>
			<?php

			$Cont=0;
			$MatPerdi=0;
			$Lengu=0;
			$TotIndPerd=0;
			$ArrMatDef=array(
				"1" => 0,
				"2" => 0,
				"3" => 0,
				"4" => 0,
				"Aus" => 0
				);
			foreach ($MateriaDef as $keyM => $Mater) {
				
			?>
			<div class="row">
				<div class="cell"><?= ++$Cont; ?></div>
				<div class="cell">
					<span class="cMat"><?= $Mater['NombreMateria']; ?></span></div>
					<div class="cell cre"><?= $Mater['CreditosMater']; ?></div>
				<?php

				$indPer=1;
				$Sum=0; 
				$div=0;

				foreach ($Mater['Periodos'] as $keyP => $PerD) {
					
					?><div class="cell"><?php
						
						if($keyP==$indPer){ 
							$qSqlMalo=$Calcs->gNotasPerdidas($Mater['idMaterGrupo'], $idAlumno, $keyP);
							
							$ContP=0;
							$ParenMsg="";
							
							if(mysql_num_rows($qSqlMalo)>0){
								while ($rSqlMalo=mysql_fetch_assoc($qSqlMalo)) {
									$ContP++;
									$ParenMsg.="Per".$rSqlMalo['PeriodoCompet']."-".$rSqlMalo['Indicador']." =".$rSqlMalo['Nota']." \n";
								}
							}
							$Paren="";
							if ($ContP > 0) {
								$Paren="<span class='parPerd' title='$ParenMsg'>($ContP)</span>";
								$TotIndPerd+=$ContP;
							}
							$ArrMatDef[$keyP] += $PerD;
							echo number_format($PerD, 0).$Paren;
							$Sum+=$PerD;
							$div++;
						}else{ 
							echo 0;
						};
						/*
						echo number_format($PerD, 0)."";
						$Sum+=$PerD;
						$div++;
						*/
					?></div>
					<?php
					$indPer++;
				}
				
				//echo "Suma: ".$Sum."<--";
				$prom = 0;

				if ($Sum != 0) {
					$prom = $Sum / $div;
				}
				$prom = number_format( $prom , 0 );

				if ($prom< $Calcs->gNotaBasica()) {
					if ($Mater['NombreMateria']=="LENGUA CASTELLANA" || $Mater['NombreMateria']=="INGLÉS") {
						$Lengu++;
						if($Lengu==2){
							$MatPerdi++;
						}
					}else{
						$MatPerdi++;
					}
				}

				$Palabra=$MiJuicio->Palabra(number_format($prom));

				$Aus= $Calcs->gTotalAus($Mater['idMaterGrupo'], $DataAlum['idAlum']);

				?>
				<div class="cell"><?php echo $Palabra; ?></div>
				<div class="cell"><?php echo $prom; ?>%</div>
				<div class="cell"><?php 
					if ($Aus > 0) {
						echo $Aus;
					}
					 
				?></div>

			</div>

			<?php
			}

			$CantM=count($MateriaDef);

			$ArrMatDef['1']=$ArrMatDef['1']/$CantM;
			$ArrMatDef['2']=$ArrMatDef['2']/$CantM;
			$ArrMatDef['3']=$ArrMatDef['3']/$CantM;
			$ArrMatDef['4']=$ArrMatDef['4']/$CantM;


			$PromT=($ArrMatDef['1']+$ArrMatDef['2']+$ArrMatDef['3']+$ArrMatDef['4']) / 4;
			
			$PalabraT=$MiJuicio->Palabra(number_format($PromT));
			$PromT=number_format($PromT, 1);
			

			$ArrMatDef['1']=number_format($ArrMatDef['1']);
			$ArrMatDef['2']=number_format($ArrMatDef['2']);
			$ArrMatDef['3']=number_format($ArrMatDef['3']);
			$ArrMatDef['4']=number_format($ArrMatDef['4']);
			?>

		</div>

		<div class="tbr res">
				<div class="row">
					<div class="cell n">*</div>
					<div class="cell t">Total</div>
					<div class="cell c"><?php echo $Calcs->gTotalCred($MateriaDef); ?></div>
					<div class="cell p"><?php echo $ArrMatDef['1']; ?></div>
					<div class="cell p"><?php echo $ArrMatDef['2']; ?></div>
					<div class="cell p"><?php echo $ArrMatDef['3']; ?></div>
					<div class="cell p"><?php echo $ArrMatDef['4']; ?></div>
					<div class="cell i"><?php echo $PalabraT; ?></div>	
					<div class="cell d"><?php echo $PromT."%"; 
					?></div>
				</div>
			</div>

		<div class="diag">
			<?php
			//Si tiene mas de dos materias pendientes, pierde el año
			switch ($MatPerdi) {
				case 1:
					if ($DataAlum["SexoAlum"]=="M") {
						echo "El ";
					}else{
						echo "La ";
					}
					echo "estudiante tiene promoción pendiente con $TotIndPerd indicadores por nivelar.";
					break;


				case 0:
					if ($DataAlum["SexoAlum"]=="M") {
						echo "El estudiante ha sido promovido.";
					}else{
						echo "La estudiante ha sido promovida.";
					}

					break;

				default:
					if ($DataAlum["SexoAlum"]=="M") {
						echo "El estudiante no fue promovido.";
					}else{
						echo "La estudiante no fue promovida.";
					}
			}
			

			?>
		</div>
	</section>

	<footer>

		<div class="observBoletin">
			<div>Observaciones:</div>
			<div></div>
			<div></div>
		</div>

		<div class="frRec">
			<?php
			if($_GET['Firm'] == 0){
				echo "<div class='EspFr'></div>";
			}else{
				echo '<img src="../../img/Colegio/Firma venus.jpg" alt="">';
			}
			?>
			<div class="fr"><?php echo $DataColeg["RectoraCol"]; ?></div>
			<div class="lic"><?php if($DataColeg["SexoRectCol"]=='M'){
				echo "Rector";}else{echo "Rectora";}?></div>
		</div>

		
		<div class="frSec">
			<?php
			if($_GET['Firm'] == 0){
				echo "<div class='EspFr'></div>";
			}else{
				echo '<img src="../../img/Colegio/Firma Patricia.jpg" alt="">';
			}
			?>
			<div class="fr"><?php echo $DataColeg["SecretarioCol"]; ?></div>
			<div class="lic"><?php if($DataColeg["SexoSecCol"]=='M'){
				echo "Secretario";}else{echo "Secretaria";}?></div>
		</div>

		<br>
		<div class="infoCol">
			<?php
			if($DataColeg['SitioWebCol'] != ""){
				echo '<span class="urlPagCol">Página web: <a href="'.$DataColeg["SitioWebCol"].'">'.$DataColeg['SitioWebCol'].'</a></span>';
			}
			if($DataColeg['SitioWebMyVc'] != ""){
				echo '<span class="urlMyVC">Campus virtual: <a href="'.$DataColeg["SitioWebMyVc"].'">'.$DataColeg["SitioWebMyVc"].'</a></span>';
			}
			if($DataColeg['TelefonoCol'] != ""){
				echo '<span class="Telef"></span>Telefono: '.$DataColeg["TelefonoCol"].'</a></span>';
			}

			?>
			
			

		</div>
		
	</footer>
</body>
</html>
<?php
//mysql_close();
?>