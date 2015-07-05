function confirmdelete(id,title){
        
        var elem = $('#li-container'+id);

        var mess = (title) ?'Способ оплаты':'Валюту обмена';
        
        swal({
                title: "Вы уверенны?",
                text: mess + " будет удалено!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Удалить!",
                cancelButtonText: "Отменить",
                closeOnConfirm: false
                },

            function(){
                $.ajax({url: "delete/"+id, 
                    success: function(){
                        elem.delay(2000).fadeOut(400);
                        swal("Удалено!", mess + " успешно удалено.", "success");
                    }
                });

            }

            );
        
}

function confirmorder(id,value){
    var elem =  $('#order-state');
    $.ajax({url: "changestate/"+id+"?value="+value, success: function(data){
        elem.html(data); 
    }});
}

function delproduct(id,oid){
    var cont =  $('#ordercontainer');
    $.ajax({url: "deleteproduct/"+id+"?oid="+oid, 
        success: function(data){
            cont.html(data);
        } 
});
}


