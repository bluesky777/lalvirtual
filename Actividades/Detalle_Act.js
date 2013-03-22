$(document).on("ready", function(){
	tinyMCE.init({
		mode: "textareas",
		theme: "advanced",
		skin: "o2k7",
		plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
	})

	$("#rdOcul").buttonset();

	$(".dattim").datetimepicker({
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

	$("#frmCrAct").on("submit", function(e){
		datos = $("#frmCrAct").serialize();
		$.GuardarGet2("Detalle_Act.php?" + datos, "#RespAct", function(){
			//nada por ahora
		});
		e.preventDefault();
	})


jQuery.GuardarGet2 = function(myPag, myCnt, aejecutar){

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

})
