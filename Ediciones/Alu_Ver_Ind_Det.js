
$(function(){
 
	var NtIni = 0;

	ComprobarTitulos();

	setTimeout(function(){
		$(".ResFlot").fadeOut();
	}, 5000);

	$(".NtInd").on("focus", function(){
		NtIni = $(this).val();
	})
	$(".NtInd").on("focusout", function(){
		$(this).GuarNota();
	})
	$(".NtInd").on("keydown", function(e){
		
		switch (e.which)
		{
			case 13:
				$(this).GuarNota();
				break;
			case 27:
				e.preventDefault();
				$(this).val(NtIni);
				alert("Cancelaste la edición.");
				break;
		}
	});

	jQuery.fn.GuarNota = function(){
		if(NtIni != $(this).val()){
			var divi = $(this).attr("id").split(":");
			url="../Ediciones/Alu_Ver_Ind_Det.php?nota=" + $(this).val()+"&idn="+divi[1];
			$(".ResFlot").html("Guardando...")
			$(".ResFlot").show();
			if($(this).val() < $("#NotaBasic").val() ){
				$(this).addClass("perd");
			}else{
				$(this).removeClass("perd");
			}
			$(".ResFlot").load(url, function(){
				ComprobarTitulos();
				setTimeout(function(){
					$(".ResFlot").fadeOut();
				}, 5000);
			
		    })
		}
	}
	$(".titPer").on("click", function(){

		idCn = $(this).attr("id").split("_");

		NidCn = "#CnP" + idCn[1] + " .CnMt";

		$(NidCn).each(function(){
			
			if ($(this).css("display") == "none") {
				$(this).css("display", "block");
			}else{
				$(this).css("display", "none");
			}
		})


	});

	function ComprobarTitulos(){
		$(".titPer").each(function(){
			var TextInicio = $(this).html();
			var txtPerio = TextInicio.substring(0, 9);
			var contPerd=0;

			var attId = $(this).attr("id").split("_");
			attId = "#CnP"+attId[1]+" .perd";
			$(attId).each(function(){
				contPerd++;
			})

			if(contPerd>0){
				if(contPerd==1){
					txtPerio = txtPerio + " (" + contPerd + " nota pendiente)"
				}else{
					txtPerio = txtPerio + " (" + contPerd + " notas pendientes)"
				}
			} 
			$(this).html(txtPerio);
		})
	}
})