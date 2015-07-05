function confirmdelete(id, name, cat){
        
        var elem = $('#li-container'+id);
        var title = (cat) ? 'Категорию' : 'Свойство';
        var del = (cat) ? 'delete' : 'deletevalue';

        swal({
                title: "Вы уверенны?",
                text: title+" "+name+" будет удалено!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Удалить!",
                cancelButtonText: "Отменить",
                closeOnConfirm: false
                },

            function(){
                $.ajax({url: del+"/"+id, 
                    success: function(){
                        elem.delay(2000).fadeOut(400);
                        swal("Удалено!", title+" "+name+" успешно удалена.", "success");
                    },
                    error: function(){
                        sweetAlert("Ошибка", "Категория содержит свойства", "error");
                    }
                });

            }

            );
        
}

function confirmdeletenotebook(id){
        
        swal({
                title: "Вы уверенны?",
                text: "Ноутбук будет удалено!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Удалить!",
                cancelButtonText: "Отменить",
                closeOnConfirm: false,
                closeOnCancel: false
                },
                
        function(isConfirm){
            if (isConfirm) {
                $(location).attr('href','delete/'+id);
            } else {
                swal("Cancelled", "Отменено", "error");
            }
        });        
                
}

function confirmdeletenotebookimage(id, main, noteid){
    
        var elem = $('#list');
        
        var main = (main) ? main : 0;
        
        $.ajax({url: "deleteimage/"+id+"?nid="+noteid+'&main='+main, success: function(data){
            elem.html(data); 
        }});
                
}