<?php
class clsConexion{

	public $con;
	public static $conex;
	private $NotaBasica;
 
	function Conectar(){
		
		if ($_SERVER['HTTP_HOST']=="lalvirtual.com" or $_SERVER['HTTP_HOST']=="www.lalvirtual.com"){
			$hostname="localhost";
			$database="lalvirtu_myvc";
			$login="lalvirtu_admin";
			$pass="exalted";
		}else{
			$hostname="localhost";
			$database="lalvirtu_myvc";
			$login="root";
			$pass="123456";			
		}

		$this->con=mysql_connect($hostname, $login, $pass) or die("Problemas con la conexión al servidor");
		mysql_query("SET NAMES 'utf8'");
		mysql_select_db($database, $this->con)or die ("No se conecta a la DB");
		
		$this::$conex = $this->con;
		return $this->con;
	}

	function Cerrar(){
		@mysql_close($this->con);
	}

	function gNotaBasica(){
		$anio=$_SESSION['Year'];
		$sqlJ="Select ValorInicialJuic from tbjuiciosvalorativos where IntervaloBasicoJuic=1 and YearJuic=".$anio;
		
		$this->Conectar();
		$qSqlJ=mysql_query($sqlJ, $this->con)or die("No se calculó la nota básica del año ".$anio);
		
		$Nt=mysql_fetch_array($qSqlJ);
		$this->NotaBasica = $Nt[0];
		mysql_close($this->con);
		return $this->NotaBasica;
	}

	function queryx($sql, $msg){
		$this->Conectar();
		$qSql=mysql_query($sql, $this->con) or die($msg."<br>".mysql_error());
//echo $sql."<br><br>";
		if (!$qSql){
	    	echo 'Error: ' . mysql_error();
      		exit;
		}
		mysql_close($this->con);
		return $qSql;
	}

	function gCn(){
		return $this->con;
	}
	function gLastId(){
		/*
		$qsqlid=$this->queryx("SELECT last_insert_id();", "No se trajo el codigo.");
        $rSqlId=mysql_fetch_array($qsqlid);
        return $rSqlId[0];
        */
        return mysql_insert_id();
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

		$qSqlPer=$this->queryx($sqlPer, "No se pudo traer los alumnos. ");
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

		$qSqlMat=$this->queryx($sqlMat, "No se pudo traer las materias.");
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
		$qSqlInd=$this->queryx($sqlInd, "No se trajo los indicadores del alumno.");
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

		$qSqlMat=$this->queryx($sqlMat, "No se pudo traer las materias. ");

		$NtBas = $this->NotaBasica;

		$PeriodosMats=array();

		while($rSqlMat = mysql_fetch_array($qSqlMat)){
			
			$sqlPeriodos="select idPer, Periodo, Year from tbperiodos where Year=".$_SESSION['Year'];
			
			$qSqlPeriodos=mysql_query($sqlPeriodos, $this->con)or die("No se 
				trajeron los periodos del año " .$_SESSION['Year'].". <br>" . mysql_error());
			
			while($rSqlPeriodos=mysql_fetch_array($qSqlPeriodos)){

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
				
				if(mysql_num_rows($qSqlMalo)>0){
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
		
		$qSqlNtPer=$this->queryx($SqlNtPerd, "No se pudo traer las notas perdidas. ");

		return $qSqlNtPer;
	}

	function PermisoAlumnosVerNotas(){

		$SqlPerm = "select BloqAlumnosVerNotas  
			from tbyearcolegio where BloqAlumnosVerNotas=1";

		$qSqlPerm=$this->queryx($SqlPerm, "No se pudo verificar el permiso para los alumnos ver notas. ");
		if ($qSqlPerm > 0) {
			return false;
		}else{
			return true;
		}
		
	}

	function PerConPerdidos($queryPerds, $Per){
		while ($res=mysql_fetch_assoc($queryPerds)) {
			if ($res['Periodo']==$Per) {
				return " (notas pendientes)";
			}
		}
		return "";
	}

}


?>