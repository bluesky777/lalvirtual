<?php

include_once ("../php/clsConexion.php");


class clsCompetencias extends clsConexion {

	private $rSqlU;

	function __construct(){
		$this->Conectar();
	}

	function gCompetencias($idMat){
		$sql="select c.idCompet, c.OrdenCompt, c.Competencia, c.PorcCompet, 
			mg.idMaterGrupo, FechaCreacionCompet as FecCre
			from tbmateriagrupo mg, tbcompetencias c, tbperiodos p
			where mg.idMaterGrupo=c.MateriaGrupoCompet and c.PeriodoCompet=p.idPer 
			and mg.idMaterGrupo='".$idMat."' and p.idPer=". $_SESSION['PeriodoUsu'] ." 
			order by c.OrdenCompt";

		return $this->queryx($sql, "No se trajeron las competencias.");
	}
	function gLastIdComp($Per, $Compet, $IdMat){
		$qsqlid=$this->queryx("SELECT idCompet from tbcompetencias where
				PeriodoCompet='".$Per."' and Competencia='".$Compet."' and 
				MateriaGrupoCompet='".$IdMat."' order by idCompet desc;", "No se trajo el codigo.");
        $rSqlId=mysqli_fetch_array($qsqlid);
        
        return $rSqlId[0];
	}
	function gComportamiento($idUsu){
		$sql="select * from tbgrupos g where g.TitularGrupo=".$idUsu." and YearGrupo=".$_SESSION['Year'];
		return $this->queryx($sql, "No se trajeron los comportamiento.");
	}
	function GuardarOrden($OrdenC){
		$this->Conectar();
	    for ($i = 0; $i < count($OrdenC); $i++) {
	        $orT=$i+1;
	        $sqlOrd="UPDATE `tbcompetencias` SET `OrdenCompt`=" . $orT . " WHERE `idCompet`='" . $OrdenC[$i] . "'";
	        mysql_query($sqlOrd, $this->con) or die("No se ordenó. ".$sqlOrd);
	    }
	    mysql_close($this->con);
	}
	function AgregarCompetencia($Per, $Compet, $Porc, $IdMat, $Orden){
		$this->Conectar();
	    $sql="insert into tbcompetencias(PeriodoCompet, Competencia, PorcCompet, MateriaGrupoCompet, FechaCreacionCompet, OrdenCompt) 
        	values(".$Per.", '". $Compet."', '".$Porc."', 
                '". $IdMat ."','". date(" Y/m/d",time())."', ".$Orden.")";
    
		$qsql=$this->queryx($sql, "No se pudo guardar la competencia. ");

	    if(!$qsql){
	        echo "Error al agregar";
	    } else {

	    	echo "Exitoso:".$this->gLastIdComp($Per, $Compet, $IdMat);
	    }
	}
	function ActualizarCompetencia($sql){
		$this->Conectar();
	    $qsql=$this->queryx($sql, "Lo sentimos, no se pudo actualizar. ");
	    if(!$qsql){
	            echo "Error al guardar";
	    } else {
	            echo "Edición exitosa";
	    }
	}
	function ElimCompetencia($idComp){

		$sqlSelInd="select idIndic from tbindicadores where CompetenciaIndic=".$idComp;

		$qSqlSelInd=$this->queryx($sqlSelInd, "No se trajeron los indicadores de esta competencia. ");


		while($rSqlSelInd=mysqli_fetch_array($qSqlSelInd)){
			$sqlSelN="SELECT idNota from tbnotas where idIndic=" . $rSqlSelInd['idIndic'];
			
			$qSqlSelN=$this->queryx($sqlSelN, "No se pudieron seleccionar las notas de este indicador.");
			
			while($rSqlSelN=mysqli_fetch_array($qSqlSelN)){
				
				$sqlDelN="delete from tbnotas where idNota=".$rSqlSelN['idNota'];
				
				if($qSqlDelN=$this->queryx($sqlDelN, "No se pudo eliminar la nota: ".$rSqlSelN['idNota'].". ")){
					//echo "Borrado ".$rSqlSel['idNota'];
				}else{
					echo "No se borró ".$rSqlSelN['idNota'];
				}
								
			}
			
			$sqlI="delete from tbindicadores where idIndic=" . $rSqlSelInd['idIndic'];
			
			$qI=$this->queryx($sqlI, "No se pudo eliminar indicador ".$rSqlSelInd['idIndic'].". ");
			
		}

		$sqlC="delete from tbcompetencias where idCompet=" . $_POST['idComp'];

		$qC=$this->queryx($sqlC, "No se pudo eliminar competencia ".$_POST['idComp'].". ");


		echo "Competencia eliminada satisfactoriamente. Se ha creado una copia de seguridad de los datos eliminados.";

	}

}
