$(function(){

	$(".ComentsPers").on("hover", function(){
		$("#ContPer").html( $(this).attr("title") );
		$("#DiagV").css("display", "block");

	})

	$("#CerrarDiag a").on("click", function(e){
		e.preventDefault();
		$("#DiagV").css("display", "none");
	})

})