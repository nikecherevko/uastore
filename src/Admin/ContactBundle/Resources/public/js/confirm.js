function confirmprocess(id){
        
        var container = $('#li-container'+id);

        $.ajax({url: "process/"+id, 
            success: function(data){
                container.delay(1000).fadeOut(400);
            }
        });
}

function confirmdelete(id){
        
        var elem = $('#telephone'+id);

        swal({
                title: "Вы уверенны?",
                text: "Телефон будет удалён!",
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
                        elem.delay(3000).fadeOut(400);
                        swal("Удалено!", "Телефон успешно удалено.", "success");
                    }
                });

            }

            );
        
}