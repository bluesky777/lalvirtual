<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="css/css/custom-theme/jquery-ui-1.8.18.custom.css" type="text/css" media="all" rel="stylesheet" />

<script language="javascript" src="js/js/jquery-ui-1.8.18.custom.min.js"></script>
<script language="javascript" src="js/js/jquery-1.7.1.min.js"></script>

<script language="javascript">
$(document).ready(function() {
	alert("Hola");
    $("#test-list").sortable({ 
		handle : '.handle', 
		update : function () { 
		  var order = $('#test-list').sortable('serialize'); 
		  $("#info").load("process-sortable.php?"+order); 
		} 
	  });
});


</script>

<title>Ordenar competencias</title>
</head>

<body>

<?php

require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();

$sqlC="select * from tbcompetencias where MateriaGrupoCompet=".$_GET['idMat'];
$qSqlC=mysql_query($sqlC, $con)or die("No se pudo traer las consecuencias.".mysql_error());
?>
<ul id="test-list">
<?php
$i=1;
while($rSqlC=mysql_fetch_array($qSqlC)){
	
	?>
   
    <li id="listItem_<?php echo $i++;?>"><?php echo $rSqlC["Competencia"];?></li>
    
	<?php
}

?>
</ul>

<div id="info"></div>

</body>


</html>