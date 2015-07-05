function sortcategory(id, cat){
    
    var elem =  $('#sortcat');
    
    var nameurl = (cat) ? 'sort' : 'sortmenusub';
    
    $.ajax({url: nameurl+"/"+id, success: function(data){
        $("#dialog" ).dialog(elem.html(data),{ width: 500,       show: {
        effect: "explode",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }}); 
    }});
}