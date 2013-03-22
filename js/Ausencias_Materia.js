

$("#frmAusencias").submit(function() {

	$.ajax({
		type: 'POST',
		url: '../Guardar_Ausencias.php',
		data: $(this).serialize(),
		success: function(data){
			$("#ResultadoAus").html(data);
		},
		beforeSend: function(){
			$('#ResultadoAus').html("<img src='../img/loader-mini.gif'/><br/>");
		},
		error: function(data){
			$('#ResultadoAus').html("Hubo problemillas " + data);
		}
	});
    return false;
});

		
$("#Atras").click(function(e) {
    history.back();
});

