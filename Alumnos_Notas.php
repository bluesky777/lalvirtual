<?php
require_once("verificar_sesion.php");
require_once("conexion.php");
include("php/clsHallarDatos.php");

$con=Conectar();
$idComp=$_GET['idComp'];

$idGr = new clsHallarDatos();
$idGru = $idGr->idGrxIdComp($idComp);

$sqlComp="select c.Competencia, m.NombreMateria, NombreGrupo from tbcompetencias c, tbmateriagrupo mg, tbmaterias m, tbgrupos g
		where idCompet=".$idComp." and c.MateriaGrupoCompet=mg.idMaterGrupo and m.idMateria=mg.idMateria and g.idGrupo=mg.idGrupo";

$qSqlComp=mysql_query($sqlComp, $con) or die("No se pudo consultar la competencia ".$idComp);
$rSqlComp=mysql_fetch_array($qSqlComp);

function Promediar($idComp, $idAlum){
	
	$sqlPr="select count(n.idIndic) as totalInd, avg(n.Nota) as Prom, c.Competencia
		from tbnotas n, tbindicadores i, tbcompetencias c 
		where c.idCompet=".$idComp. " and n.idAlumno=".$idAlum." 
		and n.idIndic=i.idIndic and i.CompetenciaIndic=c.idCompet";
			
	$qSqlPr=mysql_query($sqlPr, $con) or die("No se trajo las notas" . mysql_error());
	$rSqlPr=mysql_fetch_array($qSqlPr);

	return $rSqlPr['Prom'];
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Insertar Notas</title>
<script type="text/javascript" src="js/jquery.js" ></script>
<!-- <script type="text/javascript" src="js/cuenta_regresiva.js"></script> -->
<script type="text/javascript">
	$(document).ready(function() {
		
		Promediar();
		
		$(".Indicador").hide("slow");
		
		$("#LinkEliminar").click(function(){
            if(confirm("¿Está seguro que desea proseguir? Esto eliminará las notas agregadas a todos los indicadores de esta competencia.")){
                $.ajax({
                    type: 'POST',
                    url: 'Eliminar_Notas_de_Indicador.php',
                    data: $(this).attr("href"),
                    success: function(data){
                            $("#Resultado").html(data);
                            history.back();
                    },
                    beforeSend: function(){
                            $('#Resultado').html("<img src='img/loader-mini.gif'/><br/>");
                    },
                    error: function(data){
                            $('#Resultado').html("Hubo problemillas " + data);
                    }
                });	
                return false;
            }
		});
		
		$("#NotasTodas").submit(function() {
			$.ajax({
                type: 'POST',
                url: 'Guardar_Notas.php',
                data: $(this).serialize(),
                success: function(data){
                    $("#Resultado").html(data);
                },
                beforeSend: function(){
                    $('#Resultado').html("<img src='img/loader-mini.gif'/><br/>");
                },
                error: function(data){
                    $('#Resultado').html("Hubo problemillas " + data);
                }
	        });
            $.ajax({
                type: 'POST',
                url: 'Guardar_Notas.php',
                data: $(this).serialize(),
                success: function(data){
                    $("#Resultado").html(data);
                },
                beforeSend: function(){
                    $('#Resultado').html("<img src='../img/loader-mini.gif'/><br/>");
                },
                error: function(data){
                	$('#Resultado').html("Hubo problemillas " + data);
                }
            });
            return false;
		});
		
		
				
		$(".NotaOnly").focusout(function(e) {
			
			Nota=$(this).val();
			
			Nombre=$(this).attr('name');
			
			PosI=Nombre.indexOf('I');
			PosA=Nombre.indexOf('A');
			
			idInd=Nombre.substring(PosI+1);
			idAlu=Nombre.substring(PosA+1, PosI);
			
			PromediarAlu(idAlu);
			
			Datos="idAlu="+idAlu+"&idInd="+idInd+"&Nota="+Nota;
			
			$.ajax({
				type: 'POST',
				url: 'Guardar_Notas_Only.php',
				data: Datos,
				success: function(data){
					$("#Resultado").html(data);
					
				},
				beforeSend: function(){
					$('#Resultado').html("<img src='img/loader-mini.gif'/><br/>");
				},
				error: function(data){
					$('#Resultado').html("Hubo problemas en la red."/* + data*/);
				}
			});
            return false;
		});
		
		
		
		$("#Atras").click(function(e) {
            history.back();
        });
		
		
		
		
    });
		
function eliminar(Datos){
	if (confirm("¿Desea eliminar las notas de esta competencia a ?")){
		alert(Datos);
		$.ajax({
			url: "Eliminar_Notas_de_Alumno.php",
			data: Datos,
			type: "POST",
			success: function(resp){
				alert(resp);
				history.back();
			}
		})
	}
}

function PromediarAlu(idAlum){
	
	Acumu=0;
	idAluAnt=0;
	
	$(".LaNota").each(function(index, element) {
        
		NombreP=$(this).attr('name');
		
		if($(this).val()<70){
			$(this).css({background: '#FF7171'});
		} else {
			$(this).css({background: '#FFFFFF'});
		}
		
		PosIp=NombreP.indexOf('I');
		PosAp=NombreP.indexOf('A');
		
		idIndp=NombreP.substring(PosIp+1);
		idAlup=NombreP.substring(PosAp+1, PosIp);
		
		idTh="CodInd"+idIndp;
		
		Porc=document.getElementById(idTh).className/100;
		Notap=$(this).val();
		
		valTemp=Porc*Notap
		
		if(idAluAnt!=idAlup){
			Acumu=0;
		} 
		
		Acumu+=valTemp;
		idAluAnt=idAlup;
		
		NomP="Prom"+idAlup;
		document.getElementById(NomP).innerHTML=Acumu;
		
		
    });
	//Tomar todos los texts que tengan el codigo del alumno y promediar
	
}

function Promediar(){
	
	Acumu=0;
	idAluAnt=0;
	
	$(".LaNota").each(function(index, element) {
        
		Nombre=$(this).attr('name');
		
				
		if($(this).val()<70){
			$(this).css({background: '#FF7171'});
		} else {
			$(this).css({background: '#FFFFFF'});
		}
		
		PosI=Nombre.indexOf('I');
		PosA=Nombre.indexOf('A');
		
		idInd=Nombre.substring(PosI+1);
		idAlu=Nombre.substring(PosA+1, PosI);
		
		idTh="CodInd"+idInd;
		
		Porc=document.getElementById(idTh).className/100;
		Nota=$(this).val();
		
		valTemp=Porc*Nota
		
		if(idAluAnt!=idAlu){
			Acumu=0;
		} 
		
		Acumu+=valTemp;
		idAluAnt=idAlu;
		
		NomP="Prom"+idAlu;
		document.getElementById(NomP).innerHTML=Acumu;
		
		
    });
	//Tomar todos los texts que tengan el codigo del alumno y promediar
	
}


</script>

<style>
.LaNota{
	margin-left:1; 
	margin-right:1;
	
}
.ResFlot{
	position: fixed;
	background: rgb(42, 35, 228);
	padding: 4px 8px;
	border-radius: 4px;
	top: 10px;
	right: 10px;
	color: #fff;
}
</style>

</head>

<body background="img/fondito.png">


<span id='Resultado' class='ResFlot'></span>


<p>Materia: <b style="font-size:18px"><?php echo $rSqlComp['NombreMateria']; ?> - <b style="font-size:24px"><?php echo $rSqlComp['NombreGrupo']; ?></b></b>
</p>
<p>Competencia: <b><i style="font-size:24px"><?php echo $rSqlComp['Competencia']; ?></i></b><br>
  <?php
$sqlAlum="select a.idAlum, a.NombresAlum NomA, a.ApellidosAlum ApeA, g.idGrupo, 
	g.NombreGrupo NomG, mg.CreditosMater Cred,
	c.Competencia, c.PorcCompet, c.OrdenCompt
	from tbalumnos a, tbgrupoalumnos ga, tbgrupos g, tbmateriagrupo mg, tbcompetencias c
	where c.idCompet=".$idComp." and c.MateriaGrupoCompet=mg.idMaterGrupo 
	and mg.idGrupo=g.idGrupo 
	and g.idGrupo=ga.idGrupo 
	and ga.idPeriodo=".$_SESSION['PeriodoUsu']."
	AND a.idAlum = ga.idAlumno order by a.ApellidosAlum";

$sqlInd="select * from tbindicadores i where i.CompetenciaIndic=".$idComp." order by OrdenIndic;";


$qSqlInd = mysql_query($sqlInd, $con) or die ("Pailas con los indicadores. " . mysql_error());

if(isAdPr()){
?>
<br>
Cada nota se guardará automáticamente de forma individual, sin embargo puede presionar la tecla <b>Enter</b> o el botón <b>Guardar todo</b> para que guarde todas las notas de forma masiva.
</p>
<form name="tbNotasTodas" id="NotasTodas">
  <table border="1">
  <thead>
	<tr bgcolor="#FFBD35">
	  <th>&nbsp;</th>
    	<th>No</th>
    	<th><input type="hidden" value=<?php echo $idGru; ?> name="txtIdGrupo" >
    	  <input type="hidden" value=<?php echo $idComp; ?> name="txtIdComp" >
        Apellidos y nombres</th>
        
        <?php
		
	while ($rSqlInd = mysql_fetch_array($qSqlInd)){
			
		?>
        
       	<th id="CodInd<?php echo $rSqlInd['idIndic']; ?>" title="<?php echo $rSqlInd['Indicador']; ?>" class="<?php echo $rSqlInd['PorcIndic']; ?>">Ind <?php echo $rSqlInd['OrdenIndic']; ?>
        	  </th>
        <?php
		
	} /// fin while
			
        ?>
        <th>Prom</th>
    </tr>
  </thead>
  <tbody>
  	<?php
	$i=1;
	$qSqlAlum = mysql_query($sqlAlum, $con) or die ("No se trajeron los alumnos". mysql_error());
	$sw=0;
	while ($rSqlAlum=mysql_fetch_array($qSqlAlum)){

		if($sw==0){
			$sw=1;
	?>
	<tr>
	  <td>
      	<img src="img/icono_eliminar.gif" width="17" height="22" style="cursor:pointer" onClick="eliminar(<?php echo "idAlum=".$rSqlAlum['idAlum']."&idComp=".$_GET['idComp']; ?>);"/></td>
    	<td><?php echo $i++; ?></td>
		<td><label title="Este es el estudiante" ><?php echo $rSqlAlum['ApeA']; ?> <?php echo $rSqlAlum['NomA']; ?></label></td>
        
		<?php

		$qSqlInd = mysql_query($sqlInd, $con) or die ("Pailas con los indicadores. " . mysql_error()); //Ejecutamos otra vez
		
		while($rSqlInd = mysql_fetch_array($qSqlInd)){
			
			$sqlNot="select * from tbnotas 
				where idIndic=".$rSqlInd['idIndic']." and idAlumno=".$rSqlAlum['idAlum'];
				
			$qSqlNot=mysql_query($sqlNot, $con) or die ("No se pudo traer 
				el indicador ".$rSqlInd['idIndic']." del alumno:".$rSqlAlum['idAlum']);
				
			$nSqlNot=mysql_num_rows($qSqlNot);

			if($nSqlNot==0){
				$sqlInsNot="insert into tbnotas(idIndic, idAlumno, Nota) values('". $rSqlInd['idIndic']."', '".$rSqlAlum['idAlum']."','". $rSqlInd['NotaPorDefecto']."')";
				
				$qSqlInsNot=mysql_query($sqlInsNot, $con) or die("No se ingresó nota por defecto en indicador " . $rSqlInd['idIndic'] . " - " . mysql_error(). $sqlInsNot);
				

			?>
        <td title="<?php echo $rSqlInd['Indicador']; ?>">
        <input type="text" name="idNotaA<?php echo $rSqlAlum['idAlum']."I".$rSqlInd['idIndic']; ?>" size="3" maxlength="3" value="<?php echo $rSqlInd['NotaPorDefecto']; ?>" class="NotaOnly LaNota"  title="<?php echo $rSqlInd['Indicador']; ?>">
        
        </td>
        	<?php
			} else {
				
				$rSqlNot=mysql_fetch_array($qSqlNot);

				
			?>
        <td><input type="text" name="idNotaA<?php echo $rSqlAlum['idAlum']."I".$rSqlInd['idIndic']; ?>" size="3" maxlength="3" value="<?php echo $rSqlNot['Nota']; ?>" class="NotaOnly LaNota"  title="<?php echo $rSqlInd['Indicador']; ?>" ></td>
        	<?php	
			}
		}
		
		?>
        <td align="center"><label id="Prom<?php echo $rSqlAlum['idAlum']; ?>"><?php //echo Promediar($idComp, $rSqlAlum['idAlum']); ?></label></td>
    </tr>
    <?php
		} else{
			$sw=0;
			?>
	<tr  bgcolor="#FDC4A8">
	  <td><img src="img/icono_eliminar.gif" width="17" height="22" style="cursor:pointer" onClick="eliminar(<?php echo "idAlum=".$rSqlAlum['idAlum']."&idComp=".$_GET['idComp']; ?>);"/></td>
    	<td><?php echo $i++; ?></td>
		<td><?php echo $rSqlAlum['ApeA']; ?> <?php echo $rSqlAlum['NomA']; ?></td>
        
		<?php

		$qSqlInd = mysql_query($sqlInd, $con) or die ("Pailas con los indicadores. " . mysql_error()); //Ejecutamos otra vez
		
		while($rSqlInd = mysql_fetch_array($qSqlInd)){
			$sqlNot="select * from tbnotas where idIndic=".$rSqlInd['idIndic']." and idAlumno=".$rSqlAlum['idAlum'];
			$qSqlNot=mysql_query($sqlNot, $con) or die ("No se pudo traer el indicador ".$rSqlInd['idIndic']." del alumno:".$rSqlAlum['idAlum']);
			$nSqlNot=mysql_num_rows($qSqlNot);

			if($nSqlNot==0){
				//echo ". ".$nSqlNot." esta vacio. ";
				$sqlInsNot="insert into tbnotas(idIndic, idAlumno, Nota) values('". $rSqlInd['idIndic']."', '".$rSqlAlum['idAlum']."','". $rSqlInd['NotaPorDefecto']."')";
				
				$qSqlInsNot=mysql_query($sqlInsNot, $con) or die("No se ingresó nota por defecto en indicador " . $rSqlInd['idIndic'] . " - " . mysql_error(). $sqlInsNot);


			?>
        <td title="<?php echo $rSqlInd['Indicador']; ?>"><input type="text" name="idNotaA<?php echo $rSqlAlum['idAlum']."I".$rSqlInd['idIndic']; ?>" size="3" maxlength="3" value="<?php echo $rSqlInd['NotaPorDefecto']; ?>" class="NotaOnly LaNota"  title="<?php echo $rSqlInd['Indicador']; ?>"></td>
        	<?php
			} else {
				
				$rSqlNot=mysql_fetch_array($qSqlNot);
	
				?>
        <td><input type="text" name="idNotaA<?php echo $rSqlAlum['idAlum']."I".$rSqlInd['idIndic']; ?>" size="3" maxlength="3" value="<?php echo $rSqlNot['Nota']; ?>" class="NotaOnly LaNota"  title="<?php echo $rSqlInd['Indicador']; ?>"></td>
        	<?php	
			}
		}

		?>
        <td align="center"><label id="Prom<?php echo $rSqlAlum['idAlum']; ?>"><?php //echo Promediar($idComp, $rSqlAlum['idAlum']); ?></label></td>
    </tr>
    <?php
		}
	}
	?>
  </tbody>
</table>
  <input type="button" value="Atrás" id="Atras" >
  <input type="submit" value="Guardar todo" id="btGuardar">
<a href="idComp=<?php echo $idComp; ?>" id="LinkEliminar">Eliminar todos</a>
</form>
<?php
}
?>
<br>

</body>
</html>