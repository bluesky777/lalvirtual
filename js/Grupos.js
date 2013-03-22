$("#Formulario").submit(function() {
	$.ajax({
        type: 'POST',
        url: '../Guardar_Grupos_Todos.php',
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
    return false;
});

$(".GruAsignaturas").on("click",function() {
    var urldir=$(this).attr('id');
    $.CargaDinamica(urldir, "../js/Asignaturas_Grupos.js", "../css/Asignaturas_Grupos.css");
    return false;
});
