	 
$("#MatriAll").click(function(){

        var urlp=$(this).attr('href');
        
        $.ajax({
                type: 'GET',
                url: urlp,
                success: function(data){
                        $("#RespPeriMatAll").html(data)
                },
                beforeSend: function(){
                        $("#RespPeriMatAll").html("<img src='../img/loader-mini.gif'/><br/>");
                },
                error: function(data){
                        $("#RespPeriMatAll").html("Problemas de red. Intentelo de nuevo. " + data);
                }
        });
        return false;
});

$("#MatrPorGrupo").click(function(){

    var urldir=$(this).attr('href');
        
        $.ajax({
            type: 'POST',
            url: urldir,
            success: function(data){
                    $("#ContLoaded").html(data);
                    //alert(data.extractScript());
            },
            beforeSend: function(){
                    $("#ContLoaded").html("<img src='../img/loader-mini.gif'/><br/>");
            },
            error: function(data){
                    $("#ContLoaded").html("Hubo problemillas " + data);
            }
        });
        return false;
});


// Oculto los submenus
$("#Grupos ul").css({display: "none"});

// Que se oculten cada vez que de click en algun ul y se muestre el que le dio
$("#Grupos li").click(function(){
    $("#Grupos ul").css({display: "none"});

    //$(this).find('ul:first:visible').slideUp(400);  //No me funciona

    $(this).find('ul:first').css({visibility: "visible",display: "none"}).slideDown(400);

});
