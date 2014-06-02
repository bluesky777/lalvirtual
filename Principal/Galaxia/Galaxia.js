$(function(){
	
	$(".xkAnu").on("click", function(){
		idAnu = $.CalcId ($(this).attr("id") );
		$.CargaDinamica("../../")
	});

	$("#btBolFinales").on("click", function(e){
		window.open("../Informes/Boletin_Final/main_boletin_final.php");
	});


})