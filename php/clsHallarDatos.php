<?php

include ("clsConexion.php");


class clsHallarDatos {

	function idGrxIdComp($idComp){
		$con = new clsConexion();

		$cn=$con->Conectar();

		$sql = "select idGrupo from tbcompetencias c, tbmateriagrupo mg 
			where mg.idMaterGrupo=c.MateriaGrupoCompet and c.idCompet=".$idComp;

		$qSql=mysql_query($sql, $cn) or die("No se trajo el grupo. ".mysql_error());

		if (!$qSql){
	    	echo 'Error: ' . mysql_error();
      		exit;
		}
		$rSql=mysql_fetch_array($qSql);
		
		return $rSql["idGrupo"];

	}

}


?>