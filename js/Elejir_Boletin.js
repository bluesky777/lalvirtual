
$("#frmBoletinAlumno").submit(function(e){
	
	//url = $("#frmBoletinAlumno").attr("action")+"?IdAlum="+$("#txtIdAlum").val();
	//$(location).attr("href", url);
	window.open($("#frmBoletinAlumno").attr("action")+"?IdAlum="+$("#txtIdAlum").val(), "_blank");
	e.preventDefault();
})


$("#frmBoletinesAlumnos").on("submit", function(){
	window.open($("#frmBoletinesAlumnos").attr("action"), "_blank");
	return false;	
})