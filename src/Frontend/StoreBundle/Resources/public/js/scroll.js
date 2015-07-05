
$(function() {
		var offset = $("#fixed").offset();
		var topPadding = 80;
                
		$(window).scroll(function() {
			if ($(window).scrollTop() > offset.top) {
				$('#fixed').css({'position': 'fixed', 'top':'80px', 'z-index':'10'});
                                $(".toolbar").css('display', 'block');
			} else {
                            $('#fixed').css({'position': 'static'});
                            $(".toolbar").css('display', 'none');
                        }
        ;});
                                
	});

