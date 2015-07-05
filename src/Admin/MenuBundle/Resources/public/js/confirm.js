function confirmdelete(id, title, category){

    var elem = $('#li-container'+id);
    
    var cat = (category) ? 'Категория меню' : 'Пункт меню';
    var deleteurl = (category) ? 'delete' : 'deletemenusub';
     
    swal({
            title: "Вы уверенны?",
            text: cat + " будет полностью удалена!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Удалить!",
            cancelButtonText: "Отменить",
            closeOnConfirm: false
            },
      
        function(){
     
            $.ajax({url: deleteurl+"/"+id,
                success: function(){
                    elem.slideUp();
                    swal("Удалено!", cat + " успешно удалено.", "success");
                },
                error :  function(){
                    sweetAlert("Ошибка", cat + " содержит пункты меню!", "error");
                }
            });
            
        }
                
        );

}