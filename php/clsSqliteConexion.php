<?php
require_once('clsConexion.php');
class clsSqliteConexion extends clsConexion{

	public $con;
	private $NotaBasica;
	private $rutaFile='../../Archivos/';
 
	function CrearSqlite($nombre){
		$RutaBD=$this->rutaFile.$nombre;
		$db = sqlite_open($RutaBD) or die("No puedo abrir la base de datos ");
		return $db;
	}

	function CerrarSqlite($BD){
		unlink($BD);
	}

	function CrearTablaAlumnos($db){		
		@sqlite_exec($db, 'DROP TABLE "tbalumnos"');
		sqlite_exec($db, 'CREATE TABLE tbalumnos (ID INTEGER PRIMARY KEY, 
				Nombres, Apellidos, Documento)');
	}

	function ExportarAlumnos($db){
		$this->Conectar();

		$sqlG="SELECT idGrupo, NombreGrupo 
			from tbgrupos g 
			where YearGrupo=".$_SESSION['Year']." order by OrdenGrupo;";
		
		$qSqlG= $this->queryx($sqlG, "No se pudo traer los grupos. ");
		$tbAlum=array();
		while ($rSqlG=mysqli_fetch_assoc($qSqlG)) {
			$idGrupo=$rSqlG['idGrupo'];
			$sql="SELECT (@rownum:=@rownum+1) AS No, idAlumno, NombresAlum, ApellidosAlum 
				from (select @rownum:=0) r, tbalumnos a, tbgrupoalumnos ga 
				where a.idAlum=ga.idAlumno and ga.idGrupo=".$idGrupo." 
				and ga.idPeriodo=".$_SESSION['PeriodoUsu']." and Estado=1";
			
			$qSql= $this->queryx($sql, "No se pudo traer la cantidad de alumnos del grupo ".$idGrupo.". ");
			while ($rSqlG=mysqli_fetch_assoc($qSqlG)) {
				$tbAlum[]=$rSqlG;
			}
		}
		


	}

	function gCn(){
		return $this->con;
	}

	function gAlPer(){
		$sqlAlu="select * from tbalumnos a, tbgrupoalumnos ga 
				where a.idAlum=ga.idAlumno and ga.idPeriodo=".$_SESSION['PeriodoUsu']." 
				ORDER BY ApellidosAlum";
		$qSqlAlu=mysql_query($sqlAlu, $this->con)or die("No se pudo traer los alumnos.".mysql_error());

		return $qSqlAlu;
	}
	function BusAluxPerxProf($term){
		$qSqlAlu;

		if($_SESSION['TipoUsu'] == 1){
			$sqlAlu="select  a.idAlum as id, CONCAT(a.NombresAlum,' ',a.ApellidosAlum) as label  
				from tbalumnos a, tbgrupoalumnos ga 
				where a.idAlum=ga.idAlumno and ga.idPeriodo=".$_SESSION['PeriodoUsu']." 
				and CONCAT(a.NombresAlum, ' ', a.ApellidosAlum) like '%".$term."%' and 
				CONCAT(a.ApellidosAlum, ' ', a.NombresAlum) like '%".$term."%'
				order by label";

			$qSqlAlu=mysql_query($sqlAlu, $this->con)or die("No se pudo traer los alumnos.".mysql_error());

		} elseif ($_SESSION['TipoUsu'] == 2) {
			$sqlAlu="select distinct a.idAlum as id, CONCAT(a.NombresAlum,' ',a.ApellidosAlum) as label  
				from tbalumnos a, tbgrupoalumnos ga, tbgrupos g, tbmateriagrupo mg   
				where a.idAlum=ga.idAlumno and ga.idPeriodo=".$_SESSION['PeriodoUsu']." 
				and CONCAT(a.NombresAlum, ' ', a.ApellidosAlum) like '%".$term."%' and 
				CONCAT(a.ApellidosAlum, ' ', a.NombresAlum) like '%".$term."%' and 
				g.idGrupo=ga.idGrupo and 
				g.YearGrupo=".$_SESSION['Year']." and g.idGrupo=mg.idGrupo and mg.idProfesor=".$_SESSION['idUsuar']."
				order by label";

			$qSqlAlu=mysql_query($sqlAlu, $this->con)or die("No se pudo traer los alumnos.".mysql_error());
		}

		return $qSqlAlu;
	}
	function BusPeople($term){
		$sql="select  a.idAlum as id, CONCAT(a.NombresAlum,' ',a.ApellidosAlum) as label  
		    from tbalumnos a, tbgrupoalumnos ga, tbperiodos p
		    where a.idAlum=ga.idAlumno and ga.idPeriodo=p.idPer and p.Year=2012 
		        and CONCAT(a.NombresAlum, ' ', a.ApellidosAlum) like '%".$term."%' and 
		        CONCAT(a.ApellidosAlum, ' ', a.NombresAlum) like '%".$term."%'
		    group by a.idAlum
		    order by label;";
		    
		$qSql=mysql_query($sql, $this->con)or die("No se pudo traer los alumnos.".mysql_error());

		return $qSql;
	}

	function EdiNotaAl($nota, $idN){
		$sqlNo="Update tbnotas set Nota =".$nota." where idNota=".$idN;
		mysql_query($sqlNo, $this->con)or die("No se pudo guardar la nota. ".mysql_error());

		return true;
	}

	function gPers(){
		$sqlPer="Select * from tbperiodos where Year = ".$_SESSION['Year'];

		$qSqlPer=mysql_query($sqlPer, $this->con)or die("No se pudo traer los alumnos. ".mysql_error());
		return $qSqlPer;
	}
	function gMatsxPer($Per, $IdAlu, $Filtro){
		$sqlMat = "";

		if($Filtro == 'perdidos'){

			$sqlMat.="Select m.idMateria, m.NombreMateria, m.AliasMateria, mg.idMaterGrupo  
					 from tbgrupoalumnos ga, tbgrupos g, tbmateriagrupo mg, tbmaterias m,
					 	tbcompetencias c, tbindicadores i, tbnotas n  
					 where g.YearGrupo = ".$_SESSION['Year']." and ga.idPeriodo=".$Per." 
					 and mg.idMaterGrupo=c.MateriaGrupoCompet and c.idCompet=i.CompetenciaIndic 
					 and c.PeriodoCompet=".$Per." and i.idIndic=n.idIndic and n.idAlumno=ga.idAlumno
					 and ga.idAlumno=".$IdAlu." and ga.idGrupo=g.idGrupo and mg.idGrupo=g.idGrupo 
					 and m.idMateria=mg.idMateria and n.Nota<".$this->gNotaBasica();

			if ($_SESSION['TipoUsu']==2){
				$sqlMat.=" and mg.idProfesor=".$_SESSION['idUsuar'];
			}
			$sqlMat.=" group by m.idMateria";
			
		} else {
			$sqlMat="Select m.idMateria, m.NombreMateria, m.AliasMateria, mg.idMaterGrupo  
					 from tbgrupoalumnos ga, tbgrupos g, tbmateriagrupo mg, tbmaterias m 
					 where g.YearGrupo = ".$_SESSION['Year']." and ga.idPeriodo=".$Per." 
					 and idAlumno=".$IdAlu." and ga.idGrupo=g.idGrupo and mg.idGrupo=g.idGrupo 
					 and m.idMateria=mg.idMateria ";

			if ($_SESSION['TipoUsu']==2){
				$sqlMat.=" and idProfesor=".$_SESSION['idUsuar'];
			}
		}

		$qSqlMat=mysql_query($sqlMat, $this->con)or die("No se pudo traer las materias.".mysql_error());
		return $qSqlMat;
	}
	function gIndxMatxAlu($Per, $idMat, $IdAlu, $Filtro){
		$sqlInd="select * from tbindicadores i, tbnotas n, tbcompetencias c 
				where n.idIndic=i.idIndic and c.idCompet=i.CompetenciaIndic 
				and c.PeriodoCompet=".$Per." and n.idAlumno=".$IdAlu ." and 
				c.MateriaGrupoCompet= ".$idMat;

		if($Filtro == 'perdidos'){
			$sqlInd.=" and n.Nota<".$this->NotaBasica;
		}
		$qSqlInd=mysql_query($sqlInd, $this->con) or die ("No se trajo los indicadores del alumno.".mysql_error());
		return $qSqlInd;
	}
	function gNtBasica(){
		return $this->NotaBasica;
	}
		
	function MatesConPerdidos_BORRAR($idAlu){

		$sqlMat ="select * from tbmaterias m, tbmateriagrupo mg, tbgrupos g, tbgrupoalumnos ga  
				where m.idMateria=mg.idMateria and mg.idGrupo=g.idGrupo and g.idGrupo=ga.idGrupo 
				and g.YearGrupo=".$_SESSION['Year']." and ga.idAlumno=".$idAlu;
		if ($_SESSION['TipoUsu']==2){
			$sqlMat.=" and idProfesor=".$_SESSION['idUsuar'];
		}
		$sqlMat .= " order by ordenMater";

		$qSqlMat=mysql_query($sqlMat, $this->con) or die ("No se pudo traer las materias. ".mysql_error());

		$NtBas = $this->NotaBasica;

		$PeriodosMats=array();

		while($rSqlMat = mysqli_fetch_array($qSqlMat)){
			
			$sqlPeriodos="select idPer, Periodo, Year from tbperiodos where Year=".$_SESSION['Year'];
			
			$qSqlPeriodos=mysql_query($sqlPeriodos, $this->con)or die("No se 
				trajeron los periodos del año " .$_SESSION['Year'].". <br>" . mysql_error());
			
			while($rSqlPeriodos=mysqli_fetch_array($qSqlPeriodos)){

				$sqlMalo="select n.Nota, n.idAlumno, i.idIndic, i.Indicador, c.idCompet 
					from tbnotas n, tbindicadores i, tbcompetencias c, tbmateriagrupo mg, 
					tbgrupoalumnos ga, tbgrupos g 
					where n.idIndic=i.idIndic and i.CompetenciaIndic=c.idCompet 
					and c.MateriaGrupoCompet=mg.idMaterGrupo and ga.idAlumno=n.idAlumno 
					and mg.idMateria=".$rSqlMat['idMateria']." 
					and n.idAlumno=".$idAlu." and c.PeriodoCompet=".$rSqlPeriodos['idPer']." and n.Nota<".$NtBas." 
					and g.idGrupo=ga.idGrupo and mg.idGrupo=g.idGrupo and ga.idPeriodo=".$rSqlPeriodos['idPer'];
				if ($_SESSION['TipoUsu']==2){
					$sqlMalo.=" and idProfesor=".$_SESSION['idUsuar'];
				}
				$qSqlMalo=mysql_query($sqlMalo, $this->con)or die("No se trajeron las notas de la materia: 
					".$rSqlMat['idMateria'].". <br>".mysql_error());
				
				if(mysqli_num_rows($qSqlMalo)>0){
					$PeriodosMatsT ['idPer'] = $rSqlPeriodos['idPer'];
					$PeriodosMatsT ['Peri'] = $rSqlPeriodos['Periodo'];
					$PeriodosMatsT ['Mater'] = $rSqlMat['NombreMateria'];
					
					if ( sizeof($PeriodosMats)==0 ){
						$PeriodosMats[] = $PeriodosMatsT;
					}
					$swEstaPer=false;
					foreach ($PeriodosMats as $key => $value) {
						$indice = array_search($rSqlPeriodos['idPer'], $value);
						if ($indice) {
							$swEstaPer=true;

							$MatsInternas[] = $PeriodosMats[$key]['Mater'];

							$indice2 = in_array($PeriodosMatsT ['Mater'], $MatsInternas);

							if (!$indice2) {
								//$MaTe[] = $PeriodosMats [$key]['Mater'];
								$MaTe[] = $PeriodosMatsT ['Mater'];

								$PeriodosMats[$key]['Mater'] = $MaTe;
							}

						}
					}
					if(!$swEstaPer){
						$PeriodosMats[] = $PeriodosMatsT;
					}
					/*
					if(!in_array($PeriodosMats['idMateria'], $MateriaYa)){
						$MateriaYa[]=$rSqlMat['idMateria'];
					}
					if(!in_array($rSqlPeriodos['Periodo'], $PeriodoYa)){
						$PeriodoYa[]=$rSqlPeriodos['Periodo'];
					} 
					*/
				}
			}  /// while periodos a recorrer
		} //// while materias del alumno
		return $PeriodosMats;
	}

	function NotasPerdidasxAlu($idAlu){

		$NtBas = $this->NotaBasica;

		$SqlNtPerd = "select n.idNota, n.Nota, n.idAlumno, i.idIndic, i.Indicador, c.idCompet, c.Competencia, m.NombreMateria, p.idPer, p.Periodo  
			from tbnotas n, tbindicadores i, tbcompetencias c, tbmateriagrupo mg, 
			    tbgrupoalumnos ga, tbgrupos g, tbmaterias m, tbperiodos p 
			where n.idIndic=i.idIndic and i.CompetenciaIndic=c.idCompet 
			    and c.MateriaGrupoCompet=mg.idMaterGrupo and ga.idAlumno=n.idAlumno 
			    and mg.idMateria=m.idMateria and g.YearGrupo=".$_SESSION['Year']." 
			    and n.idAlumno=".$idAlu." and c.PeriodoCompet=p.idPer and n.Nota<".$NtBas."  
			    and g.idGrupo=ga.idGrupo and mg.idGrupo=g.idGrupo and ga.idPeriodo=p.idPer ";

		if ($_SESSION['TipoUsu']==2){
			$SqlNtPerd.=" and idProfesor=".$_SESSION['idUsuar'];
		}
		$SqlNtPerd.=" order by Periodo, mg.OrdenMater, c.OrdenCompt, i.OrdenIndic";
		
		$qSqlNtPer=mysql_query($SqlNtPerd, $this->con) or die ("No se pudo traer las notas perdidas. ".mysql_error());

		return $qSqlNtPer;
	}

	function PerConPerdidos($queryPerds, $Per){
		while ($res=mysqli_fetch_assoc($queryPerds)) {
			if ($res['Periodo']==$Per) {
				return " (notas pendientes)";
			}
		}
		return "";
	}

}


?>