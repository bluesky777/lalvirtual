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

	
	