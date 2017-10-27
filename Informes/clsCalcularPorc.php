<?php
if (file_exists("../php/clsConexion.php"))
	include_once ("../php/clsConexion.php");
else
	include_once ("../../php/clsConexion.php");


class clsCalcularPorc extends clsConexion {

	private $Peri;

	function __construct(){
		//$this->Conectar();
	}
	function gLastPeriodo($idGrupo){
		$sqlPer = "SELECT * FROM tbperiodos where Year=".$_SESSION['Year']."
				order by Periodo DESC";
		$qSqlPer = $this->queryx($sqlPer, "No se pudo traer la cantidad de alumnos del grupo ".$idGrupo.". ");
		
		while ($rSqlPer=mysql_fetch_array($qSqlPer)) {
			$PeriT = $rSqlPer['Periodo'];
			
			$qSqlA=$this->gContAlumnosxNomGrupo($idGrupo, $PeriT);
			$rSqlA = mysql_fetch_array($qSqlA);
			if ($rSqlA['cuantos'] > 0 ) {
				$this->Peri=$PeriT;
				return $PeriT;	
			} 
		}
	}
	function gContAlumnosxNomGrupo($idGrupo, $Per){
		$sql="SELECT count(idAlumno) cuantos, NombreGrupo 
			from tbalumnos a, tbgrupoalumnos ga, tbgrupos g 
			where a.idAlum=ga.idAlumno and ga.idGrupo='".$idGrupo."' and g.idGrupo=ga.idGrupo 
			and idPeriodo=".$Per." and Estado=1";

		return $this->queryx($sql, "No se pudo traer la cantidad de alumnos del grupo ".$idGrupo." en el periodo ".$Per.". ");
	}
	function gDefAlumnosxGrupo($idGrupo){
		
		$sqlDef="SELECT idAlumno, NoMatriculaAlum, NombresAlum, ApellidosAlum, SexoAlum, UsuarioAlum,
				NombreMateria, AliasMateria, idMaterGrupo, idMateria, idProfesor, PeriodoCompet,
				CreditosMater, OrdenMater, sum( ValorCompetencia ) DefMateria
			FROM(
				SELECT ga.idALumno, a.NoMatriculaAlum, a.NombresAlum, a.ApellidosAlum, a.SexoAlum, a.UsuarioAlum,
					m.NombreMateria, m.AliasMateria, mg.idMaterGrupo, mg.idMateria, mg.idProfesor, 
					mg.CreditosMater, mg.OrdenMater, c.PeriodoCompet, c.Competencia, c.idCompet, i.Indicador, i.PorcIndic, 
					sum( ((c.PorcCompet/100)*((i.PorcIndic/100)*n.Nota)) ) ValorCompetencia
					
				FROM tbalumnos a, tbgrupoalumnos ga, tbmaterias m, tbmateriagrupo mg,
					tbcompetencias c, tbindicadores i, tbnotas n

				WHERE a.idAlum=ga.idAlumno and ga.Estado=1
					and ga.idGrupo='$idGrupo' and m.idMateria=mg.idMateria 
					and mg.idGrupo=ga.idGrupo and c.MateriaGrupoCompet=mg.idMaterGrupo
					and i.CompetenciaIndic=c.idCompet and n.idIndic=i.idIndic
					and n.idAlumno=a.idAlum and n.idAlumno=ga.idAlumno and c.PeriodoCompet=ga.idPeriodo
				group by a.idAlum, i.CompetenciaIndic
			)r group by idAlumno, idMaterGrupo, PeriodoCompet;";
//echo $sqlDef;
		$qSqlDef=$this->queryx($sqlDef, "No se pudo traer los detalles.");
		//$rSqlDef= mysql_fetch_assoc($qSqlDef) ;
		
		return $qSqlDef;
		//return $sqlDef;
	}


	function MateriasxGrupo($TablaxAlum){

		//Materias del grupo con sus datos en una tabla
		$tbMaterias=array();
		foreach ($TablaxAlum as $key => $Todos) {
			$tid=0;
			foreach ($Todos as $keyA => $Alumno) {
				
				if ($tid!=$Alumno["idMateria"]) {
					$tbMaterias[]=array(
							"idMateria"=>utf8_decode($Alumno["idMateria"]),
							"NombreMateria"=>utf8_decode($Alumno["NombreMateria"]), 
							"CreditosMater"=>utf8_decode($Alumno["CreditosMater"]), 
							"OrdenMater"=>utf8_decode($Alumno["OrdenMater"]), 
						);
					$tid=$Alumno["idMateria"];

				}
			}
			break;
		}
		return $tbMaterias;
	}

	function tbMateriaxPer($qSqlM){
		$MateriaDef=array();
		$PerD=array();
		$Pt=0;
		$i=0;
		while ($rSqlM=mysqli_fetch_assoc($qSqlM)) {


			if ($Pt==$rSqlM['idMaterGrupo']) {
				$MateriaDef[$i]["Periodos"][$rSqlM['Periodo']]=array($rSqlM['DefMateria'], $rSqlM['PeriodoCompet']);
			}else{
				$Pt=$rSqlM['idMaterGrupo'];
				$MateriaDef[++$i]=array(
					"idMaterGrupo" => $rSqlM['idMaterGrupo'],
					"NombreMateria" => $rSqlM['NombreMateria'],
					"AliasMateria" => $rSqlM['AliasMateria'],
					"CreditosMater" => $rSqlM['CreditosMater'],
					"Periodos" => array(
						$rSqlM['Periodo'] => array($rSqlM['DefMateria'], $rSqlM['PeriodoCompet'])
						)
					);
			}


/*
			if (count($MateriaDef)==0) {
				$MateriaDef[] = array(
					"idMaterGrupo" => $rSqlM['idMaterGrupo'],
					"NombreMateria" => $rSqlM['NombreMateria'],
					"AliasMateria" => $rSqlM['AliasMateria'],
					"CreditosMater" => $rSqlM['CreditosMater'],
					"Periodos" => array(
						$rSqlM['PeriodoCompet'] => $rSqlM['DefMateria']
						)
					);
			}
			$MEncontrado=false;
			foreach ($MateriaDef as $keyM => $Materia) {

				if($Materia["idMaterGrupo"] == $rSqlM["idMaterGrupo"] ){
					$MEncontrado=true;
					$PEncontrado=false;

					foreach ($Materia["Periodos"] as $keyP => $Periodos) {
						
						if ($Periodos[$keyP+1] == $rSqlM["PeriodoCompet"]) {
							$PEncontrado=true;
						}
					}
					if ($PEncontrado == false) {
						$MateriaDef[$keyM]["Periodos"][$rSqlM['PeriodoCompet']] = $rSqlM['DefMateria'];
					}
					$MEncontrado=true;
				}
			}


			if ($MEncontrado==false) {
				$MateriaDef[] = array(
					"idMaterGrupo" => $rSqlM['idMaterGrupo'],
					"NombreMateria" => $rSqlM['NombreMateria'],
					"AliasMateria" => $rSqlM['AliasMateria'],
					"CreditosMater" => $rSqlM['CreditosMater'],
					"Periodos" => array(
						$rSqlM['PeriodoCompet'] => $rSqlM['DefMateria']
						)
					);
			}
*/
		}
		/*
		echo "<pre>";
		print_r($MateriaDef);
		echo "</pre>";
		*/
		return $MateriaDef;
	}

	function DatosAlumGrupo($idAlumno, $year=0){

		if ($year==0) {
			$year = $_SESSION['Year'];
		}

		$sqlA="SELECT distinct(a.idAlum), a.NoMatriculaAlum, a.NombresAlum, a.ApellidosAlum, a.SexoAlum, 
				g.NombreGrupo, g.Grupo, g.idGrupo 
			FROM tbalumnos a, tbgrupoalumnos ga, tbgrupos g 
			WHERE a.idAlum=ga.idAlumno and g.idGrupo=ga.idGrupo 
				and g.YearGrupo=".$year." and a.idAlum=$idAlumno";

		$qSqlA=$this->queryx($sqlA, "No se pudo traer los datos del alumno ".$idAlumno);
		return $qSqlA;
	}

	function DatosColegio($year=0){
		if ($year==0) {
			$year = $_SESSION['Year'];
		}
		$SqlC="SELECT * FROM tbyearcolegio where Year=".$year;
		$qSqlC=$this->queryx($SqlC, "No se trajeron los datos del colegio.");
		return $qSqlC;
	}

	function gTotalAus($idMaterGrupo, $idAlumno){
		$SqlAus="SELECT sum(au.CantidadAus) Ausencias
			FROM tbausencias au
			WHERE au.idMaterGrupo=$idMaterGrupo and au.idAlumno=$idAlumno;";
		
		$qSqlAus=$this->queryx($SqlAus, "No se pudo traer las ausencias.");

		$rSqlAus=mysqli_fetch_assoc($qSqlAus);
		return $rSqlAus['Ausencias'];
	}

	function gTotalCred($MateriaDef){
		$Sum=0;
		foreach ($MateriaDef as $key => $value) {
			$Sum+=$value["CreditosMater"];
		}
		return $Sum;
	}

	function gMaterxPerio($idAlumno, $year=0){

		if ($year==0) {
			$year = $_SESSION['Year'];
		}

		$SqlM="SELECT idAlumno, NombreMateria, AliasMateria, idMaterGrupo, idMateria, PeriodoCompet, Periodo,
				CreditosMater, OrdenMater, sum( ValorCompetencia ) DefMateria
			FROM(
				SELECT ga.idAlumno, NombreMateria, AliasMateria, mg.idMaterGrupo, mg.idMateria, mg.idProfesor, 
					mg.CreditosMater, mg.OrdenMater, c.PeriodoCompet, c.Competencia, c.idCompet, 
					i.idIndic, i.Indicador, i.PorcIndic, p.Periodo, 
					sum( ((c.PorcCompet/100)*((i.PorcIndic/100)*n.Nota)) ) ValorCompetencia
				FROM tbgrupoalumnos ga, tbmateriagrupo mg, tbcompetencias c, tbindicadores i,
					tbnotas n, tbmaterias m, tbperiodos p 
				WHERE mg.idGrupo=ga.idGrupo and m.idMateria=mg.idMateria 
					and c.MateriaGrupoCompet=mg.idMaterGrupo and i.CompetenciaIndic=c.idCompet 
					and n.idIndic=i.idIndic	and n.idAlumno=ga.idAlumno and c.PeriodoCompet=ga.idPeriodo
					and ga.idAlumno=". $idAlumno ."  and ga.idPeriodo=p.idPer and p.Year='".$year."' 
				group by ga.idAlumno, i.CompetenciaIndic
			)r
			group by idAlumno, idMaterGrupo, PeriodoCompet
			order by OrdenMater, idMaterGrupo, PeriodoCompet";
//echo $SqlM;
		$qSqlM=$this->queryx($SqlM, "No se pudo traer las definitivas por materias de los periodos.");
		
		return $qSqlM;
	}


	function gNotasPerdidas($MatCod, $idAlum, $idPer, $year=0){

		if ($year==0) {
			$year = $_SESSION['Year'];
		}

		$NotaBas=$this->gNotaBasica($year);
		$sql="SELECT distinct n.Nota, n.idNota, n.idAlumno,  i.idIndic, i.Indicador, c.idCompet, c.PeriodoCompet
			from tbnotas n, tbindicadores i, tbcompetencias c, tbmateriagrupo mg, tbgrupoalumnos ga
			where n.idIndic=i.idIndic and i.CompetenciaIndic=c.idCompet and c.PeriodoCompet=$idPer 
			and c.MateriaGrupoCompet=mg.idMaterGrupo and ga.idAlumno=n.idAlumno 
			and mg.idMaterGrupo=".$MatCod." and n.idAlumno=".$idAlum." and ga.Estado=1
			and n.Nota < $NotaBas;";
		//echo $sql;
		$qSql=$this->queryx($sql, "No se trajeron las notas perdidas de la materia ".$MatCod.". ");
		
		return $qSql;
		
	}
	function gNotasPerdidasxAlum($idAlum){
		$sql="SELECT distinct idMaterGrupo  
			from tbmateriagrupo mg, tbgrupos g, tbgrupoalumnos ga 
			where mg.idGrupo=g.idGrupo and g.idGrupo=ga.idGrupo
				and ga.idAlumno=".$idAlum." and g.YearGrupo=".$_SESSION['Year'].";";
			
		$qSql= $this->queryx($sql, "No se trajeron las materias ");
		$Cont=0;
		while ($rSql=mysql_fetch_array($qSql)) {
			$qSqlP=$this->gNotasPerdidas($rSql['idMaterGrupo'], $idAlum);
			$Cont+=mysql_num_rows($qSqlP);
		}
		return $Cont;
	}
}

?>
