
$("#NuevaMateria").hide();

$("#btNuevo").click(function() {
    $("#NuevaMateria").show("fast");
    $("#txtOrden").focus();
});

$("#NuevaMat").on("submit", function(e){

    e.preventDefault();

    if( vacio($("#txtOrden").val()) == false ) { 
        
        alert("Introduzca el No para el orden.");
        $("#txtOrden").focus(); 
        return false  
        
    } else if ( vacio($("#txtCreditos").val()) == false ){ 
        
        alert("Introduzca las horas semanales.");
        $("#txtCreditos").focus(); 
        return false
        
    }

    $.ajax({
        type: 'POST',
        url: '../Materias/Guardar_Materia_Inscripcion.php',
        data: $(this).serialize(),
        success: function(data){
            $("#Resultado").html(data);
            //document.location.reload();
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

$("#Cancelar").click(function() {
    $("#NuevaMateria").hide("fast");
});

$(".Eliminar").click(function(e) {
    if (confirm("¿Está seguro de que desea eliminar esta competencia?")){
        var idMatGr = "idMatGr=" + $(this).attr("id");
        $.ajax({
            url: "../Eliminar_Materia_Inscrita.php",
            data: idMatGr,
            type: "POST",
            success: function(resp){
                $("#Resultado").html(resp);
                alert(resp);
                //location.reload(true);

                return false;
            }
        });
    }
    return false;
});

function vacio(q) {  
    for ( i = 0; i < q.length; i++ ) {  
        if ( q.charAt(i) != " " ) {  
            return true  
        }  
    }  
    return false  
}  
