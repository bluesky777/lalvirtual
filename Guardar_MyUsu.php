<?php
require_once('conexion.php');
require_once('verificar_sesion.php');

$con=Conectar();

$max=3500000;

$sqlU="select LoginUsu from tbusuarios where idUsu=".$_POST['idUsu'];
$rSqlU=mysql_query($sqlU, $con);
$qSqlU=mysql_fetch_array($rSqlU);

$NomUsu=$qSqlU['LoginUsu'];


switch($_POST['OperUsu']){
    case "ImgPerf":
        
        //$CarpUsu="../img/Usuarios/".$NomUsu."_".$_POST['idUsu']; //No entiendo por qué no sirve
        $CarpUsu="img/Usuarios/".$NomUsu."_".$_POST['idUsu'];

        $filesize = $_FILES['ImagenPerf']['size'];
        $filename = "Perfil.jpg";
        $Subido=false; 
        $Archiv = $CarpUsu ."/". $filename;
        $Archivsmall = $CarpUsu ."/small_". $filename;
        
        if($filesize < $max){
                if($filesize > 0){
                    
                    if(is_dir($CarpUsu)){
                        if (move_uploaded_file($_FILES['ImagenPerf']['tmp_name'], $Archiv)) {
                            $Subido=true;
                        }
                        
                    } else {
                        
                        mkdir($CarpUsu);
                        if (move_uploaded_file($_FILES['ImagenPerf']['tmp_name'], $Archiv)) {
                            $Subido=true;
                        }
                    }
                    
                    
                    $tam=getimagesize($Archiv);  
                    if($tam[0] > 500 OR $tam[1] > 500){
                        
                        cambiartam($Archiv, $Archivsmall, $tam[2], 200, 200); 
                    } 
                    
                } else {
                    print("Campo vacío, no ha seleccionado ninguna imagen");
                }
            } else {
                print("Sólo se permiten imágenes de máximo 3.5 MB.");
            }

        
        if($Subido==true){

            $sqlI="INSERT INTO tbimagenes (NombreImg, UsuId) VALUES ('".$filename."', ".$_POST['idUsu'].");";
            $rSqlI=mysql_query($sqlI,$con);
            $idImg=mysql_insert_id();

            $sqlU="UPDATE tbusuarios SET PerfilImg=".$idImg." WHERE idUsu='".$_POST['idUsu']."'";
            $qSqlU=mysql_query($sqlU, $con) or die ("Nada que ver con actualizar la imagen de perfil. ". mysql_error());
            
            print("SubidaExitosa");
            
            
        }
        

        break;
    
    case "ImgPrin":
        
        break;
}









function cambiartam($nombre,$archivo,$tipo,$ancho,$alto) { 

    if ($tipo==1) { $imagen=imagecreatefrompng($nombre); } 
    if ($tipo==2) { $imagen=imagecreatefromjpeg($nombre); } 
    if ($tipo==3) { $imagen=imagecreatefromgif($nombre); } 

    $x=imageSX($imagen); 
    $y=imageSY($imagen); 

    if ($x > $y){ 
        $w=$ancho; 
        $h=$y*($alto/$x); 
    } 

    if ($x < $y){ 
        $w=$x*($ancho/$y); 
        $h=$alto; 
    } 

    if ($x == $y){ 
        $w=$ancho; 
        $h=$alto; 
    } 


    $destino=ImageCreateTrueColor($w,$h); 
    imagecopyresampled($destino,$imagen,0,0,0,0,$w,$h,$x,$y);  

    if ($tipo==1) { imagejpeg($destino,$archivo); } 
    if ($tipo==2) { imagepng($destino,$archivo); } 
    if ($tipo==3) { imagegif($destino,$archivo); } 



    imagedestroy($destino);  
    imagedestroy($imagen);  
} 




?>