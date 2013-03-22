
$("#tabsAnun").tabs();

$("#frmAnun_Normal").submit(function(){
	

})

$("#frmAnun_Especif").submit(function(){
	
    $.ajax({
        type: 'POST',
        url: "../Anunciar_Guardar.php",
        data: $("#frmAnun_Especif").serialize(),
        success: function(data){
            $("#RespAnun").html(data)
			//$("#CuadroAnunciar").dialog("close");
        },
        beforeSend: function(){
            $("#RespAnun").html("<img src='../img/loader-mini.gif'/><br/>");
        },
        error: function(data){
            $("#RespAnun").html("Lo sentimos, hubo problemas de red. " + data);
        }
    });


	return false;
})

