<?

require_once("conexion.php");


$con=Conectar();

$Oper=$_POST['Oper'];

switch ($Oper) {
    case "PediEmail":  //Si pide cambio dando el Email
        
        require_once('php/recaptchalib.php');
        $privatekey = "6Lem-dMSAAAAAOwqVDqvW7KjfjETmk-jWUuSw5X0";
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if (!$resp->is_valid) {
            die ("ReCaptchaError");
        } else {
            //die ("Coincide");
        }

        
        
        
        $destinatario = $_POST['email'];

        $sqlUsu="select idUsu, LoginUsu from tbusuarios where EmailUsu='".$destinatario."'";
        $qSqlUsu=mysql_query($sqlUsu, $con)or die("No se pudo comparar el email de usuario. ".  mysql_error());
        $num=mysqli_num_rows($qSqlUsu);
        
        if($num>1){
            mysql_free_result($qSqlUsu);
            die("Existe más de un usuario asociado a este email, comuniquese con el administrador.");
        }
        
        $rSqlUsu=mysqli_fetch_array($qSqlUsu);
        $idUsu=$rSqlUsu["idUsu"];
        $sqlCol="select SitioWebMyVc, NombreColegio, NombreCortoCole from tbyearcolegio where Year=".date('Y');
        $qSqlCol=mysql_query($sqlCol, $con)or die("No se trajo el Sitio. ".  mysql_error());
        $rSqlCol=  mysqli_fetch_array($qSqlCol);
        $NomCor=$rSqlCol['NombreCortoCole'];
        
        $codConfirm=rand(0000000000, 9999999999).$idUsu;
        $codReject=rand(00000000000, 99999999999).$idUsu;
        
        $sqlIns="UPDATE `tbusuarios` SET `ConfirmCodUsu`='".$codConfirm."', `RejectCodUsu`='".$codReject."' WHERE `idUsu`='".$idUsu."'";
        $qSqlIns=mysql_query($sqlIns, $con)or die("No se ingresaron los códigos. ".  mysql_error());
        
        if((!$NomCor=='') OR (!$NomCor==null)){ $NomCorT=' del <b>'.$NomCor.'</b>'; } 
        
        $asunto = "Recuperar contraseña-".$rSqlCol['NombreCortoCole'];
        $cuerpo = '
        <html>
        <head>
        <title>Recuperar contraseña</title>
        </head>
        <body>
            <h1>'.$rSqlCol['NombreColegio'].'</h1>
            <h2>Hola apreciado usuario</h2>
        <p>
        Has solicitado restablecer tu contraseña '.$NomCorT.'. 
        Si aceptas cambiar la contraseña, entra al siguiente enlace: 
        <br />
        <a href=\'http://www.lalvirtual.com/lal/Cambiar_Pass_Email.php?conf='.$codConfirm.'&Oper=y&idU='.$idUsu.'\'>Confirmar cambio.</a>
        </p>
        
        <p>
        Si quieres rechazar el cambio presiona este enlace:
        <br />
        <a href="http://www.lalvirtual.com/lal/Cambiar_Pass_Email.php?conf=\''.$codReject.'\'&Oper=\'n\'&idU=\''.$idUsu.'\'">Rechazar este cambio.</a>
        </p>
        <p>
        Si no has pedido ningún cambio puedes ignorar este mensaje o puedes bloquear la recuperación de contraseña temporalmente.
        </p>
        
        <p><b><a href="http://www.bluesky.lalvirtual.com">MyVc</a> - La informática también alaba a Dios</b></p>
        </body>
        </html>
        ';
        
        //para el envío en formato HTML
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";

        //dirección del remitente
        
        if((!$NomCor=='') OR (!$NomCor==null)){ $NomCorT=$NomCor.'-'; } 
        
        $headers .= "From: ".$NomCorT. "ColegioVirtual <josethmaster@lalvirtual.com>\r\n";

        //dirección de respuesta, si queremos que sea distinta que la del remitente
        $headers .= "Reply-To: licadli@lalvirtual.com\r\n";

        //ruta del mensaje desde origen a destino
        $headers .= "Return-path: josethmaster@lalvirtual.com\r\n";

        //direcciones que recibián copia
        //$headers .= "Cc: maria@desarrolloweb.com\r\n";

        //direcciones que recibirán copia oculta
        //$headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n";

        mail($destinatario,$asunto,$cuerpo,$headers);
            
        echo 'El mensaje a sido enviado a tu correo. Vuelve a <a href="http://www.lalvirtual.com">lalvirual.com</a> e intentalo de nuevo.';
        
        break;

    case "PediUsu":  //Si pide cambio dando el Nombre de usuario
        
        break;
    
    default:
        break;
}


?> 

