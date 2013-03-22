<?php
if(file_exists("../php/clsConexion.php"))
	include_once ("../php/clsConexion.php");
else
	include_once ("../../php/clsConexion.php");


class clsActividades extends clsConexion {

	private $cn;
	private $rSqlAc;



	function __construct(){
		$con = new clsConexion();
		$this->cn = $con->Conectar();
	}

	function gActividades($idMat){
		$sql = "select * from tbactividades where MateriaAct=$idMat and PeriodoAct=". $_SESSION['Per'];
		return $this->queryx($sql, "No se trajo las actividades. ");
	}
	function gActVigentesxUsu($idUsu, $TipoUsu){
		$sql = "";
		switch ($TipoUsu) {
			case 2:
				$sql = "select idAct, OrdenAct, TituloAct, MateriaAct, DescAct, TipoAct, AdjuntoAct, FechaCreacionAct
				    FechaInicioAct, FechaFinAct, OcultarAct, FechaEdicionAct, PeriodoAct
				from tbactividades a, tbmateriagrupo mg, tbgrupos g
				where mg.idMaterGrupo=a.MateriaAct and g.idGrupo=mg.idGrupo and g.YearGrupo=".$_SESSION['Year']." and
				    FechaFinAct>NOW() and FechaInicioAct<=NOW() and mg.idProfesor=".$idUsu." and
				    a.PeriodoAct=".$_SESSION['PeriodoUsu'];
				break;

			case 3:
				$sql = "select idAct, OrdenAct, TituloAct, MateriaAct, DescAct, TipoAct, AdjuntoAct, FechaCreacionAct
				    FechaInicioAct, FechaFinAct, OcultarAct, FechaEdicionAct, PeriodoAct
				from tbactividades a, tbmateriagrupo mg, tbgrupos g, tbgrupoalumnos ga  
				where mg.idMaterGrupo=a.MateriaAct and g.idGrupo=mg.idGrupo and g.YearGrupo=".$_SESSION['Year']." and
				    FechaFinAct>NOW() and FechaInicioAct<=NOW() and 
				    a.PeriodoAct=ga.idPeriodo and a.PeriodoAct=".$_SESSION['PeriodoUsu']." and ga.idAlumno=".$idUsu;
				break;
			
			default:
				# code...
				break;
		}

		$qSql=$this->queryx($sql, "No se trajo las actividades vigentes.");
		return $qSql;
	}
	function TiposActividad(){
		$sql = "select * from tbtipoactividad";

		$qSql=mysql_query($sql, $this->cn) or die("No se trajeron los tipos de actidad. ".mysql_error());

		if (!$qSql){
	    	echo 'Error: ' . mysql_error();
      		exit;
		}
		
		return $qSql;
	}

	function CrearActividad($idMat, $tipo, $Titu, $Desc, $FhIn, $FhFi, $Ocul, $Arch){
		$Desc2 = str_replace(" '", " ", $Desc);
		$Desc2 = str_replace("';", ";", $Desc2);
		$Desc2 = str_replace("',", ",", $Desc2);
		$Desc2 = str_replace("'", "`", $Desc2);
		$sqlCr ="insert into tbactividades(TituloAct, MateriaAct, DescAct, TipoAct, AdjuntoAct, FechaCreacionAct, FechaInicioAct, FechaFinAct, OcultarAct, PeriodoAct)
				values('$Titu', $idMat, '".$Desc2."', $tipo, '$Arch', '".date('Y/m/d h:i:s',time())."', '".date('Y/m/d h:i:s',strtotime($FhIn))."', '".date('Y/m/d h:i:s',strtotime($FhFi))."', '$Ocul', ".$_SESSION['PeriodoUsu'].");";
		$qSqlCr=mysql_query($sqlCr, $this->cn)or die("No se udo guardar la actividad. $sqlCr <br>".mysql_error());										

		if (!$qSqlCr){
	    	echo 'Error: ' . mysql_error();
      		exit;
		}
		return "Actividad guardada.";

	}

	function gActiv($idA){
		$sql ="select * from tbactividades where idAct=". $idA;
		$qSql=mysql_query($sql, $this->cn)or die("No selecciono la actividad. $sql <br>".mysql_error());										
		if (!$qSql){
	    	echo 'Error: ' . mysql_error();
      		exit;
		}
		$rSql=mysql_fetch_array($qSql);
		return $rSql;
	}

}
?>
