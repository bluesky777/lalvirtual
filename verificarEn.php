<?php
require_once("conexion.php");
include ("php/clsPersonaTotal.php");

if (isset($_SESSION)){
	session_destroy();	
}

session_name("LoginUsuario");
session_start();

$con=Conectar();


///////////// CONSULTA A TRAVÉS DE CONTRASEÑA ENCRITADA MD5 /////////////////
    
$sqlEn = "select idUsu, TipoUsu, TipoUsuario, LoginUsu, ActivoUsu, PeriodoUsu, Periodo, Year, PerfilImg, PrincipalImg, CifradoUsu 
from tbtipousuarios, tbusuarios, tbperiodos
where LoginUsu='" . $_POST["txtLogin"] . "' and PassUsu='" . $_POST["txtPass"] . "' and tbusuarios.TipoUsu=tbtipousuarios.IdTipoUsu and PeriodoUsu=IdPer"; 



$q=mysql_query($sqlEn, $con) or die ("Consulta fallida : " . mysql_error());

if(mysql_num_rows($q)>0){

    $row=mysql_fetch_array($q);

    if ($row["ActivoUsu"]==1){

        EstablecerUsu($row, $con);


        if ($row['TipoUsu']==2){
            ////////////////////////////  TIPO ALUMNO /////////////////////////////////////

            $sqlProf="select * from tbprofesores where UsuarioProf='".$row['idUsu']."'";
            $qSqlProf=mysql_query($sqlProf, $con) or die ("No se trajeron los datos personanles del usuario profesor ingresado.".mysql_error());
            $num=mysql_num_rows($qSqlProf);

            if ($num==0){
                echo "Lo sentimos, Usuario no ha sido asignado a ningún profesor.";
                session_unset(); 
                //Cierra la sesión 
                session_destroy(); 
            } else {
                $rSqlProf=mysql_fetch_array($qSqlProf);
                $_SESSION['idUsuar']=$rSqlProf['idProf'];
                $_SESSION['NombresUsuar']=$rSqlProf['NombresProf'];
                $_SESSION['ApellidosUsuar']=$rSqlProf['ApellidosProf'];
                $_SESSION['SexoUsuar']=$rSqlProf['SexoProf'];
                $_SESSION['FechaNacUsuar']=$rSqlProf['FechaNacProf'];
                $_SESSION['FacebookUsuar']=$rSqlProf['FacebookProf'];

                $_SESSION['TituloProf']=$rSqlProf['TituloProf'];
                $_SESSION['TipoProf']=$rSqlProf['TipoProf'];

                $sqlPriv="select Privilegio, FechaLimite from tbusuariopriv up, tbprivilegios p where up.idUsu=". $_SESSION['idUsuar'] ." and up.idPriv=p.idPriv";

                $qSqlPriv=mysql_query($sqlPriv, $con) or die ("No se pudo consultar los privilegio de este usuario. ".mysql_error());

                $Privilegios[]=array();

                while($rPriv=mysql_fetch_array($qSqlPriv)){

                    if ($rPriv['FechaLimite']=="" or $rPriv['FechaLimite'] > date('Y-m-d H:i:s')){
                        $Privilegios[]=$rPriv['Privilegio'];
                    }else{
                        echo "No entro";
                    }
                }

                if (isset($_SESSION['Privilegios'])) unset($_SESSION['Privilegios']);
                $_SESSION['Privilegios'][]=$Privilegios;

                }
            }

            else if ($row['TipoUsu']==3)
            ////////////////////////////  TIPO ALUMNO /////////////////////////////////////

            {
                    $sqlAlum="select * from tbalumnos where UsuarioAlum='".$row['idUsu']."'";
                    $qSqlAlum=mysql_query($sqlAlum, $con) or die ("No se trajeron los datos personales del usuario alumno ingresado.".mysql_error());

                    $num=mysql_num_rows($qSqlAlum);
                    if ($num==0){  //Usuario asignado a algún Alumno?
                            echo "Lo sentimos, este Usuario no ha sido asignado a ningún alumno.";
                            session_unset(); 
                            //Cierra la sesión 
                            session_destroy(); 
                    } else {
                            $rSqlAlum=mysql_fetch_array($qSqlAlum);
                            $_SESSION['idUsuar']=$rSqlAlum['idAlum'];
                            $_SESSION['NombresUsuar']=$rSqlAlum['NombresAlum'];
                            $_SESSION['ApellidosUsuar']=$rSqlAlum['ApellidosAlum'];
                            $_SESSION['SexoUsuar']=$rSqlAlum['SexoAlum'];	
                            $_SESSION['FechaNacUsuar']=$rSqlAlum['FechaNacAlum'];
                            $_SESSION['FacebookUsuar']=$rSqlAlum['FacebookAlum'];

                            $_SESSION['PazySalvoAlum']=$rSqlAlum['PazySalvoAlum'];
                            $_SESSION['DeudaAlum']=$rSqlAlum['DeudaAlum'];


                            $sqlPriv="select Privilegio, FechaLimite from tbusuariopriv up, tbprivilegios p where up.idUsu=". $_SESSION['idUsuar'] ." and up.idPriv=p.idPriv";

                            $qSqlPriv=mysql_query($sqlPriv, $con) or die ("No se pudo consultar los privilegio de este usuario. ".mysql_error());

                            //Array temporal para almacenar los privilegios de usuario
                            $Privilegios[]=array();

                            while($rPriv=mysql_fetch_array($qSqlPriv)){

                                    if ($rPriv['FechaLimite']=="" or $rPriv['FechaLimite'] > date('Y-m-d H:i:s'))
                                    {
                                            //Si el privilegio no se ha vencido, lo añadimos a la variable temporal
                                            $Privilegios[]=$rPriv['Privilegio'];
                                    } 
                            }

                            //Añadimos los privilegios de la variable temporal al arreglo de sesión.
                            $_SESSION['Privilegios'][]=$Privilegios;


                    }  //Cierre pregunta de usuario asignado a un Alumno

        }  //Cierre si se verificaron los tipos de usuario


        echo "Exitoso"; 



    }else{

        echo("Usuario " . $row['TipoUsuario'] . " desactivado, comuniquese con el administrador.</br>");

    }
    

} else {
    
    echo("VerificarSe");
    
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

    $pe=new clsPersona();
    $pe->DatosxUsu($_SESSION['idUsuar']);
    $_SESSION['PerfilImg']=$pe->ImgPerf();
}




?>
