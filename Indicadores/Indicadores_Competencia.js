$(function(){
    ComprobInd();
})

$("#ListadoIndicadores ul").sortable({ 
    placeholder: "lugarArrastrado" ,
    beforeStop: function() { 

        $.post("../Indicadores/Guardar_Indicador.php", $(this).sortable("serialize"), function(dat){ 

            j=1;
            $("#ListadoIndicadores ul .OrdenIndic").each(function(){
                $(this).html(j++);
            })

        })
    }
});

////////////  OPCIONES PEGAR INDICADORES    //////////////////////
$(".SubOpcionIndic #PegarVarInd").click(function(){
    $(".PegarIndicadores").css("display", "block");
});



////////////  FIN  OPCIONES PEGAR INDICADORES    //////////////////////


$("#ListadoIndicadores ul").disableSelection();

$(".icoEdiIndic").on("click", EdiIndic);

$(".icoEliIndic").on("click", EliIndic);



/**  EVENTO CUANDO DIGITE INDICADORES PARA PEGAR *** */

$("#txtIndicadorIndicPeg").keyup(TeclaIndicPeg);

/**  FIN EVENTO CUANDO DIGITE INDICADORES PARA PEGAR *** */


$("#cancelNuevoIndicPeg").click(function(){
    $(".PegarIndicadores").css("display", "none");
});

$("#submitNuevoIndicPeg").submit(function(){
    alert("Lo sentimos, aun en construcción.");
    return false;
});


$("#AgregarIndic a").on ("click", function (e) {
    $.AgregarIndic(e);
});

$("#OpcionesIndic .OpcionesGeneIndic").click(function(){

    if($(".OpcionesGeneIndic .SubOpcionesIndic").css("display")=="none"){
        $("#OpcionesIndic .FondoOpt").addClass("Mostrando");
        $(".OpcionesGeneIndic .SubOpcionesIndic").css("display", "block");
    }else{
        $("#OpcionesIndic .FondoOpt").removeClass("Mostrando");
        $(".OpcionesGeneIndic .SubOpcionesIndic").css("display","none");
    }
})


$.AgregarIndic = function(e){
    e.stopImmediatePropagation();
    e.preventDefault ();

    var idCp= $("#AgregarIndic a").attr("id").split(":");
    //Poner el orden en que se pondría el siguiente indicador
    var OrdenIndicFalta=0;
    $(".OrdenIndic").each(function(){ OrdenIndicFalta = eval($(this).text());  });
    if (OrdenIndicFalta==undefined){  OrdenIndicFalta=1; }else{  OrdenIndicFalta+=1;  }

    $("#ContenedorIndicadores").append('<div class="NuevoIndic" title="Indicador" style="display: none;"> \
    <form name="frmNuevoIndic" id="frmNuevoIndic">\
        <label id="TituloNuevoIndic">Nuevo indicador</label>\
        <input type="hidden" name="txtOrdenIndic" id="txtOrdenIndic" value="'+ OrdenIndicFalta +'" />\
        <input type="hidden" name="txtIdCompIndic" id="txtIdCompIndic" value="'+ idCp[1] +'" />\
        <input type="hidden" name="txtOperIndic" id="txtOperIndic" value="GuardarNuevo" />\
        <label for="txtIndicadorIndic" id="lbNuevIndicador" class="label1">Indicador</label>\
        <textarea name="txtIndicadorIndic" id="txtIndicadorIndic"></textarea>\
        <label for="txtPorcentajeIndic" id="lbNuevPorc" class="label1">Porcentaje</label>\
        <input type="text" name="txtPorcentajeIndic" id="txtPorcentajeIndic" class="input1" value="'+ ComprobInd() +'" />\
        <label for="txtDefecIndic" id="lbNuevDef" class="label1">Nota por defecto</label>\
        <input type="text" name="txtDefecIndic" id="txtDefecIndic" value="100" class="input1" />\
        <br>\
        <label for="txtFechaIniIndic" id="lbNuevFecIni" class="label2">Desde</label>\
        <input type="text" name="txtFechaIniIndic" id="txtFechaIniIndic" class="input1 dattim" />\
        <br>\
        <label for="txtFechaFinIndic" id="lbNuevFecFin" class="label2">hasta</label>\
        <input type="text" name="txtFechaFinIndic" id="txtFechaFinIndic" class="input1 dattim" /> \
        <br>\
        <input type="submit" name="submitNuevoIndic" id="submitNuevoIndic" value="Añadir" />\
        <input type="reset" name="cancelNuevoIndic" id="cancelNuevoIndic" class="Roj" value="Cancelar" /> \
    </form>\
</div>');

    $("#cancelNuevoIndic").on("click", function(e){
        $(".NuevoIndic").dialog("close");
        e.preventDefault();
    });
    $("#frmNuevoIndic").on("submit", function(e){
        $.submitNuevoIndic();
        e.preventDefault();
    });

    $("#txtFechaIniIndic").datetimepicker({
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
        monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        showWeek: true,
        stepMinute: 15,
        ampm: true,
        gotoCurrent: true,
        hourMin: 4,
        hourMax: 22,
        addSliderAccess: true,
        sliderAccessArgs: { touchonly: false }
    })
    $("#txtFechaFinIndic").datetimepicker({
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
        monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        showWeek: true,
        stepMinute: 10,
        ampm: true,
        gotoCurrent: true,
        hourMin: 4,
        hourMax: 23,
        addSliderAccess: true,
        sliderAccessArgs: { touchonly: false }
    })

    $(".NuevoIndic").dialog({
        height: 300,
        width: 370,
        closeOnEscape: true,
        modal: true,
        show: "folk",
        hide: "scale",
       close: function(){
            ComprobInd();
            $(this).dialog ("destroy").remove();
        }
    });
    
    $("#txtIndicadorIndic").focus();

}   




var EsperandoAct=false; //esperando a que actualice
    
function TeclaIndicPeg(){

    if(!EsperandoAct){

        EsperandoAct=true;

        var TextoOriginal=$(this).val();
        var TextoRestante=TextoOriginal;

        var contPeg=0;

        //Poner el orden en que se pondría el siguiente indicador
        var OrdenIndicFalta;
        $(".OrdenIndic").each(function(){
            OrdenIndicFalta = eval($(this).text());
        })

        while(TextoRestante.length>0 ){

            ++contPeg;
            ++OrdenIndicFalta;

            var NomPegOr="NuevIndicPegOr" + contPeg;
            var NomPegInd="NuevIndicadorPeg" + contPeg;

            var IndiceSalto=TextoRestante.indexOf("\n");

            document.getElementById(NomPegOr).childNodes[0].nodeValue=OrdenIndicFalta;

            if (IndiceSalto!=-1){
                document.getElementById(NomPegInd).childNodes[0].nodeValue=TextoRestante.substring(-2, IndiceSalto);
                TextoRestante=TextoRestante.substring(IndiceSalto+1);
            }else{

                document.getElementById(NomPegInd).childNodes[0].nodeValue=TextoRestante.substring(-2);
                TextoRestante="";
            }


        }

        var PorcIndicMientras=0; //Poner el porcentaje que hace falta

        $(".PorcIndic").each(function(){
            PorcIndicMientras += eval($(this).text());
        })

        if (PorcIndicMientras>100) {PorcIndicMientras=100}

        PorcIndicFalta=Math.abs(PorcIndicMientras-100);

        if(PorcIndicFalta>0) {

            for(i=0; i<=contPeg-2; i++){

                var Pedazo=0;

                var NomPegPo="NuevIndicPegPo" + (i+1);

                var residuoPorc=PorcIndicFalta % contPeg; //Para saber si la division es exacta
                divFal=contPeg-i;
                Pedazo=PorcIndicFalta / divFal;
                //alert(PorcIndicFalta +" / "+ divFal+" la i: "+i+" y pedazo es "+Pedazo);

                if (residuoPorc > 0){
                    Pedazo=Math.ceil(Pedazo);
                }

                document.getElementById(NomPegPo).childNodes[0].nodeValue=Pedazo;

                PorcIndicFalta = PorcIndicFalta - Pedazo;

            } 

            NomPegPo="NuevIndicPegPo" + contPeg;

            document.getElementById(NomPegPo).childNodes[0].nodeValue=PorcIndicFalta;

        } else{
            //alert("No queda porcentaje para distribuir, aunque luego puedes usar \n la opción Distribuir porcentajes auntomáticamente");
        }
        setTimeout(function(){EsperandoAct=false;$("#txtIndicadorIndicPeg").keyup();} , 3000);
    } 

}
    
    
function EliIndic(){
    if(confirm("¿Está seguro que desea eliminar este indicador?")){
        var idIndicEn=$(this).attr("id").indexOf("i");
        var idIndicCod=$(this).attr("id").substring(idIndicEn+1);
        var idInd = "idInd="+idIndicCod;
        $.ajax({
            url: "../Indicadores/Eliminar_Indicador.php",
            data: idInd,
            type: "POST",
            success: function(resp){
                $("#OrdenI_"+idIndicCod).remove();
                ComprobInd();
                $("#RespuestaInd").html(resp);
            }  
        });

    }
}


/* **** FUNCION GUARDAMOS INDICADOR *****  */
var sIndOr="", sIndIn="", sIndPo="", sIndDe="", sIndNi="", sIndFi=""; //Para la edicion de los indicadores

$.submitNuevoIndic = function(){

    if($("#txtIndicadorIndic").val()==""){
        $("#txtIndicadorIndic").focus();
        alert("Debe copiar el indicador");
        return false;
    } else if($("#txtFechaIniIndic").val()==""){
        $("#txtFechaIniIndic").focus();
        alert("Debe seleccionar la fecha de inicio");
        return false;
    }else if($("#txtFechaFinIndic").val()==""){
        $("#txtFechaFinIndic").focus();
        alert("Debe seleccionar la fecha de plazo");
        return false;
    } else if($("#txtFechaFinIndic").val()==""){
        $("#txtFechaFinIndic").focus();
        alert("Debe seleccionar la fecha de plazo");
        return false;
    }

    $("#submitNuevoIndic").css("display", "none");
    $("#cancelNuevoIndic").css("display", "none");

    if($("#submitNuevoIndic").val()=="Añadir"){ /* Pregunto si está agregando nuevo o guardando un editado */

        $.ajax({
            type: 'POST',
            url: '../Indicadores/Guardar_Indicador.php',
            data: $("#frmNuevoIndic").serialize(),
            success: function(data){

                var Respus=data.split(":");
                if(Respus[0]=="Exitoso"){
                    $("#RespuestaInd").html(Respus[0]);
                    $.showIndicAgregado(Respus[1]);
                } else {
                    $("#RespuestaInd").html(Respus[0]);
                }
                return false;
            },
            beforeSend: function(){
                $('#RespuestaInd').html("<img src='../img/loader-mini.gif'/><br/>");
            },
            error: function(data){
                $('#RespuestaInd').html("Hubo problemas en la red " + data);
                return false;
            }
        }); 

    } else if($("#submitNuevoIndic").val()=="Guardar"){

        $.ajax({
            type: 'POST',
            url: '../Indicadores/Guardar_Indicador.php',
            data: $("#frmNuevoIndic").serialize(),
            success: function(data){
                $(sIndOr).html($("#txtOrdenIndic").val());
                $(sIndIn).html($("#txtIndicadorIndic").val());
                $(sIndPo).html($("#txtPorcentajeIndic").val());
                $(sIndDe).html($("#txtDefecIndic").val());
                $(sIndNi).html($("#txtFechaIniIndic").val());
                $(sIndFi).html($("#txtFechaFinIndic").val());

                $("#RespuestaInd").html(data);
                
                return false;
            },
            beforeSend: function(){
                $('#RespuestaInd').html("<img src='../img/loader-mini.gif'/><br/>");

            },
            error: function(data){
                $('#RespuestaInd').html("Hubo problemas en la red " + data);
                return false;
            }
        });
    }
    setTimeout(function(){
        $(".NuevoIndic").dialog("close");
    }, 2000);
    
    $("#txtIndicadorIndicPeg").live("keyup", TeclaIndicPeg);
    
    return false;
}

$.showIndicAgregado=function(NuevoIdInd){

    tIdCompIndic = $("#txtIdCompIndic").val();
    tIndicadorIndic = $("#txtIndicadorIndic").val();
    tPorcentajeIndic = $("#txtPorcentajeIndic").val();
    tDefecIndic = $("#txtDefecIndic").val();
    tFechaIniIndic = $("#txtFechaIniIndic").val();
    tFechaFinIndic = $("#txtFechaFinIndic").val();
    tOrdenIndic = $("#txtOrdenIndic").val(); 


    //var NuevoIdInd=5887; //Id que viene de la BD despues de agregar el nuevo.
    $('#ListadoIndicadores ul').append('<li id="OrdenI_' + NuevoIdInd +'"><span class="OrdenIndic" id="sIndOr'+NuevoIdInd+'">' + tOrdenIndic +'</span> \
        <div class="ColumnaCentral" style="width:320px;">\
        <span class="Indicador" title="' + tIndicadorIndic +'"><a href="../Alumnos_Notas.php?idComp='+tIdCompIndic+'" id="sIndIn'+NuevoIdInd+'">' + tIndicadorIndic +'</a>\
        </span> \
        <span class="PorcIndic" title="Este indicador vale un ' + tPorcentajeIndic +'% de la competencia seleccionada" id="sIndPo'+NuevoIdInd+'">' + tPorcentajeIndic +'</span>\
        <span class="NotaDefecIndic" id="sIndDe'+NuevoIdInd+'">' + tDefecIndic +'</span>\
        <span class="FechIniIndic" id="sIndNi'+NuevoIdInd+'">' + tFechaIniIndic +'</span>\
        <span class="FechFinIndic" id="sIndFi'+NuevoIdInd+'">' + tFechaFinIndic +'</span>\
        <span class="FechCreIndic" id="sIndCr'+NuevoIdInd+'">' + tFechaIniIndic +'</span> \
        </div><div class="ColumnaDere">\
        <div class="OptIndic">\
        <span class="icoEliIndic" id="Eli'+NuevoIdInd+'" title="Eliminar indicador">\
            <img src="../img/icono_eliminar.gif" width="17" height="22" />\
        </span>\
        <span class="icoEdiIndic" id="Edi'+NuevoIdInd+'" title="Editar indicador">\
            <img src="../img/icono_editar.png" width="17" height="22" />\
        </span>\
        </div><!-- Fin de las opciones de un indicador -->\
        </div></li>');

    var ediIndT="Edi"+NuevoIdInd;
    var eliIndT="Eli"+NuevoIdInd;

    $(".icoEdiIndic").on("click", EdiIndic);
    $(".icoEliIndic").on("click", EliIndic);
}

/* **** FIN DE GUARDAMOS INDICADOR *****  */
    
function EdiIndic(){

    $(".NuevoIndic").dialog ("destroy").remove();

    var idIndicEn=$(this).attr("id").indexOf("i");
    var idIndicCod=$(this).attr("id").substring(idIndicEn+1);

    sIndOr="#sIndOr"+idIndicCod;
    sIndIn="#sIndIn"+idIndicCod;
    sIndPo="#sIndPo"+idIndicCod;
    sIndDe="#sIndDe"+idIndicCod;
    sIndNi="#sIndNi"+idIndicCod;
    sIndFi="#sIndFi"+idIndicCod;

    $("#ContenedorIndicadores").append('<div class="NuevoIndic" title="Indicador" style="display: none;"> \
    <form name="frmNuevoIndic" id="frmNuevoIndic">\
        <label id="TituloNuevoIndic">Editar indicador</label>\
        <input type="hidden" name="txtOrdenIndic" id="txtOrdenIndic" value="'+ $(sIndOr).html() +'" />\
        <input type="hidden" name="txtOperIndic" id="txtOperIndic" value="GuardarEditado" />\
        <input type="hidden" name="txtIDIndic" id="txtIDIndic" value="'+idIndicCod+'" />\
        <label for="txtIndicadorIndic" id="lbNuevIndicador" class="label1">Indicador</label>\
        <textarea name="txtIndicadorIndic" id="txtIndicadorIndic">'+ $(sIndIn).html() +'</textarea>\
        <label for="txtPorcentajeIndic" id="lbNuevPorc" class="label1">Porcentaje</label>\
        <input type="text" name="txtPorcentajeIndic" id="txtPorcentajeIndic" class="input1" value="'+ $(sIndPo).html() +'" />\
        <label for="txtDefecIndic" id="lbNuevDef" class="label1">Nota por defecto</label>\
        <input type="text" name="txtDefecIndic" id="txtDefecIndic" value="'+ $(sIndDe).html() +'" class="input1" />\
        <br>\
        <label for="txtFechaIniIndic" id="lbNuevFecIni" class="label2">Desde</label>\
        <input type="text" name="txtFechaIniIndic" id="txtFechaIniIndic" class="input1 dattim" value="'+ $(sIndNi).html() +'" />\
        <br>\
        <label for="txtFechaFinIndic" id="lbNuevFecFin" class="label2">hasta</label>\
        <input type="text" name="txtFechaFinIndic" id="txtFechaFinIndic" class="input1 dattim" value="'+ $(sIndFi).html() +'" /> \
        <br>\
        <input type="submit" name="submitNuevoIndic" id="submitNuevoIndic" value="Guardar" />\
        <input type="reset" name="cancelNuevoIndic" id="cancelNuevoIndic" class="Roj" value="Cancelar" /> \
    </form>\
</div>');

    $("#cancelNuevoIndic").on("click", function(e){
        $(".NuevoIndic").dialog("close");
        e.preventDefault();
    });
    $("#frmNuevoIndic").on("submit", function(e){
        $.submitNuevoIndic();
        $(".NuevoIndic").dialog("close");
        e.preventDefault();
    });
    $(".NuevoIndic").dialog({
        height: 300,
        width: 370,
        closeOnEscape: true,
        modal: true,
        show: "folk",
        hide: "scale",
	   close: function (){
            ComprobInd();
            $(this).dialog ("destroy").remove();
        }
    });

    $("#txtFechaIniIndic").datetimepicker({
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
        monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        showWeek: true,
        stepMinute: 15,
        ampm: true,
        gotoCurrent: true,
        hourMin: 4,
        hourMax: 22,
        addSliderAccess: true,
        sliderAccessArgs: { touchonly: false }
    })
    $("#txtFechaFinIndic").datetimepicker({
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
        monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        showWeek: true,
        stepMinute: 10,
        ampm: true,
        gotoCurrent: true,
        hourMin: 4,
        hourMax: 23,
        addSliderAccess: true,
        sliderAccessArgs: { touchonly: false }
    })
}
/*  ** Funciones para los eventos *** */
$("#EstPorcId").on("click", function(){
    ComprobInd();
})

function ComprobInd(){
    //Poner el porcentaje que hace falta
    var PorcId=0; 
    $(".PorcIndic").each(function(){ PorcId += eval($(this).text()); });
    PorcIdSobra = PorcId - 100;

    if (PorcIdSobra > 0) {
        $("#EstPorcId").html("Está sobrando " + PorcIdSobra + "%");
        $("#EstPorcId").css("display", "block");
        return "0";
    } else if(PorcIdSobra < 0){
        $("#EstPorcId").html("Aun falta un " + Math.abs(PorcIdSobra) + "%");
        $("#EstPorcId").css("display", "block");
        return Math.abs(PorcIdSobra);
    } else {
        $("#EstPorcId").html("");
        $("#EstPorcId").css("display", "none");
        return "0";
    }
}
