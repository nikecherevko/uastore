var showFile = (function () {
    var fr = new FileReader,
        i = 0,
        files, file;
    
    fr.onload = function (e) {
        var li;
        if (file.type.match('image.*')) {
            li = document.createElement('li');
            li.innerHTML = "<img src='" + e.target.result + "' width='170px' >";
            document.getElementById('list').appendChild(li);
        }
            file = files[++i];
            if (file) {
                fr.readAsDataURL(file)
            } else {
                i = 0;
            }
    }
    
    return function (e) {
        files = e.target.files;
        file = files[i];
        if (files) {
            while (i < files.length && !file.type.match('image.*')) {
                file = files[++i];
            }
            if (file) fr.readAsDataURL(files[i])
        }
    }
 
})()
 
document.getElementById('photo').addEventListener('change', showFile, false);

 $(".recentList").on('click', '.delete', function () {
            var cache = $(this).closest('li');

            cache.fadeOut(300, function(){ 
               cache.remove(); 
            });
});