function confirmdelete(id){
        
        var elem = $('#telephone'+id);

        swal({
                title: "Вы уверенны?",
                text: "Цвет будет удалён!",
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
                        swal("Удалено!", "Цвет успешно удалено.", "success");
                    }
                });

            }

            );
        
}