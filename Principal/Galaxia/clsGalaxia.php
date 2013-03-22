<?php

include_once ("../../php/clsConexion.php");
include_once ("../../Materias/clsMaterias.php");
include_once ("../../Actividades/clsActividades.php");


class clsGalaxia extends clsConexion {

	private $rSqlU;

	function __construct(){
		$this->Conectar();
	}

	function gNumActividades($idUsu, $TipoUsu){
		$Act=new clsActividades();
		$qSqlM=$Act->gActVigentesxUsu($idUsu, $TipoUsu);

		$ContNumAct= mysql_num_rows($qSqlM);
		
		mysql_close($this->con);
		return $ContNumAct;
	}

	function gxActividadesVigentes($idUsu, $TipoUsu){
		$Mat=new clsMaterias();
		$Act=new clsActividades();
		$qSqlM=$Mat->gMaterias($idUsu, $TipoUsu);

		$ContNumAct=0;
		while ($rSqlM=mysql_fetch_array($qSqlM)) {
			$qSqlA = $Act->gActVigentes($rSqlM['idMateria']);
			$numAct=mysql_num_rows($qSqlA);
			$ContNumAct += $numAct;
		}
		mysql_close($this->con);
		return $ContNumAct;
	}



}
