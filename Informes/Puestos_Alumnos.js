$(function(){

	$("#btPuestExc").on("click", function(){
		idGr=$("#hdIdGrupPuest").val();
		window.open("../Informes/Puestos_Alumnos/Exc_Puestos_Alumnos.php?idGrupo="+idGr, "_blank");
	})

	$("#btPuestAnioFirExc").on("click", function(){
		idGr=$("#hdIdGrupPuest").val();
		window.open("../Informes/Puestos_Alumnos/Exc_Puestos_Alumnos_Anio_Fir.php?idGrupo="+idGr, "_blank");
	})

	$("#btPuestPdf").on("click", function(){
		idGr=$("#hdIdGrupPuest").val();
		window.open("../Informes/Puestos_Alumnos/Pdf_Puestos_Alumnos.php?idGrupo="+idGr, "_blank");
	})

	$("#btPuestAnioFirDobleExc").on("click", function(){
		console.log("Hola soy Goku");
		idGr=$("#hdIdGrupPuest").val();
		window.open("../Informes/Puestos_Alumnos/Exc_Puestos_Alumnos_Anio_Fir.php?idGrupo="+idGr+"&doble=true", "_blank");
	})

})