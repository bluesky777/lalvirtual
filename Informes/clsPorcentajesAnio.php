<?php
if (file_exists("../php/clsConexion.php"))
	include_once ("../php/clsConexion.php");
else
	include_once ("../../php/clsConexion.php");



class clsPorcentajesAnio extends clsConexion {

	public $lastPer;
	static public $lastPeriodo;
	private $countAlumnos;

	function __construct(){
		// $this->Conectar();
	}
	function gLastPeriodo($idGrupo){
		$sqlPer = "SELECT * FROM tbperiodos where Year=".$_SESSION['Year']."
				order by Periodo DESC";
				//echo $sqlPer;
		$qSqlPer = $this->queryx($sqlPer, "No se pudo traer la cantidad de alumnos del grupo ".$idGrupo.". ");
		
		while ($rSqlPer=mysql_fetch_array($qSqlPer)) {
			$PeriT = $rSqlPer['idPer'];
			$PeriodoT = $rSqlPer['Periodo'];
			
			$qSqlA=$this->gContAlumnosxNomGrupo($idGrupo, $PeriT);
			$rSqlA = mysql_fetch_array($qSqlA);
			if ($rSqlA['cuantos'] > 0 ) {
				$this->lastPer = $PeriT;
				clsPorcentajesAnio::$lastPeriodo = $PeriodoT;
				// echo "Aqui está la variable del periodo actual: " . clsPorcentajesAnio::$lastPeriodo;
				return $PeriT;	
			} 
		}
	}
	function gContAlumnosxNomGrupo($idGrupo, $Per){
		$sql="SELECT count(idAlumno) cuantos, NombreGrupo 
			from tbalumnos a, tbgrupoalumnos ga, tbgrupos g 
			where a.idAlum=ga.idAlumno and ga.idGrupo='".$idGrupo."' and g.idGrupo=ga.idGrupo 
			and idPeriodo=".$Per." and Estado=1";

		$this->countAlumnos = $this->queryx($sql, "No se pudo traer la cantidad de alumnos del grupo ".$idGrupo." en el periodo ".$Per.". ");
		return $this->countAlumnos;
	}
	function gtbPuestos($idGrupo, &$Tabla, &$tbMat, &$PromGrupo){
		// Traer las materias para los titulos
		$qSqlTitulos = $this->gAbrevMatxGrupo($idGrupo);
		while ( $reg = mysql_fetch_assoc( $qSqlTitulos) ) {
			$tbMat[]=$reg;
		}

		// Alumnos con promedio por año
		$qSqlAl = $this->gPromedioxAlum($idGrupo, $this->lastPer);
		$tbAl=array();
		while ( $reg = mysql_fetch_assoc( $qSqlAl ) ) {
			$idAlum=$reg['idAlumno'];
			$cont=$this->gNotasPerdidasxAlum($idAlum);
			$Prom=number_format($reg['PromedioAlumTotal'], 1);
			if($cont>0){
				$Prom.="(".$cont.")";
			}
			$tbAl[]=array(
				'NO' => $reg['NO'],
				'NombresAlum' => $reg['NombresAlum'],
				'ApellidosAlum' => $reg['ApellidosAlum'],
				'NombreGrupo' => $reg['NombreGrupo'],
				'idAlumno' => $idAlum,
				'PromedioAlum' => $Prom
				);
		}
		
		//Definitiva de los alumnos en cada materia
		$Cuantos = $this->countAlumnos;  //Cuantos alumnos hay
		$AcumuladoProm=0;
		foreach ($tbAl as $key => $value) {
			$PerdidosAlu=0;  //Para hallar el total de indicadores perdidos por alumno en el año
			$PromAlu=0;  //Definitiva por alumno sin quitarle decimales
			$MatsAlu=array();
			$CantMat=0;
			foreach ($tbMat as $key2 => $value2) {
				$CantMat++;
				$idAlum=$value['idAlumno'];
				$MatCod=$value2['idMaterGrupo'];

				$Pr=$this->gPromxAluxMatxAnio($MatCod, $idAlum);

				//Total de indicadores perdidos por materia
				$qSqlPerd=$this->gNotasPerdidas($MatCod, $idAlum);
				$num = mysql_num_rows( $qSqlPerd );
				$PerdidosAlu+=$num;
				$PromAlu+=$Pr;
				$Def=number_format($Pr, 0);
				if ($num>0){
					$Def.="(".$num.")";
				}
				$Tabla[$key]=$value;
				$temp=array(
					'idMaterGrupo' => $value2['idMaterGrupo'],
					'AbreviaturaMateria' => $value2['AbreviaturaMateria'],
					'Definitiva' => $Def
				);
				$MatsAlu[]=$temp;
			}
			$Tabla[$key]['Materias']=$MatsAlu;
			$Tabla[$key]['PERDIDOS']=$PerdidosAlu;
			$AcumuladoProm+=$PromAlu/$CantMat;
		}
		$PromGrupo= ($AcumuladoProm/$Cuantos);
	}
	
	function gAbrevMatxGrupo($idGrupo){
		$sql="SELECT idMaterGrupo, AbreviaturaMateria from tbmaterias m, tbmateriagrupo mg 
			where m.idMateria=mg.idMateria and mg.idGrupo='".$idGrupo."' order by OrdenMater";
		//echo $sql;
		return $this->queryx($sql, "No se trajeron las abreviaturas. ");
	}
	function gPromxAluxMatxAnio($idMateria, $idAlum){
		$sqlPer="SELECT AVG(Valores) as Promedio, MateriaGrupoCompet from
			(SELECT PeriodoCompet, sum((R2.PorcCompet/100)*R2.ValorCompetencia) Valores, MateriaGrupoCompet 
			from (SELECT PeriodoCompet, sum(r.ValorNota) ValorCompetencia, r.PorcCompet, MateriaGrupoCompet 
			from (SELECT c.PeriodoCompet, i.CompetenciaIndic,c.PorcCompet, i.idIndic,i.Indicador, 
			    MateriaGrupoCompet, AVG((i.PorcIndic/100)*n.Nota) ValorNota FROM (tbcompetencias c 
			INNER JOIN tbindicadores i ON c.idCompet=i.CompetenciaIndic) 
			INNER JOIN tbnotas n ON i.idIndic=n.idIndic 
			WHERE MateriaGrupoCompet='".$idMateria."' AND n.idAlumno=".$idAlum." 
			GROUP BY i.idIndic,i.Indicador) r 
			group by (r.CompetenciaIndic) ) R2 
			group by R2.PeriodoCompet) R3
			group by R3.MateriaGrupoCompet";

		$qSqlPer = $this->queryx($sqlPer, "No se promedio la materia anual.");
		$rSqlPer = mysql_fetch_array($qSqlPer);
		
		return $rSqlPer['Promedio'];
	}
	function gPromedioxAlum($idGrupo, $LastPeriodo, $limite=0, $PromSoloPerActivos=0){
		$PartePromedio="";
		if($PromSoloPerActivos==0){
			$PartePromedio="(SUM(r4.PromedioAlum)/".clsPorcentajesAnio::$lastPeriodo.") as PromedioAlumTotal";
		}else{
			$PartePromedio="AVG(r4.PromedioAlum) as PromedioAlumTotal";
		}
		$sql="SELECT (@rownum:=@rownum+1) AS NO,PromedioAlumTotal, idAlumno, NombresAlum, ApellidosAlum, NombreGrupo 
		FROM (select @rownum:=0) r, (SELECT $PartePromedio, r4.idAlumno, NombresAlum, ApellidosAlum, NombreGrupo
			FROM (
			SELECT PeriodoCompet, AVG(r3.DefiMateria) PromedioAlum, idAlumno, NombresAlum, ApellidosAlum, 
			NombreGrupo from ( 
			select PeriodoCompet, sum(r2.ValorCompet) DefiMateria, 
			NombreMateria, idAlumno, NombresAlum, ApellidosAlum, NombreGrupo 
			from(
			    select PeriodoCompet, sum(r.ValorNota) ValorCompet, r.CompetenciaIndic, r.PorcCompet, 
			    r.MateriaGrupoCompet, r.NombreMateria, r.idAlumno, r.NombresAlum, r.ApellidosAlum, r.NombreGrupo, 
			    r.Competencia from
			        (SELECT c.PeriodoCompet, i.CompetenciaIndic,c.PorcCompet, c.Competencia, i.idIndic,i.Indicador, 
			            MateriaGrupoCompet, m.NombreMateria, (((c.PorcCompet/100)*i.PorcIndic/100)*n.Nota) ValorNota,
			            i.PorcIndic, n.idAlumno, a.NombresAlum, a.ApellidosAlum, g.NombreGrupo 
			        FROM tbcompetencias c, tbindicadores i, tbnotas n, tbmateriagrupo mg, tbmaterias m, tbalumnos a, tbgrupoalumnos ga, tbgrupos g 
			        WHERE c.idCompet=i.CompetenciaIndic and i.idIndic=n.idIndic 
			            and mg.idGrupo='".$idGrupo."' and mg.idMaterGrupo=c.MateriaGrupoCompet and mg.idMateria=m.idMateria 
			            and a.idAlum=n.idAlumno and ga.idAlumno=a.idAlum and ga.idGrupo=g.idGrupo and g.YearGrupo='".$_SESSION['Year']."' 
			            and g.idGrupo=mg.idGrupo and ga.idPeriodo=c.PeriodoCompet and ga.Estado=1 
			        /* Aqui sale el valor de cada nota de los alumnos con respecto a la materia */ ) r 
			    GROUP BY r.CompetenciaIndic, r.idAlumno 
			    )r2 
			group by r2.MateriaGrupoCompet, r2.idAlumno, r2.PeriodoCompet
			order by NombresAlum, NombreMateria 
			/* Sale la definitiva de la materia */)r3 
			group by r3.idAlumno, r3.PeriodoCompet ) r4 inner join tbgrupoalumnos gaa 
			on gaa.idAlumno=r4.idAlumno and idPeriodo=".$LastPeriodo."
			group by r4.idAlumno
			order by PromedioAlumTotal DESC 
			/* Puestos de los alumnos en el grupo */
			)t
		";

		//echo "<br>".$sql."<br>";
		
		if($limite<>0){ $sql.=" limit 0, ".$limite; }
		return $this->queryx($sql, "No se pudo traer a los alumnos del grupo ".$idGrupo.". ");
	}


	function gNotasPerdidas($MatCod, $idAlum){
		$NotaBas=$this->gNotaBasica();
		$sql="SELECT distinct n.Nota, n.idNota, n.idAlumno,  i.idIndic, i.Indicador, c.idCompet, c.PeriodoCompet, p.Periodo
			from tbnotas n, tbindicadores i, tbcompetencias c, tbmateriagrupo mg, tbgrupoalumnos ga, tbperiodos p
			where n.idIndic=i.idIndic and i.CompetenciaIndic=c.idCompet and p.idPer=c.PeriodoCompet  
			and c.MateriaGrupoCompet=mg.idMaterGrupo and ga.idAlumno=n.idAlumno 
			and mg.idMaterGrupo=".$MatCod." and n.idAlumno=".$idAlum." and ga.Estado=1
			and n.Nota<".$NotaBas;

		return $this->queryx($sql, "No se trajeron las notas perdidas de la materia ".$MatCod.". ");
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
	function gGrupos($anio){
		$sql="SELECT idGrupo, NombreGrupo 
			from tbgrupos g 
			where YearGrupo=".$anio.";";
			
		$qSql= $this->queryx($sql, "No se trajeron las materias ");
		$Grupos=array();
		while ($rSql=mysql_fetch_assoc($qSql)) {
			$Grupos[]=$rSql;
		}
		return $Grupos;
	}
	function gNombreGrupo($idGrupo){
		$sql = "SELECT NombreGrupo FROM tbgrupos where idGrupo=".$idGrupo;
		//echo $sql;
		$qSql = $this->queryx($sql, "No se pudo traer la información del grupo ".$idGrupo.". ");
		$rSql = mysql_fetch_assoc($qSql);
		return $rSql['NombreGrupo'];
	}

}

?>
