<?php 
require_once("verificar_sesion.php");
require_once("conexion.php");

$con=Conectar();
$sqlprof="select * from tbprofesores where idProf='" .$_GET['idProf']. "'";
$qprof=mysql_query($sqlprof, $con) or die("Problemas al consultar al profesor.");
$rprof=mysql_fetch_array($qprof);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="js/ajax_notas.js"></script>
<script language="javascript" src="js/jquery.js"></script>
<script language="javascript">
	$(document).ready(function() {
        var sw=0;
		$(".tbIndicadores").hide();
		
		$("#AgregarCompet").click(function() {
			window.open("Agregar_Competencia.php", "Agregador", "width=300,height=500, top=100,left=100");
			return false;
        });
		
		function OcultarTb() {
			alert("Entró");
			var nomcl;
			nomcl = $(this).attr("class");
			if (sw==0){
				$("#tb"+nomcl).show('fast');
				sw=1;
			} else {
				$("#tb"+nomcl).hide('fast');
				sw=0;
			}
         };
		$(".tbIndicadores").click(function(e) {
            alert("Presionaste el indicador dentro de la competencia");
        });
		
    });
</script>
<link rel="stylesheet" type="text/css" href="css/basico.css">

<title>Notas: <?php  echo $rprof['NombresProf'] . " ". $rprof['ApellidosProf'];?></title>
</head>

<body>
<p>Profesor: <b>
  <?php  echo $rprof['NombresProf'] . " ". $rprof['ApellidosProf'];?>
  </b>
</p>
<div id="Materias">
<ul>
<?php
$sqlMater="select mg.idMaterGrupo, m.idMateria, m.NombreMateria, g.grupo, g.idGrupo, m.AbreviaturaMateria as AbrevMat
	from tbmaterias m, tbgrupos g, tbmateriagrupo mg 
	where m.idMateria=mg.idMateria and mg.idProfesor='".$_GET['idProf']."' and mg.idGrupo=g.idgrupo
	order by g.grupo";
$qMater=mysql_query($sqlMater, $con) or die ("Pailas con la consulta de materias" . mysql_error());
while($rowmate=mysql_fetch_array($qMater)){
	?>
	<li>
		<?php
		echo $rowmate['NombreMateria']; ?> - <?php echo $rowmate['grupo']; 
		$sqlComp="select c.idCompet, c.OrdenCompt, c.Competencia, c.PorcCompet, mg.idMaterGrupo
			from tbmateriagrupo mg, tbcompetencias c, tbperiodos p
			where mg.idMaterGrupo=c.MateriaGrupoCompet and c.PeriodoCompet=p.idPer and mg.idMaterGrupo='". $rowmate['idMaterGrupo']. "'";
		$qComp=mysql_query($sqlComp, $con) or die ("Pailander con las competencias. " . mysql_error());

		while ($rComp=mysql_fetch_array($qComp)){
		?> 
			  <div class="<?php echo "cl" . $rComp['idCompet'] . $rComp['idMaterGrupo'] ?>" onClick="OcultarTb();">
              	<input type="hidden" class="IdCompHide" value="<?php echo $rComp['idCompet']; ?>">
			    <?php echo $rComp['OrdenCompt']; ?></td>
			    <?php echo $rComp['Competencia']; ?></td>
                <?php echo $rComp['PorcCompet']; ?>%</td>
                </div>
                <div class="<?php echo "tbcl" . $rComp['idCompet'] . $rComp['idMaterGrupo'] ?>">
                <table border="1">
                	<tr>
                        <th width="30">No</th>
                        <th width="300">Indicadores</th>
                        <th width="30">Porc</th>
                    </tr>

					<?php
					$sqlInd="select * from tbindicadores, tbcompetencias 
						where idCompet=CompetenciaIndic and idCompet=".$rComp['idCompet'] . " order by OrdenIndic";
					$qInd=mysql_query($sqlInd, $con) or die ("Problemas con los indicadores de esta Competencia. " . mysql_error());
					if (mysql_fetch_row($qInd)>0){
						while($rInd=mysql_fetch_array($qInd)){
					?>
					<tr>
                    	<td><?php echo $rInd['OrdenIndic']; ?></td>
                    	<td><?php echo $rInd['Indicador']; ?></td>
                        <td><?php echo $rInd['PorcIndic']; ?></td>
                    </tr>
					<?php
						}
					} else {
					?>
					<tr>
                    	<td colspan="3"><a class="Agregar" href="<?php echo $rowmate['../idMaterGrupo']; ?>">Agregar indicador.</a></td>
                    </tr>
					<?php
					}
					?>
                    
                </table>
                </div>
		<?php
		}
		?>
        <a class="Agregar" href="<?php echo $rowmate['../idMaterGrupo']; ?>">Agregar Competencia</a>
        <div id="CompEdicion"><div>
    </li>
    <?php
}
?>
</ul>
</div>
</body>
</html>