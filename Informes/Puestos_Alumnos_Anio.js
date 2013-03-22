$(function(){

	$("#btPuestExc").on("click", function(){
		idGr=$("#hdIdGrupPuest").val();
		window.open("../Informes/Puestos_Alumnos/Exc_Puestos_Alumnos_Anio.php?idGrupo="+idGr, "_blank");
	})

	$("#btPuestPdf").on("click", function(){
		idGr=$("#hdIdGrupPuest").val();
		window.open("../Informes/Puestos_Alumnos/Pdf_Puestos_Alumnos_Anio.php?idGrupo="+idGr, "_blank");
	})

})