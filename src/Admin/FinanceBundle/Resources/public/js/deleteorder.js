function deleteorder(id){
        
        var elem = $('#order-'+id);
        
        swal({
                title: "Вы уверенны?",
                text: "Заказ будет удалено!",
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
                        swal("Удалено!", "Заказ успешно удалено.", "success");
                    }
                });

            }

            );
}
 