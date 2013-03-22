<?php

include_once ("clsConexion.php");


class clsPersona extends clsConexion {

	private $cn;

	private $rSqlU;
	private $RImg;
	private $CmmImgPerf;
	private $CmmImgPrin;
	private $Sexo;
	private $Nombre;
	private $Apellido;
	private $Login;
	private $iTiU;
	private $TiU;
	private $ActivoU;



	function __construct(){

	}


	function DatosxUsu($idUsu){
		$con = new clsConexion();
		$this->cn = $con->Conectar(); 

		$sql = "select * from tbusuarios u, tbtipousuarios tu where u.TipoUsu=tu.idTipoUsu and u.idUsu=".$idUsu;

		$qSql=mysql_query($sql, $this->cn) or die("No se trajo el usuario. ".mysql_error());

		if (!$qSql){
	    	echo 'Error: ' . mysql_error();
      		exit;
		}
		$rSql=mysql_fetch_array($qSql);
		$rSqlT;
		switch ($rSql['TipoUsu']) {
			case 1:
				$this->rSqlU = $rSql;
				$this->Login = $rSql['LoginUsu'];
				$this->iTiU = $rSql['TipoUsu'];
				$this->TiU = $rSql['TipoUsuario'];
				return $this->rSqlU;
				break;

			case 2 or 5:
				$sqlT = "select * from tbprofesores p, tbusuarios u 
						where UsuarioProf=idUsu and idUsu=".$idUsu;
				$qSqlT=mysql_query($sqlT, $this->cn) or die("No se trajo los datos del profesor. ".mysql_error());
				$rSqlT=mysql_fetch_array($qSqlT);
				$this->Sexo = $rSqlT['SexoProf'];
				$this->Nombre = $rSqlT['NombresProf'];
				$this->Apellido = $rSqlT['ApellidosProf'];
				break;

			case 3:
				$sqlT = "select * from tbalumnos p, tbusuarios u 
						where UsuarioAlum=idUsu and idUsu=".$idUsu;
				$qSqlT=mysql_query($sqlT, $cn) or die("No se trajo los datos del alumno. ".mysql_error());
				$rSqlT=mysql_fetch_array($qSqlT);
				$this->Sexo = $rSqlT['SexoAlum'];
				$this->Nombre = $rSqlT['NombreProf'];
				$this->Apellido = $rSqlT['ApellidosProf'];
				break;
			
			case 4:
				$sqlT = "select * from tbacudientes p, tbusuarios u 
						where UsuarioAcud=idUsu and idUsu=".$idUsu;
				$qSqlT=mysql_query($sqlT, $cn) or die("No se trajo los datos del acudiente. ".mysql_error());
				$rSqlT=mysql_fetch_array($qSqlT);
				$this->Sexo = $rSqlT['SexoAcud'];
				$this->Nombre = $rSqlT['NombresProf'];
				$this->Apellido = $rSqlT['ApellidosProf'];
				break;
		}
		$this->Login = $rSql['LoginUsu'];
		$this->iTiU = $rSql['TipoUsu'];
		$this->TiU = $rSql['TipoUsuario'];
		$this->ActivoU = $rSql['ActivoUsu'];
		
		$this->ActivoU = $rSql['ActivoUsu'];



		$this->rSqlU = $rSqlT;
		return $this->rSqlU;
	}

	function ImgPrinc(){

	    $sqlPrin= "select NombreImg from tbimagenes where idImg = '" . $this->rSqlU['PrincipalImg'] ."'";

	    $qSqlPrin = mysql_query($sqlPrin, $this->cn) or die ("No se trajo la imágen de perfil. " . mysql_error());

	    $num_r = mysql_num_rows($qSqlPrin);
	    
	    if($num_r>0){

	        $rSqlPrin = mysql_fetch_array($qSqlPrin);
	        
	        $this->CmmImgPerf = $rSql['ComentarioImg'];

	        if ($rSqlPrin['NombreImg'] == "" or $rSqlPrin['NombreImg'] == null){
	            
		    	if ($this->iTiU == 1){
		    		$this->RImg="usuario_admin.png";
		    	}else{
			    	if($this->Sexo=="M"){
		                $this->RImg="usuario_male.png";
		            } elseif($this->Sexo=="F") {
		                $this->RImg="usuario_female.png";
		            } else{
		                $this->RImg="usuario_male.png";
		            }	
		    	}     

	        } else {
	            $this->RImg="Usuarios/".$this->rSqlU['LoginUsu']."_".$this->rSqlU['idUsu']."/".$rSqlPrin['NombreImg'];

	        }

	    } else {
	    	if ($this->iTiU == 1){
	    		$this->RImg="usuario_admin.png";
	    	}else{
		    	if($this->Sexo=="M"){
	                $this->RImg="usuario_male.png";
	            } elseif($this->Sexo=="F") {
	                $this->RImg="usuario_female.png";
	            } else{
	                $this->RImg="usuario_male.png";
	            }	
	    	}
            
	    }
	    return $this->RImg;
	}

	function ImgPerf(){

	    $sqlPerfil= "select NombreImg, ComentarioImg from tbimagenes where idImg = '" . $this->rSqlU['PerfilImg'] ."'";

	    $qSqlPerfil = mysql_query($sqlPerfil, $this->cn) or die ("No se trajo la imágen de perfil. " . mysql_error());

	    $num_r = mysql_num_rows($qSqlPerfil);
	    
	    if($num_r>0){

	        $rSqlPerfil = mysql_fetch_array($qSqlPerfil);
	        
	        $this->CmmImgPerf = $rSqlPerfil['ComentarioImg'];

	        if ($rSqlPerfil['NombreImg'] == "" or $rSqlPerfil['NombreImg'] == null){
	            
		    	if ($this->iTiU == 1){
		    		$this->RImg="usuario_admin.png";
		    	}else{
			    	if($this->Sexo=="M"){
		                $this->RImg="usuario_male.png";
		            } elseif($this->Sexo=="F") {
		                $this->RImg="usuario_female.png";
		            } else{
		                $this->RImg="usuario_male.png";
		            }	
		    	}     

	        } else {
	            $this->RImg="Usuarios/".$this->rSqlU['LoginUsu']."_".$this->rSqlU['idUsu']."/".$rSqlPerfil['NombreImg'];

	        }

	    } else {
	    	if ($this->iTiU == 1){
	    		$this->RImg="usuario_admin.png";
	    	}else{
		    	if($this->Sexo=="M"){
	                $this->RImg="usuario_male.png";
	            } elseif($this->Sexo=="F") {
	                $this->RImg="usuario_female.png";
	            } else{
	                $this->RImg="usuario_male.png";
	            }	
	    	}
            
	    }
	    return $this->RImg;
	}
	function gCommImgPerf(){
		return $this->CmmImgPerf;
	}
	function gCommImgPrin(){
		return $this->CmmImgPrin;
	}
	function CerrarCon(){
		//Cómo lo cierro??
	}

	function gLogin(){
		return $this->Login;
	}
	function gNom(){
		if ($this->Nombre == ""){
			return $this->Login;
		}else{
			return $this->Nombre;
		}		
	}
	function gApe(){
		if ($this->Nombre == ""){
			return $this->Login;
		}else{
			return $this->Apellido;
		}	
	}
	function gNomApe(){
		if ($this->Nombre == ""){
			return $this->Login;
		}else{
			return $this->Nombre . " " . $this->Apellido;
		}
	}
	function gSexo(){
		return $this->Sexo;
	}
	function gActU(){
		return $this->ActivoU;
	}
	function giTiU(){
		return $this->ActivoU;
	}


	function PromedioTotal($idAlum, $idPer){

		$con = new clsConexion();
		$this->cn = $con->Conectar(); 

		$sqlPr="select idAlumno, NombresAlum, avg(ValorCompetencias) Puntaje from (
				select r4.idAlumno, r4.NombresAlum, sum(r4.SumaIndicadores)  ValorCompetencias, r4.MateriaGrupoCompet, r4.NombreMateria from (
					select r3.idAlumno, r3.NombresAlum, r3.Competencia, ((r3.PorcCompet/100) * sum(r3.ValorIndic)) SumaIndicadores, r3.PorcCompet, r3.idCompet, r3.MateriaGrupoCompet, r3.NombreMateria
					from (
						select n.idAlumno, a.NombresAlum, ((i.PorcIndic/100)*n.Nota) ValorIndic, i.idIndic, 
							c.PorcCompet, c.idCompet, c.MateriaGrupoCompet, c.Competencia, m.NombreMateria
						from tbcompetencias c inner join tbmateriagrupo mg inner join tbmaterias m 
						inner join tbindicadores i
						inner join tbnotas n
						inner join tbalumnos a
						on 
						m.idMateria=mg.idMateria and c.MateriaGrupoCompet=mg.idMaterGrupo and 
						i.CompetenciaIndic=c.idCompet and
						n.idIndic=i.idIndic and a.idAlum=n.idAlumno and
						
						c.PeriodoCompet=".$idPer." and
						a.idAlum=".$idAlum."
						
						group by i.idIndic
					)r3	group by r3.idCompet
				)r4 group by r4.MateriaGrupoCompet
			)r";

		$qSqlPr=mysql_query($sqlPr, $this->cn)or die("No se pudo el promedio del alumno. ".mysql_error());

		$rSqlPr=mysql_fetch_array($qSqlPr);

		return $rSqlPr['Puntaje'];	
	}
}

?>