function sortcategory(id){
    var elem =  $('#sortcat');
    $.ajax({url: "sort/"+id, success: function(data){
        $("#dialog" ).dialog(elem.html(data),{ 
            width: 500,
            show: {
        effect: "explode",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }}); 
    }});
}