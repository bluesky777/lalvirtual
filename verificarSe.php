<?php
require_once("conexion.php");

if (isset($_SESSION)){
	session_destroy();	
}

session_name("LoginUsuario");
session_start();

$con=Conectar();


///////////// CONSULTA A TRAVÉS DE CONTRASEÑA NO ENCRITADA /////////////////

$sqlSe = "select idUsu, TipoUsu, TipoUsuario, LoginUsu, ActivoUsu, PeriodoUsu, Periodo, Year, PerfilImg, PrincipalImg, CifradoUsu 
from tbtipousuarios, tbusuarios, tbperiodos
where LoginUsu='" . $_POST["txtLogin"] . "' and PassUsu='" . $_POST["txtPass"] . "' and tbusuarios.TipoUsu=tbtipousuarios.IdTipoUsu and PeriodoUsu=IdPer"; 


$q=$con->query($sqlSe) or die ("Consulta fallida : " . mysqli_error( $con));

if(mysqli_num_rows($q)>0){  ///////// CONTRASEÑA AUN NO ENCRIPTADA //////////////
    
    $row=mysqli_fetch_array($q);
    if ($row["ActivoUsu"]==1){
        
        EstablecerUsu($row, $con);
		
        echo "PassObli";
        

    }else{ 

        echo("Usuario " . $row['TipoUsuario'] . " desactivado, comuniquese con el administrador.</br>");

    }
            
} else {
	
	echo("Usuario o contraseña incorrecto.");

}


///////////////////// FUNCIÓN QUE DECLARE LOS DATOS DE SESSION ////////////

function EstablecerUsu($rArray, $con){
    
    $_SESSION['idUsuar']=$rArray['idUsu'];
    $_SESSION['Usuario']=$rArray['LoginUsu'];
    $_SESSION['TipoUsu']=$rArray['TipoUsu'];
    $_SESSION['PeriodoUsu']=$rArray['PeriodoUsu'];
    $_SESSION['PerfilImg']=$rArray['PerfilImg'];
    $_SESSION['PrincipalImg']=$rArray['PrincipalImg'];
    $_SESSION['Per']=$rArray['Periodo'];
    $_SESSION['Year']=$rArray['Year'];
    $_SESSION['Cifrado']=$rArray['CifradoUsu'];
    $_SESSION['Logueado']=1;
    $_SESSION['UltimoAcceso']= date("Y-n-j H:i:s");
    $_SESSION['Privilegios'][]=array();


    $sqlPerfil= "select NombreImg from tbimagenes where idImg = '" . $_SESSION['PerfilImg'] ."'";

    $qSqlPerfil =  $con->query($sqlPerfil) or die ("No se trajo la imágen de perfil. " . mysqli_error( $con));

    $num_r = mysqli_num_rows($qSqlPerfil);

    if($num_r>0){

        $rSqlPerfil = mysqli_fetch_array($qSqlPerfil);

        if ($rSqlPerfil['NombreImg'] == "" or $rSqlPerfil['NombreImg'] == null){
            $_SESSION['PerfilImg']="Indefinida";

        } else {
            $_SESSION['PerfilImg']=$rSqlPerfil['NombreImg'];

        }

    } else {
        $_SESSION['PerfilImg']="Indefinida";
    }
    
}




?>
