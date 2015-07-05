function doAjax(c, a) {
   
    $('.sort_links li').click(function() {
        $('.sort_links li').removeClass( "selected" );
        $(this).addClass( "selected" );
    });
     
        
    $.ajax({method: "POST", url: "/notebook/"+c ,data: { data: a },
        success: function(data){
        $('#box_product').hide().html(data).fadeIn('slow');
    }});
}

function doView(view) {
     
    $('.view_type li').removeClass( "selected" );
    $('.view_'+view).addClass( "selected" );
    
    $.ajax({method: "POST", url: '/notebook/changeview/' ,data: { data: view },
        success: function(data){
        $('#box_product').hide().html(data).fadeIn('slow');
    }});
}

function doCart(id) {
    
    $.ajax({method: "POST", url: '/cart/' ,data: { data: id }, dataType: 'json',
    beforeSend: function() {
        loading(true);
    }, 
    success: function(response) {
        if (response && response.viewlist && response.cart) {
            $('#popup_window').html(response.viewlist).fadeIn();
            $("#head-cart").hide().html(response.cart).fadeIn('slow');
            loading(false);
        }
    }      
});
}
 


$(".properties").on("click", ".properties_block .block_title", function() {
    
        var t = $(this).parent();
        var c = $(".chechboxes", t);
        if (t.hasClass("closed")) {
            c.slideDown(200, function() {
                t.removeClass("closed").addClass("opened");
            });
        } else if (t.hasClass("opened")) {
            c.slideUp(200, function() {
                t.removeClass("opened").addClass("closed");
            });
        }
    
});

function selfilter() {
    $(this).addClass( "checked" );
    var data = $("#notebookfilter").serialize();
    
    var pricemin = $("#min_price").val();
    var pricemax = $("#max_price").val();
     
    $.ajax({method: "POST", url: '/notebook/selectfilter/' ,dataType: 'json', data: { data: data, pricemin: pricemin, pricemax: pricemax },
    
    beforeSend: function() {
       loading(true);
    },
        
    success: function(response) {
        if (response && response.viewlist && response.viewfilter && response.priceslider) {
            
            
            $("#box_product").hide().html(response.viewlist).fadeIn('slow');
            $("#box_filter").html(response.viewfilter);
            $("#pricesliderbox").html(response.priceslider);
            loading(false);
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            
            
             
        }
    }   
     

});
}
 

 
function delfitlr(id) {
    
    if (id) {
        var filtrid = $(this).children("li input").val();
        $("#prop_"+id).prop('checked', false); 
    } else {
        $('.chechboxes li input').prop('checked', false);
    }
     
    var data = $("#notebookfilter").serialize();
    
    $.ajax({method: "POST", url: '/notebook/selectfilter/' ,data: { data: data }, 
        beforeSend: function() {
            loading(true);
        },
        success: function(response) {
            if (response && response.viewlist && response.viewfilter && response.priceslider) {
                $("#box_product").hide().html(response.viewlist).fadeIn('slow');
                $("#box_filter").html(response.viewfilter);
                $("#box_priceslider").html(response.priceslider);
                loading(false);
                window.history.pushState("object or string", "", "/notebook");
            }
        } 
    });
};

function doOrder() {
    $("#popup_window" ).fadeOut();
    var tel = $(".customerphone" ).val();
    
    $.ajax({method: "POST", data: {'data': tel}, dataType: 'json', url: '/order/' , 
        beforeSend: function() {
            loading(true);
        }, 
        success: function(response) {
            if (response && response.viewlist && response.cart) {
                loading(false);
                swal({
                    title: "Спасибо за заявку!",
                    text: "В ближайшее время мы Вам перезвоним.",
                    timer: 5000,
                    type: "success"
                  });
                $("#head-cart").hide().html(response.cart).fadeIn('slow');
                
            }
        } 

});
}      

function popup_window_close() {
    $("#popup_window" ).fadeOut();
}

jQuery(function($){
    
    $(".callmetel").mask("(099) 999-99-99",{completed:function(){
        $('#submitcallme').removeAttr("disabled").removeClass().addClass('submit btn btn-orange activeor');
    }});

    $(".buyoneclick").mask("(099) 999-99-99",{completed:function(){
        $('#submitoneclick').removeAttr("disabled").removeClass().addClass('submit btn btn-orange activeor');
    }});

    $(".customertel").mask("(099) 999-99-99",{completed:function(){
        $('#customertelsub').removeAttr("disabled").removeClass().addClass('submit btn btn-orange activeor');
    }});

});

function doCallme() {
    var tel = $(".callmetel" ).val();
        if(tel.length > 7 ) {
        $.ajax({method: "POST", data: {'data': tel}, url: '/callme/' ,
            beforeSend: function() {
                loading(true);
            }, 
            success: function(data){
            loading(false);
            swal({
                title: "Спасибо за заявку!",
                text: "В ближайшее время мы Вам перезвоним.",
                timer: 5000,
                type: "success"
              });
        }});
    }
}  


function doFastorder(id) {
    var tel = $("#telephonefast" ).val();
    var address = $("#addressorder" ).val();
    var fio  = $("#fioorder" ).val();
    
  
        if(tel.length > 7 ) {

            $.ajax({method: "POST", data: {'tel': tel, 'id': id, 'address': address, 'fio': fio}, url: '/buyoneclick/' ,
            beforeSend: function() {
                loading(true);
            },    
            success: function(data){
                loading(false);
                swal({
                    title: "Спасибо! Ваш заказ оформлен.",
                    text: "В ближайшее время мы Вам перезвоним.",
                    timer: 5000,
                    type: "success"
                  });
                $('#fastorder').fadeOut('slow');
                var delay = 3000;
                setTimeout("document.location.href='/'", delay);

            }}); 
    }
} 

function fastorder() {
    $('#footerinfo').fadeOut('slow');
    $('#fastorder').fadeIn('slow');
}  

function doBuyoneclick(id) {
   
    var tel = $(".buyoneclick" ).val();

    if(tel.length > 7 ) {
        if(id === "fast"  ) {
            $.ajax({method: "POST", data: {'tel': tel, 'id': id}, url: '/buyoneclick/' ,
                beforeSend: function() {
                    loading(true);
                },   
                success: function(data){
                loading(false);
                swal({
                    title: "Спасибо за заявку!",
                    text: "В ближайшее время мы Вам перезвоним.",
                    timer: 5000,
                    type: "success"
                  });
                $('#fastform').fadeOut('slow');
                var delay = 3000;
                setTimeout("document.location.href='/'", delay);

            }}); 


        } else {
            $.ajax({method: "POST", data: {'tel': tel, 'id': id}, url: '/buyoneclick/' ,
                beforeSend: function() {
                    loading(true);
                }, 
                success: function(data){
                loading(false);
                swal({
                    title: "Спасибо за заявку!",
                    text: "В ближайшее время мы Вам перезвоним.",
                    timer: 5000,
                    type: "success"
                  });
                $('#fastform').fadeOut('slow');

            }});   
        }
    }
}  

function doSearch() {
   
    search = $(".searchproduct" ).val();
    
    if (search.length > 2) {
        $.ajax({method: "POST", data: {'search': search}, url: '/search/' ,
            beforeSend: function() {
                loading(true);
            }, 
            success: function(data){
            $('.serach_result_container').html(data).fadeIn('slow');
            loading(false);
        }});
    }
}  

function doDelproduct(id) {
    
    $.ajax({method: "POST", url: '/delproduct/' ,data: { id: id }, dataType: 'json',
    beforeSend: function() {
        loading(true);
    },  
    success: function(response) {
        if (response && response.viewlist && response.sum && response.count) {
            $('#bastsum').html(response.viewlist).fadeIn();
            $(".requesttotal").hide().html(response.sum).fadeIn('slow');
            $("#tr"+id).fadeOut('slow');
            loading(false);
        } else {
            $('#bastsum').html(response.viewlist).fadeIn();
            $("#container-basket").fadeOut('slow');
            loading(false);
            var delay = 3000;
            setTimeout("document.location.href='/'", delay);

        }
    }      
});
}

function loading(view) {
    if (view) {
        $(".catalog_ajax_loader_bg").fadeIn();
        $(".catalog_ajax_loader").fadeIn();
    } else {
        $(".catalog_ajax_loader_bg").fadeOut();
        $(".catalog_ajax_loader").fadeOut();
    }
}


$("#head_search_input").keyup(function () {
    
    var that = this,
    value = $(this).val();

    if (value.length >= 3 ) {
        
        $.ajax({method: "POST", data: {'search': value}, url: '/search/' ,
            beforeSend: function() {
                loading(true);
            },  
            success: function(data){
            loading(true);
            $('.serach_result_container').html(data).fadeIn('slow');
        }});
    } else {
            $('.serach_result_container').fadeOut('slow');
    }
        
});
 
 