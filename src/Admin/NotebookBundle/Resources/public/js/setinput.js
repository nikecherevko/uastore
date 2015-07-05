function setVal(name, value, id, color)
{
    
    
    if ($( "#li-"+id ).val()) {
        $( "#li-"+id ).val(0);
        var color = '#efedee';
        var textc = '#333';
        $( "#filtr-"+id ).val(0);
        
        if (name == 'color') {
            $( '#admin_notebookbundle_notebook_coloreng').val(null);
        }
        
    } else {
        $( "#li-"+id ).val(1);
        var color = '#555299';   
        var textc = '#fff'; 
        $( "#filtr-"+id ).val(id);
        
        if (name == 'color') {
            $( '#admin_notebookbundle_notebook_coloreng').val(value);
        }
        
    }
    
    var prop = $( "#admin_notebookbundle_notebook_"+name ).val();
    
    if(!prop){
        $( "#admin_notebookbundle_notebook_"+name ).val(value);
    }
    
    if(prop === value){
        $( "#admin_notebookbundle_notebook_"+name ).val('');
    }
      
    $( "#li-"+id ).css( "background-color", color);
    $( "#li-"+id ).css( "color",textc);
    

}