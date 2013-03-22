$(function(){

	$(".rollbtAnu").click(function(){
		var Respus = $(this).attr("id").split(":");
		$("#AgrC"+Respus[1]).css("display","block");
		$("#rollA"+Respus[1]).css("display","none");
	});

	$(".rstCmAn").click(function(){
		var Respus = $(this).attr("id").split(":");
		$("#AgrC"+Respus[1]).css("display","none");
		$("#rollA"+Respus[1]).css("display","block");
		//$("#AgrC"+Respus[1]).hide("blind");
		//$("#rollA"+Respus[1]).show("blind");
	})

	$(".EnvCmAn").click(function(e){
		e.preventDefault();
		var idCm = $(this).attr("id").split(":");
		idCm = idCm[1];

		nomCm = "#EdA"+idCm;
		cmt = $(nomCm).val();
		var loader = "#loadA"+idCm;
		
		var myurl = "../Comentario.php?Op=AgrAnu&Cmt="+cmt+"&idAnu="+idCm;
		$.GuardarGet(myurl, loader, function(data){
			if(data=='Guardado'){
				$("#Cmt_"+idCm).append("<div>"+cmt+"</div>")
				//$("#AgrC"+idCm).hide("blind");
				//$("#rollA"+idCm).show("blind");
			}
			
		});

	});
})