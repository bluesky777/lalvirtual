$(function(){
	
	$(".xkAnu").on("click", function(){
		idAnu = $.CalcId ($(this).attr("id") );
		$.CargaDinamica("../../")
	})
})