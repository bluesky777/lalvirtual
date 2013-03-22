$(function(){
	$(".AgrAct").on("click", function(e){
		idMSw = $(this).attr("id").split(":");
		idM = idMSw[1];
		
		window.open("../Actividades/Detalle_Act.php?idMat="+idM, "_blank");
		e.preventDefault();
	})
	
	$(".TitA a").on("click", function(e){
		idASw = $(this).attr("id").split(":");
		idA = idASw[1];
		
		window.open("../Actividades/Ver_Actividad.php?idAct="+idA, "_blank");
		e.preventDefault();
	})
	
})