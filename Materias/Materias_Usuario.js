$(function(){
    var UsuId = $("#hdIdUsu").val();
    var UsuTipo = $("#hdTipoUsu").val();

    $(".AnunciarNotas").on("click", function(){
        
        if($("#tabsAnun").length == 0){
            
            capatrans = $("<div class='capaTransparente'><img src='../img/loader-mini.gif'/>").prependTo("#CuadroAnunciar");
            capatrans.animate({'opacity': 0.5});

            $("#CuadroAnunciar").load("../Anunciar.php", function(){

                $("#CuadroAnunciar").dialog({
                    height: 230,
                    width: 370,
                    closeOnEscape: true,
                    modal: true,
                    show: "folk",
                    hide: "scale"
                });
                //$.ajaxSetup({cache: true});
                $.getScript("../js/Anunciar.js", function(){
                    $.ajaxSetup({cache: false});
                    
                    $("#tabsAnun").tabs("select", 1);

                    capatrans.animate({'opacity': 0}, function(){
                        $(this).remove();
                    })
                    //$("#ContLoaded").css("opacity", "1");
                });
            })
        } else {
            $("#CuadroAnunciar").dialog();
            $("#tabsAnun").tabs("select", 1);
        }
        $("#RespAnun").html("");
        return false;
    });

    $(".MaterCompetencia").on("click", function(e){
        id = $.CalcId( $(this).attr("id") );
        urldir="../Competencias/Competencias_Materia.php?idMatG="+ id;
        $.CargaDinamica(urldir, "../Competencias/Competencias_Materia.js", "../Competencias/css/Competencias_Materia.css");
        e.preventDefault();
    });
    $(".MaterAusencia").on("click", function(){
        urldir =$(this).attr("id");
        $.CargaDinamica(urldir, "../js/Ausencias_Materia.js", "../css/Ausencias_Materia.css");
        return false;
    });
    $(".MaterSemestral").on("click", function(){
        urldir=$(this).attr("id");
        $.CargaDinamica(urldir, "../js/Semestral_Alumno.js", "../css/Semestral_Alumno.css");
        return false;
    });
    $(".MaterActiv").on("click", function(e){
        urldir=$(this).attr("id");
        $.CargarCSS("../Actividades/css/Actividades.css");
        $.CargaDinamicaInterDir(urldir, "../Actividades/Activ.js", "#ContLoaded");
        e.preventDefault();
    });

})
