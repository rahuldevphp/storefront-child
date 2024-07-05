(function( $ ) {

	"use strict";

	jQuery(document).ready(function($) {

	    $('#wprp-product-filter-form input[type="checkbox"]').change(function() {
	    	get_wprp_filter_products('category');
	    });

	    $('.wprp-product-load-more-btn').click(function() {
	    	get_wprp_filter_products('load_more');
	    });
	});

	
})(jQuery);

function get_wprp_filter_products( search_type ) {
	
	var paged 			= jQuery('.wprp-product-load-more-btn').attr('data-paged');
	var product_cat 	= [];
	var product_color 	= [];

    jQuery('#wprp-product-filter-form input[name="product_cat[]"]:checked').each(function() {
        product_cat.push(jQuery(this).val());
    });

    jQuery('#wprp-product-filter-form input[name="product_color[]"]:checked').each(function() {
        product_color.push(jQuery(this).val());
    });

    if ( search_type == 'load_more' ) {
    	jQuery('.wprp-product-load-more-btn').addClass('active');
    }

    jQuery.ajax({
        url: WPRP_DATA.ajaxurl,
        type: 'POST',
        data: {
            action 			:'filter_products',
            product_cat		: product_cat,
            product_color	: product_color,
            paged  			: paged,
            search_type 	: search_type
        },
        success: function( result ) {

			jQuery('.wprp-product-load-more-btn').removeClass('active');
            
            if( result.sucess = 1 && ( result.content != '' ) ) {

				setTimeout(function(){
					
					if ( search_type == 'load_more' ) {
						jQuery('#wprp-product-grid #product-grid-list').append( result.content );
					}else{
						jQuery('#wprp-product-grid #product-grid-list').html( result.content );
					}

					jQuery('.wprp-product-load-more-btn').attr( 'data-paged', result.paged );
					
					if ( result.last_page == 1 ) {
						jQuery('.wprp-product-load-more-btn').hide();
					}

				}, 100);
        	}
    	}
    });
}