
RutaRel="../img/";


InputFIle=document.getElementById("FlImgNuevPerf");

InputFIle.addEventListener("change", function(evt){
    $("#RespImgPerf").html("Subiendo...");

    ArchImg = this.files[0];

    if (ArchImg.type.match(/image.*/)) {

        if (window.FileReader ) {
            reader = new FileReader();
            reader.onloadend = function (e) { 
                $(".MyImgPerf").attr('src', e.target.result);
                $(".MyImgPerf").attr('title', "Imágen de perfil");
                
            };
            reader.readAsDataURL(ArchImg);
        } else {
            alert("Lo sentimos, este navegador no soporta FileReader");
            $("#RespImgPerf").html("");
        }
        
        var formdata;

        if(window.FormData){
            formdata=new FormData();
            formdata.append("ImagenPerf", ArchImg);
            formdata.append("idUsu", $("#frmSubirPerf > input[name=idUsu]").val());
            formdata.append("OperUsu", "ImgPerf");
            
            var ImgAnte=$(".MyImgPerf").attr('src');
            urlDes='../Guardar_MyUsu.php';
            
            $.ajax({
                url: urlDes,
                type: "POST",
                data: formdata,
                processData: false,
                contentType: false,
                success: function (data) {
                    if(data=="SubidaExitosa"){
                        $("#RespImgPerf").html("");
                    }else{
                        $(".MyImgPerf").attr('src', ImgAnte);
                        $("#RespImgPerf").html(data); 
                    }
                    
                },
                beforeSend: function(){
                    $("#RespImgPerf").append("<img src='"+RutaRel+"loader-mini.gif'/>");
                },
                error: function(){
                    $('#RespImgPerf').html("Hubo problemas en la red."/* + data*/);
                }
            });
            
        }else {
            alert("Este navegador no acepta esta funcionalidad.");

        }

        
    }else{
        alert("Solo puedes seleccionar imágenes");
        $("#RespImgPerf").html("");
    }
    



});


        
