$(document).ready(function(){
   $("ul#categoryorder").sortable({ 
        opacity: 0.6, 
        cursor: 'move',  
        update: function(){
               } 
        });
});

function saveDisplayChanges()
{
    var order = $("ul#categoryorder").sortable("serialize");
    $.ajax({method: "POST", url: "sortvaluesave/",data: { order: order }, success: function(data){
        $('#boxindex').html(data); 
        $( "#dialog" ).dialog( "close" ,{hide: {
        effect: "explode",
        duration: 3000
        }});
    }});
}
