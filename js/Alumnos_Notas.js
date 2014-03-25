$(document).ready(function() {
	
	Promediar();
	
	$(".Indicador").hide("slow");
	
	$("#LinkEliminar").click(function(){
        if(confirm("¿Está seguro que desea proseguir? Esto eliminará las notas agregadas a todos los indicadores de esta competencia.")){
            $.ajax({
                type: 'POST',
                url: 'Eliminar_Notas_de_Indicador.php',
                data: $(this).attr("href"),
                success: function(data){
                        $("#Resultado").html(data);
                        history.back();
                },
                beforeSend: function(){
                        $('#Resultado').html("<img src='img/loader-mini.gif'/><br/>");
                },
                error: function(data){
                        $('#Resultado').html("Hubo problemillas " + data);
                }
            });	
            return false;
        }
	});
	
	$("#NotasTodas").submit(function() {
		$.ajax({
            type: 'POST',
            url: 'Guardar_Notas.php',
            data: $(this).serialize(),
            success: function(data){
                $("#Resultado").html(data);
            },
            beforeSend: function(){
                $('#Resultado').html("<img src='img/loader-mini.gif'/><br/>");
            },
            error: function(data){
                $('#Resultado').html("Hubo problemillas " + data);
            }
        });
        $.ajax({
            type: 'POST',
            url: 'Guardar_Notas.php',
            data: $(this).serialize(),
            success: function(data){
                $("#Resultado").html(data);
            },
            beforeSend: function(){
                $('#Resultado').html("<img src='../img/loader-mini.gif'/><br/>");
            },
            error: function(data){
            	$('#Resultado').html("Hubo problemillas " + data);
            }
        });
        return false;
	});
	
	
	//Exigir numeros y mayor de cero y menor que 100
	$(".NotaOnly").on("change", function(e) {
		var cant = this.value;
		var cant_es_flotante = isFloat(cant);

		if (isNaN(cant))
		{
			alert('Valor introducido:       '+cant+' \n\n Introduce solo valores numericos');
			this.value = 0;
		}else if (cant < 1 ){
			//alert('Valor introducido:       '+cant+' \n\n Introduce números enteros mayores que 0');
			this.value = this.value*-1;
		}else if(cant > 100){
			this.value = 100;
		}else if (cant_es_flotante == true)	{
			//alert('Valor introducido:       '+cant+' \n\n El valor es decimal.  SerÃ¡ convertido a entero.');
			cant = parseInt(cant);
			this.value = cant;
		}
	});
	
	
			
	$(".NotaOnly").focusout(function(e) {
		
		Nota=$(this).val();
		
		Nombre=$(this).attr('name');
		
		PosI=Nombre.indexOf('I');
		PosA=Nombre.indexOf('A');
		
		idInd=Nombre.substring(PosI+1);
		idAlu=Nombre.substring(PosA+1, PosI);
		
		PromediarAlu(idAlu);
		
		Datos="idAlu="+idAlu+"&idInd="+idInd+"&Nota="+Nota;
		
		$.ajax({
			type: 'POST',
			url: 'Guardar_Notas_Only.php',
			data: Datos,
			success: function(data){
				$("#Resultado").html(data);
			},
			beforeSend: function(){
				$('#Resultado').html("<img src='img/loader-mini.gif'/><br/>");
			},
			error: function(data){
				$('#Resultado').html("Hubo problemas en la red."/* + data*/);
			}
		});
        return false;
	});
	
	
	
	$("#Atras").click(function(e) {
        history.back();
    });
	
	
//Fin del Document ready
});
	
function eliminar(Datos){
	if (confirm("¿Desea eliminar las notas de esta competencia a ?")){
		alert(Datos);
		$.ajax({
			url: "Eliminar_Notas_de_Alumno.php",
			data: Datos,
			type: "POST",
			success: function(resp){
				alert(resp);
				history.back();
			}
		})
	}
}
function isFloat(myNum) {
	// es true si es 1, osea si es flotante
	var myMod = myNum % 1;
	 
	if (myMod == 0) 
	{ return false; } 
	else { return true; }
}

function PromediarAlu(idAlum){

	Acumu=0;
	idAluAnt=0;

	$(".LaNota").each(function(index, element) {
	    
		NombreP=$(this).attr('name');
		
		if($(this).val()<70){
			$(this).addClass("NotaDef");
		} else {
			$(this).removeClass("NotaDef");
		}
		
		PosIp=NombreP.indexOf('I');
		PosAp=NombreP.indexOf('A');
		
		idIndp=NombreP.substring(PosIp+1);
		idAlup=NombreP.substring(PosAp+1, PosIp);
		
		idTh="CodInd"+idIndp;
		
		Porc=document.getElementById(idTh).className/100;
		Notap=$(this).val();
		
		valTemp=Porc*Notap
		
		if(idAluAnt!=idAlup){
			Acumu=0;
		} 
		
		Acumu+=valTemp;
		idAluAnt=idAlup;
		
		NomP="Prom"+idAlup;
		Acumu = Math.round(Acumu*100)/100;
		document.getElementById(NomP).innerHTML=Acumu;
		
		
	});
	//Tomar todos los texts que tengan el codigo del alumno y promediar

}

function Promediar(){

	Acumu=0;
	idAluAnt=0;

	$(".LaNota").each(function(index, element) {
	    
		Nombre=$(this).attr('name');
		
				
		if($(this).val()<70){
			$(this).addClass("NotaDef");
		} else {
			$(this).removeClass("NotaDef");
		}
		
		PosI=Nombre.indexOf('I');
		PosA=Nombre.indexOf('A');
		
		idInd=Nombre.substring(PosI+1);
		idAlu=Nombre.substring(PosA+1, PosI);
		
		idTh="CodInd"+idInd;
		
		Porc=document.getElementById(idTh).className/100;
		Nota=$(this).val();
		
		valTemp=Porc*Nota
		
		if(idAluAnt!=idAlu){
			Acumu=0;
		} 
		
		Acumu+=valTemp;
		idAluAnt=idAlu;
		
		NomP="Prom"+idAlu;
		Acumu = Math.round(Acumu*100)/100;
		document.getElementById(NomP).innerHTML=Acumu;
		
		
	});
	//Tomar todos los texts que tengan el codigo del alumno y promediar
	
}

