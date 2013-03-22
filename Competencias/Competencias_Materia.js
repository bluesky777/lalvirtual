$(function(){
    ComprobComp();
})

$(".CompetColDos a").click(VerIndis);


$("#ListaCompetencias ul li").mousemove(function(){
    $(this).css("border", "1px #b099ff solid");
    $(this).css("box-shadow", "inset 0px 0px 2px 2px  #dcd7eb");
})

$("#ListaCompetencias ul li").mouseout(function(){
    $(this).css("border", "1px #CCCCCC  solid");
    $(this).css("box-shadow", "none");
})

$("#ListaCompetencias ul").sortable({ 
    placeholder: "lugarArrastrado" ,
    beforeStop: function() { 
        $.post("../Competencias/Guardar_Competencia.php", $(this).sortable("serialize"), function(dat){ 
            if(dat=="OrdenadoExitoso") {
                j=1;
                $("#ListaCompetencias ul .OrdenComp").each(function(){
                    $(this).html(j++);
                })
            } else {
                alert(dat);
                $(this).sortable('cancel');
            }
        })
    }
});



$("#ListaCompetencias ul").disableSelection();

$(".icoEdiComp").on("click", EdiComp);

$(".icoEliComp").on("click", EliComp);

$("#frmCopiarMat").on("submit", copyComp);

$("#AgregarComp a").on("click", function(e){ 
    AgregarComp(e); 
});

function AgregarComp(e){

    e.stopImmediatePropagation();
    e.preventDefault ();
    
    var idMa=$("#AgregarComp a").attr("id").split(":");
    //Poner el orden en que se pondría la siguiente Competencia
    var OrdenCompFalta=0;
    $(".OrdenComp").each(function(){ OrdenCompFalta = eval($(this).text());  })
    if (OrdenCompFalta==undefined){ OrdenCompFalta=1;  }else{  OrdenCompFalta+=1; }
    
    $("#ContenedorIndicadores").append('<div class="NuevaCompet"  title="Competencia" style="display: none;">\
        <form name="frmNuevaCompet" id="frmNuevaCompet">\
            <label id="TituloNuevaCompet">Nueva competencia</label>\
            <input type="hidden" name="txtOrdenCompet" id="txtOrdenCompet" value="'+OrdenCompFalta+'" />\
            <input type="hidden" name="txtIdMater" id="txtIdMater" value="'+idMa[1]+'" />\
            <input type="hidden" name="txtOperCompet" id="txtOperCompet" value="GuardarNueva" />\
            <input type="hidden" name="txtIDCompet" id="txtIDCompet" value="" />\
            <label for="txtCompetenciaCompet" id="lbNuevCompet">Competencia</label>\
            <textarea name="txtCompetenciaCompet" id="txtCompetenciaCompet"></textarea>\
            <label for="txtPorcentajeCompet" id="lbNuevPorcC">Porcentaje</label>\
            <input type="text" name="txtPorcentajeCompet" id="txtPorcentajeCompet" value="'+ ComprobComp() +'" />\
            <br>\
            <input type="submit" name="submitNuevaCompet" id="submitNuevaCompet" value="Añadir" />\
            <input type="reset" name="cancelNuevaCompet" class="Roj" id="cancelNuevaCompet" value="Cancelar" />\
        </form>\
    </div>');
    
    $("#frmNuevaCompet").on("submit", function(e){ $.submitNuevaComp(e);  });

    $("#cancelNuevaCompet").on("click", function(e){ $(".NuevaCompet").dialog("close");  e.preventDefault(); });
    
    $(".NuevaCompet").dialog({
        height: 210,
        width: 360,
        closeOnEscape: true,
        modal: true,
        show: "folk",
        hide: "scale",
        close: function(){
            ComprobComp();
            $(this).dialog ("destroy").remove();
        }
    });
    $("#txtCompetenciaCompet").focus();
}   

var scriptLoaded = 0;

function VerIndis(){
    $(".CompetColDos .CompSeleccionada").removeClass("CompSeleccionada");
    $(this).addClass("CompSeleccionada");

    urldir="../Indicadores/Indicadores_Competencia.php?idComp="+$.CalcId($(this).attr("id"));

    $.CargaDinamicaInter(urldir, "../Indicadores/Indicadores_Competencia.js", "#ContenedorIndicadores");
    $.CargarCSS("../Indicadores/css/Indicadores_Competencia.css");
    return false;
}
  
function EdiComp(){

    $(".NuevoIndic").dialog ("destroy").remove();

    var idCpEn=$(this).attr("id").indexOf("i");
    var idCpCod=$(this).attr("id").substring(idCpEn+1);

    sCompe="#sCompe"+idCpCod+" a";
    sComPo="#sComPo"+idCpCod;

    $("#ContenedorIndicadores").append('<div class="NuevaCompet"  title="Competencia" style="display: none;">\
        <form name="frmNuevaCompet" id="frmNuevaCompet">\
            <label id="TituloNuevaCompet">Editar competencia</label>\
            <input type="hidden" name="txtOperCompet" id="txtOperCompet" value="GuardarEditado" />\
            <input type="hidden" name="txtIDCompet" id="txtIDCompet" value="'+idCpCod+'" />\
            <label for="txtCompetenciaCompet" id="lbNuevCompet">Competencia</label>\
            <textarea name="txtCompetenciaCompet" id="txtCompetenciaCompet">'+$(sCompe).html()+'</textarea>\
            <label for="txtPorcentajeCompet" id="lbNuevPorcC">Porcentaje</label>\
            <input type="text" name="txtPorcentajeCompet" id="txtPorcentajeCompet" value="'+ $(sComPo).html() +'" />\
            <br>\
            <input type="submit" name="submitNuevaCompet" id="submitNuevaCompet" value="Guardar" />\
            <input type="reset" name="cancelNuevaCompet" class="Roj" id="cancelNuevaCompet" value="Cancelar" />\
        </form>\
    </div>');
    
    $("#frmNuevaCompet").on("submit", function(e){ $.submitNuevaComp(e);  });

    $("#cancelNuevaCompet").on("click", function(e){ $(".NuevaCompet").dialog("close");  e.preventDefault(); });
    
    $(".NuevaCompet").dialog({
        height: 210,
        width: 360,
        closeOnEscape: true,
        modal: true,
        show: "folk",
        hide: "scale",
        close: function(){
            ComprobComp();
            $(this).dialog ("destroy").remove();
        }
    });

    $("#txtOrdenCompet").val($(sComOr).html());
    $("#txtOperCompet").val("GuardarEditado");
    $("#txtIDCompet").val(idCompCod);
    $("#txtCompetenciaCompet").val($(sCompe).html());
    $("#txtPorcentajeCompet").val($(sComPo).html());

    return false;
}

function EliComp(){
    if (confirm("¿Está seguro de que desea eliminar esta competencia?")){

        var idCoEn=$(this).attr("id").indexOf("i");
        var idCoCod=$(this).attr("id").substring(idCoEn+1);
        var idComp = "idComp="+idCoCod;

        $.ajax({
            url: "../Competencias/Eliminar_Competencia.php",
            data: idComp,
            type: "POST",
            success: function(data){
                $("#RespuestaComp").html(data); 
            },
            error:  function(data){
                $("#RespuestaComp").html(data);
            }
        });
        $("#OrdenC_"+idCoCod).remove();
    }
    ComprobComp();
}

function copyComp(){

    if(confirm("¿Seguro desea copiar todas estas competencias a " + $("#SelMateria option:selected").text() + "?")){
        var idMaA=$("input[name='txtIdMat']").val();
        Datos="idMatNew=" + $("#SelMateria").val() + "&idMatAnt="+idMaA+"&txtPeriodo="+ $("#txtPeriodo").val();
    } else {
        return false;
    }

    $.ajax({
        type: "POST",
        url: "../Copiar_Competencia.php",
        data: Datos,
        success: function(resp){
            $('#RespCopyComp').html(resp);
            return false;
        },
        beforeSend: function(){
            $('#RespCopyComp').html("<img src='../img/loader-mini.gif'/><br/>");
        },
        error: function(data){
            $('#RespCopyComp').html("Hubo problemas de red " + data);
        }
    })
    return false;
}



/* **** FUNCION GUARDAMOS COMPETENCIA *****  */
var sComOr="", sCompe="", sComPo="", sCompCr=""; //Para la edicion de las copetencias

$.submitNuevaComp = function(e){
    e.stopImmediatePropagation();
    e.preventDefault ();

    $("#submitNuevaCompet").css("display", "none");
    $("#cancelNuevaCompet").css("display", "none");

    if($("#txtCompetenciaCompet").val()==""){
        $("#txtCompetenciaCompet").focus();
        alert("Debe copiar la competencia");
        return;
    } else if ($("#txtPorcentajeCompet").val()=="" || $("#txtPorcentajeCompet").val()<0 || $("#txtPorcentajeCompet").val()>100){
        $("#txtPorcentajeCompet").focus();
        alert("El porcentaje debe ser del 0 hasta el 100");
        return;
    }

    if($("#submitNuevaCompet").val()=="Añadir"){ /* Pregunto si está agregando nuevo o guardando un editado */

        $.ajax({
            type: 'POST',
            url: '../Competencias/Guardar_Competencia.php',
            data: $("#frmNuevaCompet").serialize(),
            success: function(data){
                
                var Respus=data.split(":");

                if(Respus[0]=="Exitoso"){
                    $("#RespuestaComp").html(Respus[0]);
                    showCompetAgregada(Respus[1]);
                } else {
                    $("#RespuestaComp").html(Respus[0]);
                }
            },
            beforeSend: function(){
                $('#RespuestaComp').html("<img src='../img/loader-mini.gif'/><br/>");
            },
            error: function(data){
                alert(data);
                $('#RespuestaComp').html("Hubo problemas en la red " + data);
                return;
            }
        }); 
    } else if($("#submitNuevaCompet").val()=="Guardar"){
        $.ajax({
            type: 'POST',
            url: '../Competencias/Guardar_Competencia.php',
            data: $("#frmNuevaCompet").serialize(),
            success: function(data){

                $(sComOr).html($("#txtOrdenCompet").val());
                $("#txtOperCompet").val();
                $(sCompe).html($("#txtCompetenciaCompet").val());
                $(sComPo).html($("#txtPorcentajeCompet").val());

                $("#RespuestaComp").html(data);
                
                return false;
            },
            beforeSend: function(){
                $('#RespuestaComp').html("<img src='../img/loader-mini.gif'/><br/>");

            },
            error: function(data){
                $('#RespuestaComp').html("Hubo problemas en la red " + data);
                return false;
            }
        });
    }

    setTimeout(function(){
        $(".NuevaCompet").dialog("close");
    }, 2000);
    
    
    return false;

}

function showCompetAgregada(NuevoIdComp){
    tOrdenCompet = $("#txtOrdenCompet").val();
    tCompetenciaCompet = $("#txtCompetenciaCompet").val();
    tPorcentajeCompet = $("#txtPorcentajeCompet").val();


    var f = new Date();
    fec=f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();

    $("#UlCompetencias ul").append('<li id="OrdenC_'+NuevoIdComp+'">\
        <div class="CompetColUno"><span class="OrdenYcompet">\
            <span class="OrdenComp" id="sComOr'+NuevoIdComp+'">'+ tOrdenCompet+'</span>\
            <span class="Competen"  id="sCompe'+NuevoIdComp+'" title="'+tCompetenciaCompet+'">\
                <a href="javascript:void(0);">'+tCompetenciaCompet+'</a>\
            </span></span>\
            <span class="PorComp" id="sComPo'+NuevoIdComp+'" title="La competencia vale un '+tPorcentajeCompet+'% de la materia">'+tPorcentajeCompet+'</span>\
            <span class="FechCre" id="sCompCr'+NuevoIdComp+'" title="Fecha Creación: '+fec+'">'+fec+'</span>\
            <span class="OptCompet">\
                <span class="icoEliComp" id="Eli'+NuevoIdComp+'" title="Eliminar competencia">\
                        <img src="../img/icono_eliminar.gif" width="16" height="21" style="cursor:pointer" title="Eliminar competencia" />\
                </span>\
                <span class="icoEdiComp" id="Edi'+NuevoIdComp+'" title="Editar competencia">\
                    <img src="../img/icono_editar.png" width="20" height="22" />\
                </span>\
            </span>\
         </div>  <!-- Fin Columna Uno -->\
         <div class="CompetColDos">\
            <span>\
                <a href="javascript:void(0);" id="idComp:'+NuevoIdComp+'" title="Mostrar indicadores">\
                    <img src="../img/FlechaVerIndic.png">\
                </a>\
            </span>\
         </div>\
        </li>');
    

    $(".icoEdiComp").on("click", EdiComp);
    $(".icoEliComp").on("click", EliComp);
    $(".CompetColDos a").on("click", VerIndis);
}



$("#EstPorcComp").on("click", function(){
    ComprobComp();
})

 function ComprobComp(){
    //Poner el porcentaje que hace falta
    var PorcCp=0; 
    $(".PorComp").each(function(){ PorcCp += eval($(this).text()); });
    PorcCpSobra = PorcCp - 100;

    if (PorcCpSobra > 0) {
        $("#EstPorcComp").html("Está sobrando " + PorcCpSobra + "%");
        $("#EstPorcComp").css("display", "block");
        return "0";
    } else if(PorcCpSobra < 0){
        $("#EstPorcComp").html("Aun falta un " + Math.abs(PorcCpSobra) + "%");
        $("#EstPorcComp").css("display", "block");
        return Math.abs(PorcCpSobra);
    } else {
        $("#EstPorcComp").html("");
        $("#EstPorcComp").css("display", "none");
        return "0";
    }
}