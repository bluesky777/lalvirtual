$(function(){
 
	$("#rdPerdT").buttonset();
	
	$("#AutcmAl").focus();
	var cache = {}, lastXhr, idAlTemp="";
	
	$("#AutcmAl").autocomplete({
		source: function(request, response) {
			var term = request.term;
			if ( term in cache) {
				response ( cache [term]);
				return;
			}
			lastXhr = $.getJSON("../Ediciones/Alu_Ver_Ind_Det.php", request, function(data, status, xhr){
				cache[term] = data;
				if(xhr === lastXhr){ response(data); }
			})
		},
		select: function(event, ui){

			$("#DsIdAl").html(ui.item.id);
			$("#DsNom").html(ui.item.label);
			idAlTemp = ui.item.id;
			url="../Ediciones/Alu_Ver_Ind_Det.php?IdAlu="+ idAlTemp+"&Flt="+$("[name='rdPerdT']:checked").val();
			$.CargaDinamicaInterDir(url, "../Ediciones/Alu_Ver_Ind_Det.js", ".CntInds");

		},
        matchContains: true
	})

	$("#AutcmAl").on("focusout", function(){
		$(this).removeClass("ui-autocomplete-loading");
	})

	$("#busAl").on("click", function(){
		if(idAlTemp!=""){
			url="../Ediciones/Alu_Ver_Ind_Det.php?IdAlu="+ idAlTemp+"&Flt="+$("[name='rdPerdT']:checked").val();
			$.CargaDinamicaInterDir(url, "../Ediciones/Alu_Ver_Ind_Det.js", ".CntInds");
		}else{
			$("#AutcmAl").focus();
			alert("Primero debe buscar un alumno.")
		}
	})
	$("#busAl2").on("click", function(){

		url="../Ediciones/Alu_Ver_Ind_Det.php?Flt="+$("[name='rdPerdT']:checked").val();
		$.CargaDinamicaInterDir(url, "../Ediciones/Alu_Ver_Ind_Det.js", ".CntInds");

	})
})

	