<?php

include_once ("clsConexion.php");


class clsItemUsuario extends clsConexion {

	private $RImg;

	function gImgPerf($idImg, $idUsu, $LoginUsu, $TipoUsu, $Sexo){

	    $sqlPerfil= "select NombreImg, ComentarioImg from tbimagenes where idImg = '" .$idImg."'";

	    $qSqlPerfil = $this->queryx($sqlPerfil, "No se trajo la imagen de perfil.");

	    $num_r = mysql_num_rows($qSqlPerfil);
	    
	    if($num_r>0){

	        $rSqlPerfil = mysql_fetch_array($qSqlPerfil);
	        
	        $this->CmmImgPerf = $rSqlPerfil['ComentarioImg'];

	        if ($rSqlPerfil['NombreImg'] == "" or $rSqlPerfil['NombreImg'] == null){
				$this->ImagenAlternativa($TipoUsu, $Sexo);
	        } else {
	            $this->RImg="Usuarios/".$LoginUsu."_".$idUsu."/".$rSqlPerfil['NombreImg'];
	        }

	    } else {
			$this->ImagenAlternativa($TipoUsu, $Sexo);
	    }
	    mysql_close($this->con);
	    return $this->RImg;
	}

	function ImagenAlternativa($TipoUsu, $Sexo){
		if ($TipoUsu == 1){
    		$this->RImg="usuario_admin.png";
    	}else{
	    	if($Sexo=="M"){
                $this->RImg="usuario_male.png";
            } elseif($Sexo=="F") {
                $this->RImg="usuario_female.png";
            } else{
                $this->RImg="usuario_male.png";
            }	
    	} 
    	return $this->RImg;
	}
}