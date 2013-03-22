<?php
if (file_exists("../php/clsConexion.php"))
	include_once ("../php/clsConexion.php");
else
	include_once ("../../php/clsConexion.php");


class clsAlumnos extends clsConexion {

	function __construct(){
		//$this->Conectar();
	}

	function gAlumxPer($idPer){
		
		$sqlAlu="SELECT idAlum, NombresAlum, ApellidosAlum 
			from tbalumnos a, tbgrupoalumnos ga 
			where a.idAlum=ga.idAlumno and ga.idPeriodo=".$idPer." 
			ORDER BY ApellidosAlum;";

		$qSqlAlu=$this->queryx($sqlAlu, "No se pudo traer los alumnos.");
		return $qSqlAlu;
	}

	
	function gGrupos($idYear){
		$sqlGr="SELECT * from tbgrupos where YearGrupo='".$idYear."'";
		$qSqlGr=$this->queryx($sqlGr, "No se pudieron traer los grupos.");
		
		return $qSqlGr;
	}




}