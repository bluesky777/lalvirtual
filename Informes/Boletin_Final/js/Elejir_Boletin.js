
$("#frmBoletinAlumno").submit(function(e){
	
	//url = $("#frmBoletinAlumno").attr("action")+"?IdAlum="+$("#txtIdAlum").val();
	//$(location).attr("href", url);
	CkFr=0;
	if ($("#ckFirmas").attr("checked")=="checked") {
		CkFr=1;
	};
	window.open($("#frmBoletinAlumno").attr("action")+"?idAlum="+$("#txtIdAlum").val()+"&Firm="+CkFr, "_blank");
	e.preventDefault();
})


$("#frmBoletinesAlumnos").on("submit", function(){
	window.open($("#frmBoletinesAlumnos").attr("action")+"?idGrupo="+$("#idGrupos").val(), "_blank");
	return false;	
})