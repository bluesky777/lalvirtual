<?
session_name("LoginUsuario"); 
session_start(); //iniciamos la sesión 

//Compruebo que el usuario está logueado 
if (!isset($_SESSION)){
	header("location: ../index.php"); //Nos vamos al menú si ya inicio sesión.
} else { 
    //sino, calculamos el tiempo transcurrido 
    $fechaGuardada = $_SESSION["UltimoAcceso"]; 
    $ahora = date("Y-n-j H:i:s"); 
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 
	
	if($tiempo_transcurrido >= 1200) { 
     //si pasaron 10 minutos (600 seg) o más 
      session_destroy(); // destruyo la sesión 
      header("Location: ../index.php"); //envío al usuario a la pag. de autenticación 
	}else { 
	//sino, actualizo la fecha de la sesión 
	$_SESSION["UltimoAcceso"] = $ahora; 
	} 
} 

function isAdPr(){
	$TipoUsu = $_SESSION['TipoUsu'];
	if($TipoUsu==1 or $TipoUsu==2){
		return true;
	}
	return false;
}
function isPr(){
	$TipoUsu = $_SESSION['TipoUsu'];
	if($TipoUsu==2){
		return true;
	}
	return false;
}
function isAdm(){
	$TipoUsu = $_SESSION['TipoUsu'];
	if($TipoUsu==1){
		return true;
	}
	return false;
}

function isEst(){
	$TipoUsu = $_SESSION['TipoUsu'];
	if($TipoUsu==3){
		return true;
	}
	return false;
}
function isAcud(){
	$TipoUsu = $_SESSION['TipoUsu'];
	if($TipoUsu==4){
		return true;
	}
	return false;
}
?>