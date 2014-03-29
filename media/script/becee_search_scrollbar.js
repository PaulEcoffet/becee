		var isMobile = {
		Android: function() {
		    return navigator.userAgent.match(/Android/i) ? true : false;
		},
		BlackBerry: function() {
		    return navigator.userAgent.match(/BlackBerry/i) ? true : false;
		},
		iOS: function() {
		    return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false;
		},
		Windows: function() {
		    return navigator.userAgent.match(/IEMobile/i) ? true : false;
		},
		any: function() {
		    return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Windows());
		}
		};

	    jQuery(document).ready(function($){
	    	if(! isMobile.any()){
		    	$('.containers2').scrollbar();
		    }
    		$('.restaurant').click(function() {
	    		$('.restaurant').removeClass('restaurant-clicked');
	    		$(this).addClass('restaurant-clicked');
	    	})
	    });