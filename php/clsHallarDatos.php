<?php

include ("clsConexion.php");


class clsHallarDatos {

	function idGrxIdComp($idComp){
		$con = new clsConexion();

		$cn=$con->Conectar();

		$sql = "select idGrupo from tbcompetencias c, tbmateriagrupo mg 
			where mg.idMaterGrupo=c.MateriaGrupoCompet and c.idCompet=".$idComp;

		$qSql=$cn->query($sql) or die("No se trajo el grupo. ".mysqli_error($cn));

		if (!$qSql){
	    	echo 'Error: ' . mysqli_error($cn);
      		exit;
		}
		$rSql=mysqli_fetch_array($qSql);
		
		return $rSql["idGrupo"];

	}

}


?>