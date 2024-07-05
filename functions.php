<?php 
add_action( 'wp_enqueue_scripts', 'storefront_child_enqueue_styles' );
function storefront_child_enqueue_styles() {

	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); 
}


/**
 * Defining Some Variable
 */

if( ! defined( 'WPRP_DIR' ) ) {
	define( 'WPRP_DIR', get_stylesheet_directory() ); // Theme dir
}

if( ! defined( 'WPRP_URL' ) ) {
	define( 'WPRP_URL', get_stylesheet_directory_uri() ); // Theme url
}

/**
 * Function to load wp_enqueue_script
 * */
if ( ! function_exists( 'wprp_category_filter_scripts' ) ) {
    
    function wprp_category_filter_scripts() {

        wp_enqueue_script('wprp-public-script', WPRP_URL . '/assets/js/wprp-public.js', array('jquery'), null, true);

        wp_localize_script('wprp-public-script', 'WPRP_DATA', array( 
    																'ajaxurl'	 	=> admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ), 
    																'no_post_msg' 	=> esc_js( __( 'Sorry, No more post to display.', '	' ) )
    															) );
    }
    add_action('wp_enqueue_scripts', 'wprp_category_filter_scripts');
}

/**
 * Function to load for ajax call filter products
 * 
 * */
if ( ! function_exists( 'filter_products_by_category' ) ) {
    
    function filter_products_by_category() {


        $product_cat 	= ( !empty( $_POST['product_cat'] ) ) 	? $_POST['product_cat'] : array();
        $product_color   = ( !empty( $_POST['product_color'] ) )   ? $_POST['product_color'] : array();
        $paged 			= ( !empty( $_POST['paged'] ) )		     ? $_POST['paged'] 		: 1;

        $paged          = ( !empty( $_POST['search_type'] ) &&  $_POST['search_type'] === 'category' )  ? 1  : $paged;
        
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
            'paged' 			=> $paged,
            'meta_query' 		=> $meta_query,
        );

        if( ! empty( $product_cat ) ) {
    		$args['tax_query'] = array(
    								array(
    								'taxonomy'	=> 'product_cat',
    								'field'		=> 'id',
    								'terms'		=> $product_cat,
    								),
    							);
    	}

    	ob_start();

        $query = new WP_Query($args);

        $max_num_pages	= $query->max_num_pages;

        if ( $query->have_posts() ) {

            while ( $query->have_posts() ) {

            	$query->the_post();
                wc_get_template_part('content', 'product');
            }

        }

        wp_reset_postdata(); // Reset WP Query

    	$content = ob_get_clean();

    	$result['success'] 		= 1;
    	$result['content'] 		= $content;
    	$result['paged']		= $paged + 1;
    	$result['last_page']	= ( $paged >= $max_num_pages ) ? 1 : 0;
        $result['max_num_pages'] = $max_num_pages;

    	wp_send_json( $result );
    }
    add_action('wp_ajax_filter_products', 'filter_products_by_category');
    add_action('wp_ajax_nopriv_filter_products', 'filter_products_by_category');
}

/**
 * Function to load for get product filter option 
 * 
 * */
if ( ! function_exists( 'get_wprp_product_color_options' ) ) {
    
    function get_wprp_product_color_options() {

        if ( function_exists( 'acf_get_fields' ) ) {
            
            $fields         = acf_get_fields( 'group_667fbfd971cfc' );
            $product_color  = array();

            if ( $fields ) {

                foreach ( $fields as $a_ky => $field ) {

                    if ( $field['name'] === 'product_color' ) {
                        
                        $product_color = $field['choices'];
                    }
                }
            }
        }

        return $product_color;
    }

}

function wprp_loop_shop_columns( $columns ) {

    $columns = 4;
    return $columns;
}

add_filter('loop_shop_columns', 'wprp_loop_shop_columns');

 ?>