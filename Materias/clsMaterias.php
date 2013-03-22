<?php
if (file_exists("../php/clsConexion.php"))
	include_once ("../php/clsConexion.php");
else
	include_once ("../../php/clsConexion.php");


class clsMaterias extends clsConexion {

	private $rSqlU;

	function __construct(){
		$this->Conectar();
	}

	function gMaterias($idUsu, $TipoUsu){
		$sql="";

		if ($TipoUsu == 1 or $TipoUsu == 2){
			$sql="select m.idMateria, mg.idMaterGrupo, NombreMateria, g.Grupo, g.idGrupo, g.NombreGrupo 
				from tbmateriagrupo mg, tbgrupos g, tbmaterias m 
				where mg.idProfesor='".$idUsu."' and g.idGrupo=mg.idGrupo 
				and m.idMateria=mg.idMateria and g.YearGrupo='". $_SESSION['Year']."'
				order by g.Grupo";
		}elseif($TipoUsu == 3){
			$sql="select m.idMateria, mg.idMaterGrupo, NombreMateria, g.Grupo, g.idGrupo, g.NombreGrupo,
					p.idProf, p.NombresProf, p.ApellidosProf, p.SexoProf, u.idUsu, u.LoginUsu, u.PerfilImg  
				from tbmateriagrupo mg, tbgrupos g, tbmaterias m, tbgrupoalumnos ga, tbprofesores p,
					tbusuarios u  
				where ga.idAlumno='".$idUsu."' and p.idProf=mg.idProfesor and
					u.idUsu=p.UsuarioProf
					and ga.idGrupo=g.idGrupo and ga.idPeriodo=".$_SESSION['PeriodoUsu']."
					and g.idGrupo=mg.idGrupo and m.idMateria=mg.idMateria and 
					g.YearGrupo='".$_SESSION['Year']."' order by g.Grupo";
		}

		return $this->queryx($sql, "No se trajeron las materiasdfdfdfd.");
	}

	function gComportamiento($idUsu){
		$sql="select * from tbgrupos g where g.TitularGrupo=".$idUsu." and YearGrupo=".$_SESSION['Year'];
		return $this->queryx($sql, "No se trajeron los comportamiento.");
	}

	function NotiCompet($idNMt){  // FUNCION NOTIFICACION COMPET
		
		$InfN=array('CComp'=> 0,
					'CInd' => 0, 
					'CPorInd' => 100, 
					'CPorComp'=> 0, 
					'CEstInd'=>'', 
					'CEstComp'=>'', 
					'CEstado'=>'',
					'CAus'=>0);
		
		
		$sqlNC="SELECT idCompet, PorcCompet FROM tbcompetencias 
			where PeriodoCompet=". $_SESSION['PeriodoUsu'] ."
			and  MateriaGrupoCompet=". $idNMt;
		
		$qSqlNC=$this->queryx($sqlNC, "XX Porcentajes de Competencias XX");
		
		$CantC=mysql_num_rows($qSqlNC);
		
		$InfN['CComp']=$CantC;
		
		if($CantC>0){ // SI NUM_ROWS $qSqlNC
			
			$SumC=0;
			
			$CantIndicad=0;
			
			while($rSqlNC=mysql_fetch_array($qSqlNC)){ // WHILE  $rSqlNC
				
			 $SumC+=$rSqlNC['PorcCompet'];
			 
			 $CantTemp = $this->CalcIndi($rSqlNC['idCompet'], $InfN['CEstInd']);
			 
			 $CantIndicad+=$CantTemp['Cant'];
			 
			 
			 if($CantTemp['Compl']=='Incompleto'){
				 
				$InfN['CEstInd']=$CantTemp['Compl'];
			 	$InfN['CPorInd']=$CantTemp['Porc'];
				
			 } elseif($CantTemp['Compl']=='Completo') {
				$InfN['CEstInd']=$CantTemp['Compl'];
			
			 }
				
			}  // WHILE  $rSqlNC
			
			$InfN['CInd']=$CantIndicad;
			$InfN['CPorComp']=$SumC;
			
					  
		    if ($SumC==100){
			  $InfN['CEstComp']="Completo";
		    } else{
				
				$InfN['CEstComp']="Incompleto";
			  
		    }
			
		}  // SI NUM_ROWS $qSqlNC
		
		
		$sqlNA="select sum(CantidadAus) as CantAus from tbausencias where idMaterGrupo=". $idNMt ."	and idPeriodo=".$_SESSION['PeriodoUsu'];
		
		$qSqlNA=$this->queryx($sqlNA, "XX Cantidad de ausencias XX");
		
		$rSqlNA=mysql_fetch_array($qSqlNA);
		
		$InfN['CAus']=$rSqlNA['CantAus'];
		
		
		
		
		if(($InfN['CEstComp']=="Completo") and ($InfN['CEstInd']=="Completo")){
			$InfN['CEstado']="Completo";
		} else {
			$InfN['CEstado']="Incompleto";
		}
		
		return $InfN;
		
	}  // FUNCION NOTIFICACION COMPET


	function CalcIndi($idCMat, $InfEstad){
		
		 $InfTemp=array('Porc'=>0, 'Compl'=>'', 'Cant'=>0);
		
		 $sqlNI="select idIndic, PorcIndic from tbindicadores where CompetenciaIndic=".$idCMat;
		 $qSqlNI = $this->queryx($sqlNI, "XX Porcentajes de indicadores XX");
		 
		 $InfTemp['Cant']=mysql_num_rows($qSqlNI);

				 
		 if($InfTemp['Cant']>0){  // SI NUM_ROWS $qSqlNI

		  while($rSqlNI=mysql_fetch_array($qSqlNI)){  // WHILE  $rSqlNI
			
			$InfTemp['Porc'] += $rSqlNI['PorcIndic'];
			
		  } // WHILE  $rSqlNI
		  
		  
		  if ($InfEstad=="Incompleto"){
			  $InfTemp['Compl']="IncompletoYa";
			  return $InfTemp;
			  
		  } else {
			 
			 if ($InfTemp['Porc']==100){
				$InfTemp['Compl']="Completo";
		 	 } else{
				$InfTemp['Compl']="Incompleto";
		     }
		  }
		  
		 } // SI NUM_ROWS $qSqlNI
		 
		 return $InfTemp;
				
	}



}

