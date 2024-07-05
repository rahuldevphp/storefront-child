<?php 

$product_cat		= ! empty( $_POST['product_cat'] ) 	? $_POST['product_cat'] : '';
$paged 				= isset($_POST['page']) 				? $_POST['page'] 			: 1;
$product_color   	= ( !empty( $_POST['product_color'] ) )   ? $_POST['product_color'] : array();

$meta_query = array('relation' => 'OR');

if (!empty($product_color)) {


     foreach ($product_color as $color) {
         $meta_query[] = array(
             'key' => 'product_color',
             'value' => '"' . $color . '"',
             'compare' => 'LIKE'
         );
     }
 }

$args = array(
    		'post_type' 		=> 'product',
        	'post_status'		=> array( 'publish' ),
        	'posts_per_page' 	=> 4,
        	'paged' 				=> $paged,
        	'meta_query' 		=> $meta_query,
);

if( ! empty( $product_cat ) ) {

	$args['tax_query'] 	= 	array(
										array(
										'taxonomy'	=> 'product_cat',
										'field'		=> 'id',
										'terms'		=> $product_cat,
										),
									);
}


$query 			= new WP_Query($args);
$max_num_pages	= $query->max_num_pages;
$paged			= $paged + 1;
 ?>
 <ul id="product-grid-list" class="products columns-4">
    <?php
	   if ( $query->have_posts() ) {
	    		
	        	while ( $query->have_posts() ) {
		    			
	            $query->the_post();
	            wc_get_template_part('content', 'product');
	        	}
	   	
	        	wp_reset_postdata();
	   }	
    ?>
</ul>
<?php if( $max_num_pages > 1 ) { ?>
<div class="wprp-product-pagination wprp-product-loader-more">
	<div class="wprp-product-ajax-btn-wrap">
		<button class="wprp-product-load-more-btn" data-paged="<?php echo esc_attr( $paged ); ?>"> <i class="wprp-ajax-loader"><img src="<?php echo WPRP_URL.'/assets/images/loader.gif'; ?>" alt="<?php esc_html_e( 'Loading', 'storefront-child' ); ?>" /></i> <?php esc_html_e( 'Load More', 'storefront-child' ); ?></button>
	</div>
</div>
<?php } ?>