
<?php

header("Content-type: text/xml");


function Conectar(){

	$hostname="localhost";
	$database="lalvirtu_myvc";
	$login="root";
	$pass="123456";
	
	$con=mysql_connect($hostname, $login, $pass) or die("Problemas con la conexión al servidor");
	
	mysql_select_db($database, $con)or die ("No se conecta a la db");
	
	return $con;	
}

$con=Conectar();

$sql="select * from tbalumnos where idALum<300";

$qSql=mysql_query($sql, $con) or die("No se trajeron los alumnos.");


$salida_xml="<?xml version=\"1.0\" ?>\n";
$salida_xml.="<informacion>\n";

for($x=0; $x=mysqli_num_rows($qSql); $x++){
	$rSql=mysqli_fetch_assoc($qSql);
	$salida_xml.="\t<Alumno>\n";
	$salida_xml.="\t\t<Nombres>" . $qSql['NombresAlum']. "<\Nombres>\n";
	$salida_xml.="\t\t<Apellidos>" . $qSql['ApellidosAlum'] . "<\Apellidos>\n";
		// Este es el formato para corregir caracteres especiales
		//$rSql['texto'] = str_replace("&", "&amp;", $$rSql['NombresAlum']);
	
	$salida_xml.="\t\t<Documento>" . $rSql['DocAlum'] . "</Documento>\n";
	$salida_xml.="\t\t<FechaNacimiento>" . $rSql['FechaNacAlum'] . "</FechaNacimiento>\n";
	$salida_xml.="\t\t<Direccion>" . $rSql['DireccionAlum'] . "</Direccion>\n";
	$salida_xml.="\t\t<Barrio>" . $rSql['BarrioAlum'] . "</Barrio>\n";
	$salida_xml.="\t</Alumno>\n";
	$salida_xml.="</informacion>";
	
	echo $salida_xml;
	
}

?>