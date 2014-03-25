<?php
session_name("LoginUsuario"); 
session_start();

require_once("../conexion.php");


$con=Conectar();


if(isset($_GET['PerSel'])){

	$PerSel = $_GET['PerSel'];
	$idU= $_SESSION['idUsuar'];

	$sql = "UPDATE tbusuarios SET PeriodoUsu=". $PerSel ." WHERE idUsu=". $idU;

	$qSql=mysql_query($sql, $con) or die("No se pudo cambiar el periodo Error: " . mysql_error());


	$sqlAc = "select Periodo, Year from tbperiodos where idPer=" . $PerSel;

	$qSqlAc=mysql_query($sqlAc, $con);

	while ($rSql=mysql_fetch_array($qSqlAc)) {
		$_SESSION['PeriodoUsu']=$PerSel;
		$_SESSION['Per']= $rSql['Periodo'];
		$_SESSION['Year']= $rSql['Year'];
	}

	echo "Per " . $_SESSION['Per'];

}else{
	if($_GET['YearSel']){

		$YearSel = $_GET['YearSel'];
		$idU= $_SESSION['idUsuar'];



		$sqlY = "SELECT * FROM tbperiodos WHERE Year='" .$YearSel. "' limit 1";
		$qSqlY = mysql_query($sqlY, $con) or die("No se trajo ".mysql_error());
		$rSqlY = mysql_fetch_array($qSqlY);
		
		$sqlP = "SELECT * FROM tbperiodos WHERE idPer='" .$_SESSION['PeriodoUsu']. "'";
		$qSqlP = mysql_query($sqlP, $con) or die("No se trajo ".mysql_error());
		$rSqlP = mysql_fetch_array($qSqlP);

		$PeriodoAnt = $rSqlP['Periodo'];

		// Periodo al cual lo voy a agregar	
		$sqlPn = "SELECT * FROM tbperiodos WHERE Periodo='" .$PeriodoAnt. "' AND Year='".$YearSel."'";
		$qSqlPn = mysql_query($sqlPn, $con) or die("No se trajo ".mysql_error());
		$rSqlPn = mysql_fetch_array($qSqlPn);

		$idPerNew = 0;

		if ( mysql_num_rows($qSqlPn) == 0 ){
			// Periodo al cual lo voy a agregar	
			$sqlPn = "SELECT * FROM tbperiodos WHERE Year='".$YearSel."' limit 1";
			$qSqlPn = mysql_query($sqlPn, $con) or die("No se trajo ".mysql_error());
			$rSqlPn = mysql_fetch_array($qSqlPn);

			$idPerNew = $rSqlPn['idPer'];
		}else{
			$idPerNew = $rSqlPn['idPer'];
		}
		
		$sql = "UPDATE tbusuarios SET PeriodoUsu=". $idPerNew ." WHERE idUsu=". $idU;
		
		$qSql=mysql_query($sql, $con) or die("No se pudo cambiar el periodo Error: " . mysql_error());


		$_SESSION['PeriodoUsu'] = $idPerNew;
		$_SESSION['Per']= $PeriodoAnt;
		$_SESSION['Year']= $YearSel;

		echo "Año " . $_SESSION['Year'];

		

	}
}

?>

