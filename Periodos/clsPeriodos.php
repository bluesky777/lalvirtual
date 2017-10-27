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
		//echo $sqlP;
		return mysqli_fetch_array($qSqlP);
	}
	function gPeriodoAnt($rSqlP){
		$sqlP_ant="Select * from tbperiodos where Year=".$rSqlP['Year']." order by Periodo desc;";
		$qSqlP_ant=$this->queryx($sqlP_ant, "No se pudo traer los periodos.");
		
		//echo $sqlP_ant;
		while ($rSqlP_ant = mysqli_fetch_array($qSqlP_ant)) {
			// echo "<br>Periodo estatico - >".$rSqlP['Periodo']. " Periodo fetch->".$rSqlP_ant['Periodo']."<br><br>";
			if ($rSqlP_ant['Periodo']<$rSqlP['Periodo']){
				return $rSqlP_ant['idPer'];
			}
			 
		}
	}
	function gYear(){
		$sqlY="select * from tbyearcolegio;";
		$qSqlY=$this->queryx($sqlY, "No se pudo traer los periodos.");
		return $qSqlY;
	}
	function gPeriodoYear($idPer){
		$sqlP="Select * from tbperiodos where idPer=".$idPer;
		$qSqlP=$this->queryx($sqlP, "No se pudo traer Los periodos del año ". $idPer);
		$rSqlP=mysqli_fetch_array($qSqlP);
		return $rSqlP;
	}

	function gGrupoYear($Year){
		$sqlGr="select idGrupo, Grupo, NombreGrupo 
			from tbgrupos, tbyearcolegio 
			where YearGrupo=Year and tbyearcolegio.year=".$Year;
		
		//echo $sqlGr;
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
		//echo $sqlCant;

		$qSqlCant=$this->queryx($sqlCant, "No se contaron los alumnos.");
		$rSqlCant=mysqli_fetch_array($qSqlCant);
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
		$rSqlCant=mysqli_fetch_array($qSqlCant);
		return $rSqlCant;
	}

	function gGrupos($Year){
		$sqlGr="select idGrupo, Grupo, NombreGrupo from tbgrupos, tbyearcolegio 
			where YearGrupo=Year and Year=".$Year;
		$qSqlGr=$this->queryx($sqlGr, "No se trajeron los grupos.");
		return $qSqlGr;
	}

}
