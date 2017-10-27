<?php
if (file_exists("../php/clsConexion.php"))
	include_once ("../php/clsConexion.php");
else
	include_once ("../../php/clsConexion.php");


class clsPorcentajes extends clsConexion {

	function __construct(){
		//$this->Conectar();
	}

	function gContAlumnosxNomGrupo($idGrupo){
		$sql="SELECT count(idAlumno) cuantos, NombreGrupo 
			from tbalumnos a, tbgrupoalumnos ga, tbgrupos g 
			where a.idAlum=ga.idAlumno and ga.idGrupo='".$idGrupo."' and g.idGrupo=ga.idGrupo 
			and idPeriodo=".$_SESSION['PeriodoUsu']." and Estado=1";
		
		return $this->queryx($sql, "No se pudo traer la cantidad de alumnos del grupo ".$idGrupo.". ");
	}
	function gtbPuestos($idGrupo, &$Tabla, &$tbMat){
		//Traer las materias para los titulos
		$qSqlTitulos = $this->gAbrevMatxGrupo($idGrupo);
		while ( $reg = mysqli_fetch_assoc( $qSqlTitulos) ) {
			$tbMat[]=$reg;
		}

		//Alumnos con promedio por periodo
		$qSqlAl = $this->gPromedioxAlum($idGrupo);
		$tbAl=array();
		while ( $reg = mysqli_fetch_assoc( $qSqlAl ) ) {
			$idAlum=$reg['idAlumno'];
			$cont=$this->gNotasPerdidasxAlum($idAlum);
			$Prom=number_format($reg['PromedioAlum'], 1);
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
		foreach ($tbAl as $key => $value) {
			$MatsAlu=array();
			foreach ($tbMat as $key2 => $value2) {
				$idAlum=$value['idAlumno'];
				$MatCod=$value2['idMaterGrupo'];

				$qSqlD=$this->gDefinitivaAlum($MatCod, $idAlum);
				$rSqlD=mysqli_fetch_assoc($qSqlD);

				//Total de indicadores perdidos por materia
				$qSqlPerd=$this->gNotasPerdidas($MatCod, $idAlum);
				$num = mysqli_num_rows( $qSqlPerd );

				$Def=number_format($rSqlD['Valores'], 0);
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
		}
	}
	
	function gAbrevMatxGrupo($idGrupo){
		$sql="SELECT idMaterGrupo, AbreviaturaMateria from tbmaterias m, tbmateriagrupo mg 
			where m.idMateria=mg.idMateria and mg.idGrupo='".$idGrupo."' order by OrdenMater";
			
		return $this->queryx($sql, "No se trajeron las abreviaturas. ");
	}
	function gPromedioxAlum($idGrupo, $limite=0){
		$sql="SELECT (@rownum:=@rownum+1) AS NO, PromedioAlum, idAlumno, NombresAlum, ApellidosAlum, NombreGrupo 
		FROM (select @rownum:=0) r, (
		  SELECT AVG(r3.DefiMateria) PromedioAlum, idAlumno, NombresAlum, ApellidosAlum, MateriaGrupoCompet, NombreGrupo
			from  (

			 select sum(r2.ValorCompet) DefiMateria, MateriaGrupoCompet, 
			    NombreMateria, idAlumno, NombresAlum, ApellidosAlum, NombreGrupo 
			 from(
			  select sum(r.ValorNota) ValorCompet, r.CompetenciaIndic, r.PorcCompet, r.MateriaGrupoCompet, r.NombreMateria, 
			    r.idAlumno, r.NombresAlum, r.ApellidosAlum, r.NombreGrupo, r.Competencia 
			  from
					(SELECT i.CompetenciaIndic,c.PorcCompet, c.Competencia, i.idIndic,i.Indicador, MateriaGrupoCompet, m.NombreMateria,
			        (((c.PorcCompet/100)*i.PorcIndic/100)*n.Nota) ValorNota,i.PorcIndic, n.idAlumno, a.NombresAlum, a.ApellidosAlum,
			        g.NombreGrupo
					FROM tbcompetencias c, tbindicadores i, tbnotas n, tbmateriagrupo mg,  tbmaterias m, 
			        tbalumnos a, tbgrupoalumnos ga, tbgrupos g  
			                
					WHERE c.PeriodoCompet='". $_SESSION['PeriodoUsu'] ."' AND c.idCompet=i.CompetenciaIndic 
			        and i.idIndic=n.idIndic and mg.idGrupo='". $idGrupo ."' 
					and mg.idMaterGrupo=c.MateriaGrupoCompet 
			        and mg.idMateria=m.idMateria and a.idAlum=n.idAlumno and ga.idAlumno=a.idAlum 
			        and ga.idGrupo=g.idGrupo and g.YearGrupo='". $_SESSION['Year'] ."' and g.idGrupo=mg.idGrupo 
					and ga.idPeriodo=c.PeriodoCompet and ga.Estado=1
			        /* Aqui sale el valor de cada nota de los alumnos  con respecto a la materia */
			        
					) r 
			  GROUP BY r.CompetenciaIndic, r.idAlumno
			  /* Este es el valor de cada competencia de los alumnos con respecto a la materia */
			  
			 )r2 
			 group by r2.MateriaGrupoCompet, r2.idAlumno
			 order by NombresAlum, NombreMateria
			 /* Sale la definitiva de la materia */    
			    
			)r3
			group by r3.idAlumno 
			order by PromedioAlum DESC
			/* Puestos de los alumnos en el grupo */
		  ) t
			";
		if($limite <> 0){ $sql.= " limit 0, $limite"; }
		
		return $this->queryx($sql, "No se pudo traer a los alumnos del grupo ".$idGrupo.". ");
	}
	function gDefinitivaAlum($MatCod, $idAlum){
		$sql="SELECT sum((R2.PorcCompet/100)*R2.ValorCompetencia) Valores, MateriaGrupoCompet
		  from
			(SELECT sum(r.ValorNota) ValorCompetencia, r.PorcCompet, MateriaGrupoCompet from
				(SELECT i.CompetenciaIndic,c.PorcCompet, i.idIndic,i.Indicador, 
				AVG((i.PorcIndic/100)*n.Nota) ValorNota, MateriaGrupoCompet
				FROM (tbcompetencias c 
					INNER JOIN tbindicadores i 
					ON c.idCompet=i.CompetenciaIndic)
						INNER JOIN tbnotas n 
						ON  i.idIndic=n.idIndic
				WHERE c.PeriodoCompet=".$_SESSION['PeriodoUsu']."
					 AND MateriaGrupoCompet='".$MatCod."' 
					 AND n.idAlumno=".$idAlum."
				GROUP BY i.idIndic,i.Indicador) r
			group by (r.CompetenciaIndic) ) R2
			group by R2.MateriaGrupoCompet";
			
		return $this->queryx($sql, "No se calcularon las competencias de la materia: ".$MatCod.". ");
	}

	function gNotasPerdidas($MatCod, $idAlum){
		$NotaBas=$this->gNotaBasica();
		$sql="SELECT n.Nota, n.idAlumno, i.idIndic, i.Indicador, c.idCompet 
			from tbnotas n, tbindicadores i, tbcompetencias c, tbmateriagrupo mg, 
				tbgrupoalumnos ga, tbalumnos a 
			where n.idIndic=i.idIndic and i.CompetenciaIndic=c.idCompet 
				and c.MateriaGrupoCompet=mg.idMaterGrupo and a.idAlum=ga.idAlumno and a.idAlum=n.idAlumno 
				and mg.idMaterGrupo=".$MatCod." 
				and n.idAlumno=".$idAlum." and ga.Estado=1 
				and c.PeriodoCompet=".$_SESSION['PeriodoUsu']." 
				and n.Nota<".$NotaBas." and ga.idPeriodo=".$_SESSION['PeriodoUsu'];
			
		return $this->queryx($sql, "No se trajeron las notas perdidas de la materia ".$MatCod.". ");
	}
	function gNotasPerdidasxAlum($idAlum){
		$sql="SELECT distinct idMaterGrupo  
			from tbmateriagrupo mg, tbgrupos g, tbgrupoalumnos ga 
			where mg.idGrupo=g.idGrupo and g.idGrupo=ga.idGrupo
				and ga.idAlumno=".$idAlum." and g.YearGrupo=".$_SESSION['Year'].";";
			
		$qSql= $this->queryx($sql, "No se trajeron las materias ");
		$Cont=0;
		while ($rSql=mysqli_fetch_array($qSql)) {
			$qSqlP=$this->gNotasPerdidas($rSql['idMaterGrupo'], $idAlum);
			$Cont+=mysqli_num_rows($qSqlP);
		}
		return $Cont;
	}


}