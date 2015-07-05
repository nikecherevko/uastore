function confirmdelete(id, title, category){

    var elem = $('#li-container'+id);
    
    var cat = (category) ? 'Категория' : 'Страница';
     
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
     
            $.ajax({url: "delete/"+id,
                success: function(){
                    elem.slideUp();
                    swal("Удалено!", cat + " была успешно удалена.", "success");
                },
                error :  function(){
                    sweetAlert("Ошибка", cat + " содержит страницы!", "error");
                }
            });
            
        }
                
        );

}