<?php
session_name("LoginUsuario"); 
session_start(); //iniciamos la sesión 

//Compruebo que el usuario está logueado 
if (!isset($_SESSION)){
	header("location: ../../index.php"); //Nos vamos al menú si ya inicio sesión.
} else { 
    //sino, calculamos el tiempo transcurrido 
    $fechaGuardada = $_SESSION["UltimoAcceso"]; 
    $ahora = date("Y-n-j H:i:s"); 
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 
	
	if($tiempo_transcurrido >= 1200) { 
     //si pasaron 10 minutos (600 seg) o más 
      session_destroy(); // destruyo la sesión 
      header("Location: ../../index.php"); //envío al usuario a la pag. de autenticación 
	}else { 
	//sino, actualizo la fecha de la sesión 
	$_SESSION["UltimoAcceso"] = $ahora; 
	} 
} 

require_once("../clsCalcularPorc.php");
require_once("../../php/funciones.php");

set_time_limit(0);



$Calcs=new clsCalcularPorc();
$Calcs->Conectar();



// Está pidiendo los alumnos del grupo o los datos de un alumno?:
if ( isset($_GET['idGr']) ) {
	$sql="SELECT idAlum, NombresAlum from tbalumnos a, tbgrupoalumnos ga 
		WHERE a.idAlum=ga.idAlumno and ga.idGrupo='".$_GET['idGr']. "' 
			and ga.idPeriodo='".$_SESSION['PeriodoUsu']."' 
		ORDER BY ApellidosAlum;";

	$qSql=$Calcs->queryx($sql, "No se trajeron los alumnos. ");

	$datosAl = array();

	while ($rSql = mysql_fetch_assoc($qSql)) {
		$datosAl[] = $rSql;
	}
	echo json_encode( $datosAl );
	die();
}




$MiJuicio = new JuicioVal($_SESSION['Year']);

$idAlumno = $_GET['idAlum'];

$qSqlA = $Calcs->DatosAlumGrupo($idAlumno);
$qSqlC = $Calcs->DatosColegio();

$DataAlum = mysql_fetch_assoc($qSqlA);
$DataColeg = mysql_fetch_assoc($qSqlC);


$qSqlM=$Calcs->gMaterxPerio($idAlumno);

$MateriaDef=$Calcs->tbMateriaxPer($qSqlM);
//echo "<pre>";
//print_r($MateriaDef);
//echo "</pre>";
?>

<div class="pagina">

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
		<table class="table">
		  <thead>
			<tr class="rowhf">
				<th class="cell hNo">NO</th>
				<th class="cell hMat">MATERIAS</th>
				<th class="cell hCre">Cred</th>
				<th class="cell hPer">Per1</th>
				<th class="cell hPer">Per2</th>
				<th class="cell hPer">Per3</th>
				<th class="cell hPer">Per4</th>
				<th class="cell hInd">Indicador</th>
				<th class="cell hDef">Definitiva</th>
				<th class="cell hAus">Aus</th>

			</tr>
		  </thead>
		  <tbody>
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
			<tr>
				<td class="cell"><?= ++$Cont; ?></td>
				<td class="cell">
					<span class="cMat"><?= $Mater['NombreMateria']; ?></span></td>
				<td class="cell cre"><?= $Mater['CreditosMater']; ?></td>
				<?php

				$indPer=1;
				$Sum=0; 
				$div=0;


				foreach ($Mater['Periodos'] as $keyP => $PerD) {

					?><td class="cell"><?php

						if($keyP==$indPer){ 
							$qSqlMalo=$Calcs->gNotasPerdidas($Mater['idMaterGrupo'], $idAlumno, $PerD[1]);
							
							$ContP=0;
							$ParenMsg="";
							
							if(mysql_num_rows($qSqlMalo)>0){
								while ($rSqlMalo=mysql_fetch_assoc($qSqlMalo)) {
									$ContP++;
									$ParenMsg.="Per".$keyP." >> ".$rSqlMalo['Indicador']." =".$rSqlMalo['Nota']." \n";
								}
							}
							$Paren="";
							if ($ContP > 0) {
								$Paren="<span class='parPerd' title='$ParenMsg'>($ContP)</span>";
								$TotIndPerd+=$ContP;
							}
							$ArrMatDef[$keyP] += $PerD[0];
							echo number_format($PerD[0], 0).$Paren;
							$Sum+=$PerD[0];
							$div++;
						}else{ 
							echo 0;
						};
						/*
						echo number_format($PerD, 0)."";
						$Sum+=$PerD;
						$div++;
						*/
					?></td>
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
				<td class="cell"><?php echo $Palabra; ?></td>
				<td class="cell"><?php echo $prom; ?>%</td>
				<td class="cell"><?php 
					if ($Aus > 0) {
						echo $Aus;
					}
					 
				?></td>

			</tr>
		  
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
		  
		  </tbody>
		

		  <tfoot class="res">
			<tr>
				<td class="cell n"></td>
				<td class="cell t">Total</td>
				<td class="cell c"><?php echo $Calcs->gTotalCred($MateriaDef); ?></td>
				<td class="cell p"><?php echo $ArrMatDef['1']; ?></td>
				<td class="cell p"><?php echo $ArrMatDef['2']; ?></td>
				<td class="cell p"><?php echo $ArrMatDef['3']; ?></td>
				<td class="cell p"><?php echo $ArrMatDef['4']; ?></td>
				<td class="cell i"><?php echo $PalabraT; ?></td>	
				<td class="cell d"><?php echo $PromT."%"; ?></td>
				<td></td>
			</tr>
		  </tfoot>

		</table>

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


		<div class="observBoletin">
			<div>Observaciones:</div>
			<div></div>
			<div></div>
		</div>

	</section>

	<footer>

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
	
</div>
<?php
//mysql_close();
?>