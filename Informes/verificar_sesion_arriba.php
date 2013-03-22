<? 
//iniciamos la sesión 
session_name("LoginUsuario"); 
session_start(); 

//Compruebo que el usuario está logueado 
if (!isset($_SESSION)){
	header("location: ../index.php"); //Nos vamos al menú si ya inicio sesión.
} else { 
    //sino, calculamos el tiempo transcurrido 
    $fechaGuardada = $_SESSION["UltimoAcceso"]; 
    $ahora = date("Y-n-j H:i:s"); 
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 
	
	if($tiempo_transcurrido >= 600) { 
     //si pasaron 10 minutos (600 seg) o más 
      session_destroy(); // destruyo la sesión 
      header("Location: ../../index.php"); //envío al usuario a la pag. de autenticación 
	}else { 
	//sino, actualizo la fecha de la sesión 
	$_SESSION["UltimoAcceso"] = $ahora; 
	} 
} 

?>