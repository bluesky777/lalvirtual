$(document).ready(function(){
 $(function(){   
    var UsuId = $("#hdIdUsu").val();
    var UsuTipo = $("#hdTipoUsu").val();

    $.CargaDinamica("Galaxia/Galaxia.php/My=1", "Galaxia/Galaxia.js", "Galaxia/Galaxia.css");
    

    $(".OptInicio").click(function(e) {
        document.location.reload();
    });

    
    $(".OptPuestos > a").on("click", function(e){
        var urldir= "../Informes/Menu_Puestos.php?TipoUsu="+UsuTipo;
        
        capatrans = $("<div  class='capaTransparente'><img src='../img/loader-mini.gif'/>").prependTo("#ContLoaded");
        capatrans.animate({'opacity': 0.5});

        $.CargarCSS("../Informes/css/Menu_Puestos.css");
        $.CargarCSS("../Informes/css/Puestos_Alumnos.css");

        $("#ContLoaded").load(urldir, function(){
            //$.ajaxSetup({cache: true});
            
            $.getScript("../Informes/Menu_Puestos.js", function(){
                //$.ajaxSetup({cache: false});
                
                capatrans.animate({'opacity': 0}, function(){
                    $(this).remove();
                })
                //$("#ContLoaded").css("opacity", "1");
            });
        })
        $("html, body").animate({scrollTop:"0px"});
        e.preventDefault();
    });    
    $(".CargarMaterias").on("click", function(e){
        var idUs=0;

        if(UsuTipo == 1){
            idUsw = $(this).attr("id").split(":");
            idUs = idUsw[1];
        }else{
            idUs=UsuId;
        }

        $.CargaDinamica("../Materias/Materias_Usuario.php?idUsu="+idUs, "../Materias/Materias_Usuario.js", "../Materias/css/Materias_Usuario.css");
        e.preventDefault();
    });
    $(".OptPrinEdNtAlMenu > a").on("click", function(e){
        var urldir="../Ediciones/Alu_Ver_Ind.php";
        $.CargaDinamica(urldir, "../Ediciones/Alu_Ver_Ind.js", "../Ediciones/Alu_Ver_Ind.css");
        $("html, body").animate({scrollTop:"0px"});
        e.preventDefault();
    });
    $(".OptPrinAnuncios").on("click",function(e){
        var urldir=$(this).attr('id');
        $.CargaDinamica(urldir, "../js/Anuncios.js", "../css/Anuncios.css");
        e.preventDefault();
    });  
    $(".OptPrinNotificaciones").click(function(){
        
        var urldir=$(this).attr('id');
        $.CargaDinamica(urldir, "../css/Notificaciones.js", "../css/Notificaciones.css");
        return false;
    });        
    $(".OptPrinPlanill > a").click(function(){
        
        var urldir=$(this).attr('id');
        $.CargaDinamica(urldir, "../css/algo.js", "../css/algo.css");
        return false;
    });        
    $(".OptPrinEditPer > a").on("click", function(e){
        $("html, body").animate({scrollTop:"0px"});
        urldir = "../Periodos/Editar_Periodo.php";
        $.CargaDinamica(urldir, "../Periodos/js/Editar_Periodo.js", "../Periodos/css/Crear_Per.css");
        e.preventDefault();
    });


        
    $(".myPer").click(function(){
        if($(".PeriodoOpciones").css("display")=="none"){
            $(".myPer").addClass("MenuBarraPresionado");
            $(".PeriodoOpciones").css("display", "block");
        } else {
            $(".myPer").removeClass("MenuBarraPresionado");
            $(".PeriodoOpciones").css("display", "none");
        }
    });
	 

    $(".Period").on("click", function(e){

        var dat=$(this).attr('id');
        var url = 'Cambiar_Periodo.php?PerSel=' + dat;
        $.GuardarGet(url, ".myPer", function(){
            // $("#ContLoaded").html("Has cambiado el periodo.");
            // $(".myPer").removeClass("MenuBarraPresionado");
            // $(".PeriodoOpciones").css("display", "none");
            window.location.reload();
        });
        e.preventDefault();
    });
    
    //******************************************************************************
    //**************************  BUSCAR  ******************************************
    //******************************************************************************
    var cache = {}, lastXhr;

    $("#txtBuscardorPeople").autocomplete({
        source: function(request, response) {
            var term = request.term;
            if ( term in cache) {
                response ( cache [term]);
                return;
            }
            lastXhr = $.getJSON("buscar.php", request, function(data, status, xhr){
                cache[term] = data;
                if(xhr === lastXhr){ response(data); }
            })
        },
        select: function(event, ui){
            //url="galaxia.php?IdPerson="+ui.item.id;
            //$.CargaDinamicaInterDir(url, "../Ediciones/Alu_Ver_Ind_Det.js", ".CntInds");
            alert("Dentro de poco podremos compartir galaxias entre amigos. Esperalo!!");
        },
        matchContains: true
    })

    $("#txtBuscardorPeople").on("focusout", function(){
        $(this).removeClass("ui-autocomplete-loading");
    })
    //******************************************************************************
    //************************** FIN BUSCAR  ***************************************
    //******************************************************************************


    $("#OptUsuario a").click(function(){
        var urldir=$(this).attr('id');
        $.CargaDinamica(urldir, "../js/MyUsu.js", "../css/MyUsu.css");
        return false;
    });
    
    $(".OptVerBoletin").on("click", function(e){
        var urldir=$(this).attr('id');
        $.CargaDinamica(urldir, "../js/Elejir_Boletin.js", "../css/Elejir_Boletin.css");
        $("html, body").animate({scrollTop:"0px"});
        e.preventDefault();
    });
    $(".OptVerBoletinFinal").on("click", function(e){
        var urldir=$(this).attr('id');
        $.CargaDinamica(urldir, "../Informes/Boletin_Final/js/Elejir_Boletin.js", "../css/Elejir_Boletin.css");
        $("html, body").animate({scrollTop:"0px"});
        e.preventDefault();
    });
    $(".OptVerBoletinAl").on("click", function(e){
        var urldir=$(this).attr('id');
        window.open(urldir, "_blank");
        e.preventDefault();
    });

    $("#OptElePlan a").on("click", function(e){
        var urldir=$(this).attr('id');
        $.CargaDinamica(urldir, "../js/Elejir_Planilla.js", "../css/Elejir_Planilla.css");
        $("html, body").animate({scrollTop:"0px"});
        e.preventDefault();
    });
    
    $(".OptGrupos").click(function(){
        var urldir=$(this).attr('id');
        $.CargaDinamica(urldir, "../js/Grupos.js", "../css/grupos.css");
        return false;
    });
    $(".Opt2Alum").click(function(){
        var urldir=$(this).attr('href');
        $.CargaDinamica(urldir, "../js/Alumnos.js", "../css/Alumnos.css");
        return false;
    });
    $(".Opt2NotAl").click(function(e){  
        var urldir="../Ediciones/Alu_Ver_Ind";
        $.CargaDinamica(urldir+".php", urldir+".js", urldir+".css");
        $("html, body").animate({scrollTop:"0px"});
        e.preventDefault();
    });
    $(".Opt2Export").on("click", function(e){  
        var urldir="../Ediciones/Exportar/Exportar.php";
        $.CargaDinamica(urldir,"../Ediciones/Exportar/Exportar.js", "../Ediciones/Exportar/Exportar.css");
        $("html, body").animate({scrollTop:"0px"});
        e.preventDefault();
    });
    $(".Opt2Usu").click(function(){
        
        var urldir=$(this).attr('href');
        $.CargarCSS("../css/Usuarios.css");

        $("#ContLoaded").load(urldir, function(){

            $.ajaxSetup({cache: true});
            $.getScript("../Usuarios.js");
            $.getScript("../js/jquery.dataTables.min.js", function(){
                $.getScript("js/jquery.jeditable.js", function(){
                    $.ajaxSetup({cache: false});
                });
            });
        })

        var css='@import "../css/demo_page.css";'+
                '@import "../css/demo_table.css";'
        $("style").append(css);  

        return false;
    });

    $("#CrAnu").click(function(){
        
        if($("#tabsAnun").length == 0){

            capatrans = $("<div  class='capaTransparente'><img src='../img/loader-mini.gif'/>").prependTo("#CuadroAnunciar");
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
})

jQuery.CargaDinamica = function(myurl, myjs, mycss){

    capatrans = $("<div  class='capaTransparente'><img src='../img/loader-mini.gif'/>").prependTo("#ContLoaded");
    capatrans.animate({'opacity': 0.5});
    
    $.CargarCSS(mycss);

    $("#ContLoaded").load(myurl, function(){
        $.ajaxSetup({cache: true});
        
        $.getScript(myjs, function(){
            $.ajaxSetup({cache: false});
            
            capatrans.animate({'opacity': 0}, function(){
                $(this).remove();
            })
            //$("#ContLoaded").css("opacity", "1");
        });
    })
}

jQuery.CargaDinamicaInter = function(myurl, myjs, mycaja){
    capatrans = $("<div  class='capaTransparente'><img src='../img/loader-mini.gif'/>").prependTo(mycaja);
    capatrans.animate({'opacity': 0.5});

    $(mycaja).load(myurl, function(){
        
        $.ajaxSetup({cache: true});
        $.getScript("../js/"+myjs, function(){
            $.ajaxSetup({cache: false});
            
            capatrans.animate({'opacity': 0}, function(){
                $(this).remove();
            })
            //$("#ContLoaded").css("opacity", "1");
        });
    })
}
jQuery.CargaDinamicaInterDir = function(myurl, myjs, mycaja){
    capatrans = $("<div  class='capaTransparente'><img src='../img/loader-mini.gif'/>").prependTo(mycaja);
    capatrans.animate({'opacity': 0.5});

    $(mycaja).load(myurl, function(){

        $.ajaxSetup({cache: true});
        $.getScript(myjs, function(){
            $.ajaxSetup({cache: false});
            
            capatrans.animate({'opacity': 0}, function(){
                $(this).remove();
            })
            //$("#ContLoaded").css("opacity", "1");
        });
    })
}
    
jQuery.CargaJS = function(myjs){
    $.ajaxSetup({cache: true});
    $.getScript(myjs, function(){
        $.ajaxSetup({cache: false});
    });
}
 
jQuery.CargarCSS = function(mycss){
    sw=0;
    $("link").each(function(){
        var actcss = $(this).attr("href");
        if( actcss == mycss){
            sw=1;
        }
    });

    if (sw == 0){
        var css = jQuery("<link>");
        css.attr({
            rel:  "stylesheet",
            type: "text/css",
            href: mycss
        });
        $("head").append(css);        
    }
}
jQuery.GuardarGet = function(myPag, myCnt, aejecutar){

    $.ajax({
        type: 'GET',
        url: myPag,
        success: function(data){
                $(myCnt).html(data);
                aejecutar();
        },
        beforeSend: function(){
                $(myCnt).html("<img src='../img/loader-mini.gif'/>");
        },
        error: function(data){
                $(myCnt).html("Lo sentimos, por favor intente de nuevo.");
        }
    });
}

jQuery.CalcId = function(valor){
    MyArray = valor.split(":");
    id=MyArray[1];
    return id;
}

 
});

