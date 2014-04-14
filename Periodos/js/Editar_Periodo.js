
$(".Matricular").on("click", function(e){
	e.preventDefault();

    var urlDir="../Periodos/Matricular_Alumnos_Periodo.php?idPer=" + $(this).attr('id');

    $("#RespContePeri").html("<img src='../img/loader-mini.gif'/><br/>");

    $("#RespContePeri").load(urlDir, function(){
        $.getScript("../Periodos/js/Matricular_Alumnos_Periodo.js");
        $.CargarCSS("../Periodos/css/Matricular_Alum.css");
    })
});

$("#btnCrPeriodo").on("click", function(e){
	e.preventDefault();
	$("#RespContePeri").html("<img src='../img/loader-mini.gif'/><br/>");

	var slYear = $("#slYear").val(), slPeriodo = $("#slPeriodo").val(),
		slActual = $("#slActual").val(), values, urlDir;

	values = "slYear="+ slYear +"&slPeriodo="+ slPeriodo + "&slActual="+ slActual;
	urlDir = "../Periodos/Crear_Periodo.php?"+ values;

	console.log(urlDir);

    $("#RespContePeri").load(urlDir)
});
