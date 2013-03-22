<?php

include_once ("../php/clsConexion.php");


class clsIndicadores extends clsConexion {

	private $rSqlU;

	function __construct(){
		$this->Conectar();
	}
	function gLastIdInd($Orden, $Indicador, $IdComp){
		$qsqlid=$this->queryx("SELECT idIndic from tbindicadores where
				OrdenIndic='".$Orden."' and Indicador='".$Indicador."' and 
				CompetenciaIndic='".$IdComp."' order by idIndic desc;", "No se trajo el codigo.");
        $rSqlId=mysql_fetch_array($qsqlid);
        
        return $rSqlId[0];
	}

	function gIndicadores($idComp){
		$sql="select * from tbindicadores where CompetenciaIndic='".$idComp."' order by OrdenIndic";
		return $this->queryx($sql, "No se trajeron los indicadores.");
	}
	function AgregarInd($Orden, $Indicador, $Porcentaje, $Defec, $FechaIni, $FechaFin, $IdComp){
		$sql="";
	    if(!($FechaIni=="") and !($FechaFin=="")){
	            $sql="insert into tbindicadores(Indicador, PorcIndic, CompetenciaIndic, FechaInicioIndic, FechaFinIndic, FechaCreacionIndic, OrdenIndic, NotaPorDefecto)
	                values('". $Indicador ."', '".$Porcentaje."','".$IdComp."', '".date(" Y/m/d h:i:s",strtotime($FechaIni))."', '".date(" Y/m/d h:i:s",strtotime($FechaFin))."', 
	                '".date(" Y/m/d h:i:s",time())."', ".$Orden.", '".$Defec."' )";

	    }elseif(!($FechaIni=="")){
	            $sql="insert into tbindicadores(Indicador, PorcIndic, CompetenciaIndic, FechaInicioIndic, FechaCreacionIndic, OrdenIndic, NotaPorDefecto)
	                values('". $Indicador."', '".$Porcentaje."','".$IdComp."', '".date(" Y/m/d h:i:s",strtotime($FechaIni))."', 
	                '".date(" Y/m/d h:i:s",time())."', ".$Orden.", '".$Defec."' )";

	    }elseif(!($FechaFin=="")){
	            $sql="insert into tbindicadores(Indicador, PorcIndic, CompetenciaIndic, FechaFinIndic, FechaCreacionIndic, OrdenIndic, NotaPorDefecto)
	                values('". $Indicador."', '".$Porcentaje."','".$IdComp."', '".date(" Y/m/d h:i:s",strtotime($FechaFin))."', '".date(" Y/m/d h:i:s",time())."', ".$Orden.", '".$Defec."' )";

	    } else {
	            $sql="insert into tbindicadores(Indicador, PorcIndic, CompetenciaIndic, FechaCreacionIndic, OrdenIndic, NotaPorDefecto)
	                values('". $Indicador."', '".$Porcentaje."','".$IdComp."', '".date(" Y/m/d h:i:s",time())."', ".$Orden.", '".$Defec."' )";
	    }
	    $qsql=$this->queryx($sql, "No se agrega. ");
	    
	    if(!$qsql){
	        echo "Lo sentimos, hubo un problema al guardar.";
	    } else {

	        $idNew = $this->gLastIdInd($Orden, $Indicador, $IdComp);
	        echo "Exitoso:" . $idNew.":";
	    }
	}

	function ActualizarInd($idIndic, $Orden, $Indicador, $Porcentaje, $Defec, $FechaIni, $FechaFin){

	    $sql="update tbindicadores set Indicador='".$Indicador."', PorcIndic= '".$Porcentaje."', 
	        OrdenIndic=".$Orden. ", NotaPorDefecto=".$Defec." ";
	        

	    if(!($FechaIni=="") and !($FechaFin=="")){
	        $sql.= ", FechaInicioIndic='".date(" Y/m/d h:i:s", strtotime($FechaIni))."', FechaFinIndic='".date(" Y/m/d h:i:s",strtotime($FechaFin))."'";
	    }elseif(!($FechaIni=="")){
	        $sql.= ", FechaInicioIndic='".date(" Y/m/d h:i:s", strtotime($FechaIni))."'";
	    }elseif(!($FechaFin=="")){
	        $sql.= ", FechaFinIndic='".date(" Y/m/d h:i:s",strtotime($FechaFin))."'";
	    }

	    $sql.= " where idIndic= '".$idIndic."';";

	    $qsql=$this->queryx($sql, "Lo sentimos, no se pudo guardar los cambios. ");

	}

	function GuardarOrdenInd($OrdenI){
	    for ($i = 0; $i < count($OrdenI); $i++) {
	        $orT=$i+1;
	        $sqlOrd="UPDATE `tbindicadores` SET `OrdenIndic`=" . $orT . " WHERE `idIndic`='" . $OrdenI[$i] . "'";
	        $this->queryx($sqlOrd, "No se ordenó. ");
	    }
    	
	}

	function ElimInd($idInd){
		
		$sqlSel="SELECT idNota from tbnotas where idIndic=" . $idInd;
		$qSqlSel=$this->queryx($sqlSel, "No se pudieron seleccionar las notas de este indicador.");

		while($rSqlSel=mysql_fetch_array($qSqlSel)){
			
			$sqlDel="DELETE from tbnotas where idNota=".$rSqlSel['idNota'];
			
			if($qSqlDel=$this->queryx($sqlDel, "No se pudo eliminar la nota: ".$rSqlSel['idNota'].". ")){
				//echo "Borrado ".$rSqlSel['idNota'];
			}else{
				echo "No se borró ".$rSqlSel['idNota'];
			}
		}
		$sql="delete from tbindicadores where idIndic=" . $_POST['idInd'];

		$q=$this->queryx($sql, "Definitivamente no se pudo eliminar, comuniquese con el administrador. ");

    	
	}

}
