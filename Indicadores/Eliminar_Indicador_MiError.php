
<?php
require_once("conexion.php");
//require_once("verificar_sesion.php");

$con=Conectar();

if($_POST['Oper']==1){ /////////////////////////// PRIMERA VEZ QUE SE INVOCA ESTA ORDEN DE ELIMINAR ///////////////////////////

	$sql="delete from tbindicadores where idIndic=" . $_POST['idInd'];
	$q=mysql_query($sql, $con) or die(comprobando($sql));
	
	function comprobando($sqls){
	 	
		if(mysql_errno()==1451){
			echo "NotasAdentro";
		} else {
			echo "No se pudo eliminar. " . $sqls;
		}
	}
	
	echo "Competencia eliminada satisfactoriamente.";
	
	
} else if($_POST['Oper']==2){ /////////////////////////// SEGUNDA VEZ QUE SE INVOCA ESTA ORDEN  //////////////////////////////////
	$sqlSel="SELECT idNota from tbnotas where idIndic=" . $_POST['idInd'];
	$qSqlSel=mysql_query($sqlSel, $con)or die("No se pudieron seleccionar las notas de este indicador.");
	
	while($rSqlSel=mysqli_fetch_array($qSqlSel)){
		$sqlDel="delete from tbnotas where idNota=".$rSqlSel['idNota'];
		$qSqlDel=mysql_query($sqlDel, $con)or die("No se pudo eliminar la nota: ".$rSqlSel['idNota'].". ".mysql_error());
	}
	
	$sql="delete from tbindicadores where idIndic=" . $_POST['idInd'];
	$q=mysql_query($sql, $con) or die("Definitivamente no se pudo eliminar, comuniquese con el administrador. ".mysql_error());
	
	echo "Competencia eliminada satisfactoriamente.";
}
	
?>