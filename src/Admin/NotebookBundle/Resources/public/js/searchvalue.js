function searchvalue(id){
    
    var valbut = document.getElementById('category_'+id).value;
    var val = document.getElementById('categorysub_'+id);
    
    if(valbut == "+") {
        document.getElementById('category_'+id).value="-"; 
        document.getElementById('category_'+id).innerHTML='<span class="iconcat icminus">-</span>';
        val.style.display = 'block';
        
    } else {
        document.getElementById('category_'+id).value="+"; 
        document.getElementById('category_'+id).innerHTML='<span class="iconcat icplus">+</span>';
        val.style.display = 'none';
    }

    var elem =  $('#categorysub_'+id);
    $.ajax({url: "getvalue/"+id, success: function(data){
        elem.html(data); 
    }});
}
 