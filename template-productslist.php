<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Products List
 * 
 */

get_header(); 

$product_cats 	= get_terms('product_cat');
$product_color 	= get_wprp_product_color_options();

?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>

					<div class="entry-content">

						<div class="wprp-product-list-main-wrap wprp-row">

							<div id="wprp-product-category-filter" class="wprp-product-category-filter wprp-col-3" >

							    <form id="wprp-product-filter-form" method="post">
							    	
							    	<div class="wprp-product-filter">
								    
								    	<h3><?php esc_html_e('Product Category', 'storefront-child'); ?></h3>

								        <?php if ( !empty( $product_cats ) ): ?>
								        	<ul class="wprp-category-list">
							        		<?php foreach ( $product_cats as $p_key => $cat ): ?>
								            	<li>
							            			<label>
							            				<input type="checkbox" name="product_cat[]" value="<?php echo esc_attr($cat->term_id); ?>"> <?php esc_html_e($cat->name); ?>
							            			</label>
								            	</li>	
							        		<?php endforeach ?>
								        	</ul>
								        <?php endif ?>
							    		
							    	</div>

							    	<div class="wprp-product-filter">
								    
								    	<h3><?php esc_html_e('Product Color', 'storefront-child'); ?></h3>
								    	
								        <?php if ( !empty( $product_color ) ): ?>
								        	<ul class="wprp-product-color-list">
							        		<?php foreach ( $product_color as $a_key => $color ): ?>
								            	<li>
							            			<label>
							            				<input type="checkbox" name="product_color[]" value="<?php echo esc_attr($a_key); ?>"> <?php esc_html_e($color); ?>
							            			</label>
								            	</li>	
							        		<?php endforeach ?>
								        	</ul>
								        <?php endif ?>
							    		
							    	</div>

							    </form>
							</div>
							<div class="wprp-product-grid wprp-col-9" id="wprp-product-grid">
								<?php include WPRP_DIR.'/wprp-template/product-list.php'; ?>
							</div>
						</div>
					</div>

				</article><!-- #post-## -->

				<?php
			endwhile; // End of the loop.
			?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
