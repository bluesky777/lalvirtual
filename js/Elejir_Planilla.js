
$("#Grupos").change(function(e) {
    var x=$(this).val();
    var pag="../Asignaturas_Grupo_Tabla.php?idGrupo="+x;
    $.CargaDinamicaInter(pag, "uno.js", "tbGrupitos");
});