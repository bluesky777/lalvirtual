
	$(".Matricular").on("click", function(e){
		e.preventDefault();

	    var urlDir="../Periodos/Matricular_Alumnos_Periodo.php?idPer="+$(this).attr('id');

	    $("#RespContePeri").html("<img src='../img/loader-mini.gif'/><br/>");

	    $("#RespContePeri").load(urlDir, function(){
	        $.getScript("../Periodos/js/Matricular_Alumnos_Periodo.js");
	        $.CargarCSS("../Periodos/css/Matricular_Alum.css");
	    })
	});
