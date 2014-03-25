$(function(){
	$("#rdPuestModo").buttonset();
    $("#rdPuestPer").buttonset();

    $("#btAllAnio").on("click", function(e){
        e.preventDefault();
        window.open("../Informes/Puestos_Alumnos/All_Anio_Puestos.php?Pag=1", "_blank");
    });
    $("#btAllPeriod").on("click", function(e){
        e.preventDefault();
        window.open("../Informes/Puestos_Alumnos/All_Periodo_Puestos.php?Pag=1", "_blank");
    });
    $("#btTrPeriod").on("click", function(e){
        exeColorBox("#btTrAnio", e, function(){

        });
    });
    $("#btTrAnio").on("click", function(e){
        exeColorBox("#btTrAnio", e, function(){

        });
    });
    $("#btPuntAnioFir").on("click", function(e){
        exeColorBox("#btPuntAnioFir", e, function(){
            $.CargaJS("../Informes/Puestos_Alumnos.js");
        });
        $.CargarCSS("../Informes/css/Puestos_Alumnos.css");
    });
    $("#btPuntPeriod").on("click", function(e){
        exeColorBox("#btPuntPeriod", e, function(){
            $.CargaJS("../Informes/Puestos_Alumnos.js");
        })
        $.CargarCSS("../Informes/css/Puestos_Alumnos.css");
    });
    $("#btPuntAnio").on("click", function(e){
        exeColorBox("#btPuntAnio", e, function(){
            $.CargaJS("../Informes/Puestos_Alumnos_Anio.js");
        });
        $.CargarCSS("../Informes/css/Puestos_Alumnos.css");
    });

    function exeColorBox(Elem, e, myfunc){
        var datos = "idGrupo="+ $("#frmPorGrado option:selected").val();
        console.log(datos);
        $(Elem).colorbox({
            xhrError: "Lo sentimos, no se pudo cargar la página",
            data: datos,
            onComplete: myfunc
        });
        e.preventDefault();
    }

    $(".BusPuest").on("click", function(e){
        e.preventDefault();
    	TipoU=$("#hdPuesTipoU").val();
    	var urldir="";
    	var Oper = $("[name='rdPueM']:checked").val();
    	var filt = $("[name='rdPueP']:checked").val();
        var idGrupo = $("#frmPorGrado option:selected").val();

        if(Oper == 1){
            if(filt == 1){
               urldir= "../Informes/Tres_Primeros.php?TipoUsu="+TipoU+"&idGrupo="+idGrupo;
               $(".lightajax").click();
               return;
            }else if(filt == 2){
                urldir= "../Informes/Tres_Primeros_Anio.php?TipoUsu="+TipoU+"&idGrupo="+idGrupo;
            }
        }else if(Oper == 2){
            if(filt == 1){
                urldir= "../Informes/Puestos_Alumnos.php?TipoUsu="+TipoU+"&idGrupo="+idGrupo;
            }else if(filt == 2){
                urldir= "../Informes/Resumen_Puestos.php?TipoUsu="+TipoU+"&idGrupo="+idGrupo;
            }
        }

        window.open(urldir, "_blank");
    });

})