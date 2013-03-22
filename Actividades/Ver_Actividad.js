$(function(){

	$("#EdA").on("click", function(){
		idA = $("#idAct").val();
		
		window.open("Detalle_Act.php?idAct="+idA, "_blank");
		e.preventDefault();
	})
	
	$("#ElA").on("click", function(){
		idA = $("#idAct").val();
		
		window.open("Detalle_Act.php?idAct="+idA, "_blank");
		e.preventDefault();
	})

})