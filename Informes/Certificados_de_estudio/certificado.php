<?php

require_once("../clsCalcularPorc.php");
require_once("../../php/funciones.php");




$Calcs=new clsCalcularPorc();
$Calcs->Conectar();



// Está pidiendo los alumnos del grupo o los datos de un alumno?:
if ( isset($_GET['idGr']) ) {

	$sqlP = "SELECT * from tbperiodos where Year=" . $_GET['year'] . " LIMIT 1";
	$qSqlP = $Calcs->queryx($sqlP, "No se trajo el periodo. ");

	$periodo = mysql_fetch_assoc($qSqlP);

	$sql="SELECT idAlum, NombresAlum from tbalumnos a, tbgrupoalumnos ga 
		WHERE a.idAlum=ga.idAlumno and ga.idGrupo='".$_GET['idGr']. "' 
			and ga.idPeriodo='".$periodo['idPer']."' 
		ORDER BY ApellidosAlum;";

	$qSql=$Calcs->queryx($sql, "No se trajeron los alumnos. ");

	$datosAl = array();

	while ($rSql = mysql_fetch_assoc($qSql)) {
		$datosAl[] = $rSql;
	}
	echo json_encode( $datosAl );
	die();
}



$year = $_GET['year'];

$MiJuicio = new JuicioVal($year);

$idAlumno = $_GET['idAlum'];

$qSqlA = $Calcs->DatosAlumGrupo($idAlumno, $year);
$qSqlC = $Calcs->DatosColegio($year);

$DataAlum = mysql_fetch_assoc($qSqlA);
$DataColeg = mysql_fetch_assoc($qSqlC);


$qSqlM=$Calcs->gMaterxPerio($idAlumno, $year);

$MateriaDef=$Calcs->tbMateriaxPer($qSqlM);
//echo "<pre>";
//print_r($MateriaDef);
//echo "</pre>";

$sqlSeccion = "SELECT SeccionNivel FROM tbgrupos g 
				inner join tbnivel n on n.OrdenNivel=g.NivelGrupo
				where g.idGrupo='".$DataAlum['idGrupo']."';";
$seccionR = $Calcs->queryx($sqlSeccion, "No se trajo la sección. ");
$seccionQ = mysql_fetch_assoc($seccionR);
$seccion = "";


if ($seccionQ['SeccionNivel'] == 1) {
	$seccion = "Básica Primaria";
}else if ($seccionQ['SeccionNivel'] == 2) {
	$seccion = "Básica Secundaria";
}else if ($seccionQ['SeccionNivel'] == 3) {
	$seccion = "Media académico";
}

?>

<div class="pagina">
	<img class="encabezado-membrete" src="img/encabezado-membrete.jpg">

<br><br><br><br><br><br><br><br><br><br>

	<div class="contenido">
		<header style="font-family: Cambria,Georgia,serif; ">
			<span class="HeCol">
				<p>RECONOCIENTO OFICIAL DE ESTUDIOS N° 2563 del 12 de Agosto de 2014 Artículo 87 de la ley 115 de 1994 y el artículo 11 del Decreto 2002 de agosto 03 de 1994
				</p>
				<br><br>
				<h3 style="font-size: 12pt">LA RECTORA DEL LICEO ADVENTISTA LIBERTAD</h3>
				<br>
				<p style="font-size: 10pt; color: #000; */">CERTIFICA</p>
			</span>
			<br><br><br><br>

			<p style="width: 600px; text-align: justify">
				Que, <b><?php echo $DataAlum['ApellidosAlum']." ".$DataAlum['NombresAlum']; ?></b>, 
				cursó y aprobó en éste plantel educativo las áreas correspondientes al 
				grado <?php echo $DataAlum['NombreGrupo']; ?>  de la educación 
				<?php echo $seccion; ?> académico durante el año <?php echo $year; ?>, obteniendo los siguientes resultados:

			</p>

		</header>

		<br>

		<section style="width: 600px; ">
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
					"4" => 0
					);
				foreach ($MateriaDef as $keyM => $Mater) {
					
				?>
				<tr>
					<td class="cell"><?= ++$Cont; ?></td>
					<td class="cell">
						<span class="cMat" style="width: 250px"><?= $Mater['NombreMateria']; ?></span></td>
					<td class="cell cre"><?= $Mater['CreditosMater']; ?></td>
					<?php

					$indPer=1;
					$Sum=0; 
					$div=0;


					foreach ($Mater['Periodos'] as $keyP => $PerD) {

						?><td class="cell"><?php

							if($keyP==$indPer){ 
								$qSqlMalo=$Calcs->gNotasPerdidas($Mater['idMaterGrupo'], $idAlumno, $PerD[1], $year);
								
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

					$year = $_GET['year'];

					if ($prom< $Calcs->gNotaBasica($year)) {
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

					?>
					<td class="cell"><?php echo $Palabra; ?></td>
					<td class="cell"><?php echo $prom; ?>%</td>

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
					<td class="cell t" style="width: 250px">Total</td>
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
			<br><br>
			<div style="text-align: left">
				<?php
					$meses_ingles = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
					$meses_espanol = array("Enero", "FEB", "MAR", "ABR", "Mayo", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC");
					$mes_actual = str_ireplace($meses_ingles, $meses_espanol, strval(date('M')));
				?>
				
				Dado en TAME (Arauca) a los <?php echo date('d') ?>  Días del mes de <?php echo $mes_actual ?> de <?php echo date('Y') ?> .

			</div>


		</section>

		<footer style="text-align: left; bottom: -200px; left: 100px">

			<div class="frRec" style="text-align: left; ">
				<?php
				if (isset($_GET['Firm'])) {
					if($_GET['Firm'] == 0){
						echo "<div class='EspFr'></div>";
					}else{	
						echo '<img src="../../img/Colegio/Firma venus.jpg" alt="">';
					}
				}else{
					echo "<div class='EspFr'></div>";
				}
				
				?>
				
				<div class="fr"><b><?php echo $DataColeg["RectoraCol"]; ?></b></div>
				<div class="lic" style="padding: 2px 4px 0px 4px;"><?php if($DataColeg["SexoRectCol"]=='M'){
					echo "Rector";}else{echo "Rectora";}?></div>
			</div>

			

			<br>
			<div class="infoCol">
				

			</div>
			
		</footer>

	</div>

	<img class="pie-membrete" src="img/piepagina-membrete.jpg" style="top: 24cm;">
	
</div>
<?php
//mysql_close();
?>