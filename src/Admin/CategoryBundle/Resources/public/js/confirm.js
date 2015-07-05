function confirmdelete(id, countchild, title, count){
    
    if (countchild > 0 || count > 0) {
        
        var mess1 = countchild > 0 ? '- Категория содержит вложеные под категории!':'';
        var mess2 = count > 0 ? '<br>- Категория содержит товары!':'';
        
        sweetAlert("Ошибка", mess1+' '+mess2, "error");
        
    } else {
        
        var elem = $('#li-container'+id);

        swal({
                title: "Вы уверенны?",
                text: "Категория будет полностью удалена!",
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
                        swal("Удалено!", "Категория была успешно удалена.", "success");
                    },
                    error :  function(){
                        sweetAlert("Ошибка", "Категория содержит страницы!", "error");
                    }
                });

            }

            );
        
    }
	
}