<?
require_once("conexion.php");

$con=Conectar();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Usuarios</title>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.jeditable.js"></script>


<style type="text/css" title="currentStyle">
	@import "css/demo_page.css";
	@import "css/demo_table.css";
</style>

<script type="text/javascript">


$(document).ready(function() {
    var eTb=$("#tbUsuario").dataTable();
	
	
	
	$('td', eTb.fnGetNodes()).editable('php/editable_campo.php', {
		"callback": function( sValue, y ) {
						var aPos = eTb.fnGetPosition( this );
						eTb.fnUpdate( sValue, aPos[0], aPos[1] );
					},	
		"submitdata": function ( value, settings ) {
                        return {
                            
                            
							"row_id": this.parentNode.getAttribute('id'),
                           	"row_column": eTb.fnGetPosition( this )[2]
						};
					},
		"height": "14px",
		"tooltip": "De click, edite y luego presione enter para guardar los cambios"

	});
	
		
	
	$("#NewRow").click(function () {
		alert("Hola");
		eTb.dataTable().fnAddData( [
			"Agregue nuevo",
			"",
			"",
			"",
			"" ] );
		 
		giCount++;
    });
	
	                
	$("tfoot input").keyup( function () {
		/* Filter on the column (the index) of this element */
		eTb.fnFilter( this.value, $("tfoot input").index(this) );
	} );
	
	$("tfoot select").change(function(e) {
		/*eTb.fnFilter( this.value, $("tfoot select").index(this));*/
		eTb.fnFilter( this.value, 2 );
    });

	
	$("tfoot input").each( function (i) {
		asInitVals[i] = this.value;
	} );

	
	$("tfoot input").focus( function () {
		if ( this.className == "search_init" )
		{
			this.className = "";
			this.value = "";
		}
	} );
	
                
	$("tfoot input").blur( function (i) {
		if ( this.value == "" )
		{
			this.className = "search_init";
			this.value = asInitVals[$("tfoot input").index(this)];
		}
	} );

	
	
                
});



</script>

</head>

<body>

<h2>Usuarios</h2>
<a href="javascript:void(0);" id="NewRow">Nuevo</a>

<table cellpadding="0" cellspacing="0" class="display" id="tbUsuario">

<thead>
<tr>
	<th>id</th>
    <th>Usuario</th>
    <th>Cifrado</th>
    <th>Tipo</th>
    <th>Activo</th>
</tr>
</thead>
<tbody>
<?php

$sql="select * from tbusuarios";
$qSql=mysql_query($sql, $con)or die("Pailas con los usuarios. ".mysql_error());

while($rSql=mysql_fetch_array($qSql)){
?>	
<tr id="<? echo $rSql['idUsu']; ?>">
	<td class="center"><?php echo $rSql['idUsu']; ?></td>
    <td id="LoginUsu"><?php echo $rSql['LoginUsu']; ?></td>
    <td class="center"><?php echo $rSql['CifradoUsu']; ?></td>
    <td class="center"><?php echo $rSql['TipoUsu']; ?></td>
    <td class="center"><?php echo $rSql['ActivoUsu']; ?></td>
</tr>
<?php
}
?>
</tbody>
<tfoot>
<tr>
	<th><input type="text" name="search_Id" value="Buscar id" class="search_init" /></th>
    <th><input type="text" name="search_Usuario" value="Buscar usuario" class="search_init" /></th>
    <th>
        <select  class="search_init">
        	<option value="0">No</option>
            <option value="1">Si</option>
            <option value="">Todos</option>
        </select>
    </th>
    <th><input type="text" name="search_Tipo" value="Buscar tipo" class="search_init" /></th>
    <th><input type="text" name="search_Activo" value="Buscar activo" class="search_init" /></th>
</tr>
</tfoot>
	
</table>

</body>
</html>

