function showFile(e) {
         
    var files = e.target.files;
    var f = files[0];
    
    if (!f.type.match('image.*')) {
        document.getElementById('fileupload').innerHTML = "<span style='color: red'>Этот формат изображения не поддерживается.</span>";
    } else {
        var fr = new FileReader();
        fr.onload = (function(theFile) {
          return function(e) {
            document.getElementById('fileupload').innerHTML = "<img width='80px'  src='" + e.target.result + "' />";
          };
        })(f);

        fr.readAsDataURL(f);
    
    }
}
 
 document.getElementById('admin_categorybundle_category_file').addEventListener('change', showFile, false);