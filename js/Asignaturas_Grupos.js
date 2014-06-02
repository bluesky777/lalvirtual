
$("#Grupos").change(function(e) {
    var x=$(this).val();
    var pag="../Asignaturas_Grupo_Tabla.php?idGrupo="+x;
    $.CargaDinamicaInter(pag, "Asignaturas_Grupo_Tabla.js", "#tbGrupoAsig");
});
$("#CargarAsignaturas").on('click', function(e) {
    var x = $("#Grupos").val();
    var pag="../Asignaturas_Grupo_Tabla.php?idGrupo="+x;
    $.CargaDinamicaInter(pag, "Asignaturas_Grupo_Tabla.js", "#tbGrupoAsig");$("#Grupos").val();
});

(function(){
	var x = $("#Grupos").val();
    var pag="../Asignaturas_Grupo_Tabla.php?idGrupo="+x;
    $.CargaDinamicaInter(pag, "Asignaturas_Grupo_Tabla.js", "#tbGrupoAsig");$("#Grupos").val();
})();