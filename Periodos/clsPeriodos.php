<?php

include_once ("../php/clsConexion.php");


class clsPeriodos extends clsConexion {

	private $rSqlU;

	function __construct(){
		$this->Conectar();
	}

	function gPeriodos(){
		$sqlP="Select * from tbperiodos;";
		$qSqlP=$this->queryx($sqlP, "No se pudo traer los periodos.");
		return $qSqlP;
	}
	function gPeriodo($idPer){
		$sqlP="Select * from tbperiodos where idPer=".$idPer.";";
		$qSqlP=$this->queryx($sqlP, "No se pudo traer los periodos.");
		return mysql_fetch_array($qSqlP);
	}
	function gYear(){
		$sqlY="select * from tbyearcolegio;";
		$qSqlY=$this->queryx($sqlY, "No se pudo traer los periodos.");
		return $qSqlY;
	}
	function gPeriodoYear($idPer){
		$sqlP="Select * from tbperiodos where idPer=".$idPer;
		$qSqlP=$this->queryx($sqlP, "No se pudo traer Los periodos del año ". $idPer);
		$rSqlP=mysql_fetch_array($qSqlP);
		return $rSqlP;
	}

	function gGrupoYear(){
		$sqlGr="select idGrupo, Grupo, NombreGrupo from tbgrupos, tbyearcolegio where YearGrupo=Year";
		$qSqlGr=$this->queryx($sqlGr, "No se pudo traer los grupos.");
		return $qSqlGr;
	}

	function gAlumnos($idGrupo, $PerAnt, $Year){
		$sqlAl="select NombresAlum, ApellidosAlum 
			from tbalumnos a, tbgrupoalumnos ga, tbgrupos g, tbperiodos p
			where g.idGrupo=ga.idGrupo and ga.idAlumno=a.idAlum  and ga.Estado=1 
			and g.idGrupo=" . $idGrupo. " and ga.idPeriodo=p.idPer 
			and p.Periodo=". $PerAnt . " and p.Year=".$Year . " order by ApellidosAlum";
			
		$qSqlAl=$this->queryx($sqlAl, "No se trajeron los alumnos. ");
		return $qSqlAl;
	}
	function gCantAlum($idGrupo, $Per, $Year){
		$sqlCant="select Count(idAlum) as Cant
			from tbalumnos a, tbgrupoalumnos ga, tbgrupos g, tbperiodos p
			where g.idGrupo=ga.idGrupo and ga.idAlumno=a.idAlum  and ga.Estado=1 
			and g.idGrupo=" . $idGrupo . " and ga.idPeriodo=p.idPer 
			and p.Periodo=". $Per . " and p.Year=".$Year;
			
		$qSqlCant=$this->queryx($sqlCant, "No se contaron los alumnos.");
		$rSqlCant=mysql_fetch_array($qSqlCant);
		return $rSqlCant;
	}

	function gAlumnos2($idGrupo, $idPer){
		$sqlAl="select NombresAlum, ApellidosAlum from tbalumnos a, tbgrupoalumnos ga, tbgrupos g 
			where g.idGrupo=ga.idGrupo and ga.idAlumno=a.idAlum and ga.Estado=1  
			and g.idGrupo=" . $idGrupo . " and idPeriodo=". $idPer . " order by ApellidosAlum";
		
		$qSqlAl=$this->queryx($sqlAl, "No se trajeron los alumnos. ");
		return $qSqlAl;
	}
	function gCantAlum2($idGrupo, $idPer){
		$sqlCant="select Count(idAlum) as Cant
			from tbalumnos a, tbgrupoalumnos ga, tbgrupos g 
			where g.idGrupo=ga.idGrupo and ga.idAlumno=a.idAlum  and ga.Estado=1 
			and g.idGrupo=" . $idGrupo . " and idPeriodo=". $idPer;

		$qSqlCant=$this->queryx($sqlCant, "No se contaron los alumnos.");
		$rSqlCant=mysql_fetch_array($qSqlCant);
		return $rSqlCant;
	}

	function gGrupos(){
		$sqlGr="select idGrupo, Grupo, NombreGrupo from tbgrupos, tbyearcolegio where YearGrupo=Year";
		$qSqlGr=$this->queryx($sqlGr, "No se trajeron los grupos.");
		return $qSqlGr;
	}

}
