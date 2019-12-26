jQuery(document).ready(function(){
    jQuery('.humbeger-btn').click(function(){
		if( jQuery(this).hasClass('clicked-menu') ){
			jQuery(this).removeClass('clicked-menu');
			jQuery('.primary-mobile-wrapper').slideUp();
		} else {
            jQuery(this).addClass('clicked-menu');
			jQuery('.primary-mobile-wrapper').slideDown();
		}
	});
});