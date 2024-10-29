<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	// Template for single product
	$options = get_option( 'ac_catalog_options', '' );
?>
<?php get_header(); ?>
    <div class="wrapper ac-product-single">
        <div class="container">
            <div class="row">
                <div class="col<?php if ( is_active_sidebar( 'ac_single_product_sidebar' ) ) {
					echo( '-8' );
				} ?>">
					<?php
						if ( have_posts() ) :
							while( have_posts() ) :
								the_post(); ?>
                                <div class="row">
                                    <div class="col-5 ac-product-images-wrapper center">
										<?php echo do_shortcode( ' [accp_item type=main_pic] ' ); ?>
                                        <div class="ac-product-secondary-images left">
											<?php echo do_shortcode( ' [accp_item type=sec_pics] ' ); ?>
                                        </div>
                                    </div>
                                    <div class="col-7 ac-product-information">
                                        <h1 id="post-<?php the_ID(); ?> product-title"><?php the_title(); ?></h1>
										<?php echo do_shortcode( '[accp_item type=mfg]' ); ?>
                                        <p>Category: <?php $taxonomy = 'ac_product_cat';
												$post_terms          = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
												$separator           = ', ';
												if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
													$term_ids = implode( ',', $post_terms );
													$terms    = wp_list_categories( array(
														'title_li' => '',
														'style'    => 'none',
														'echo'     => false,
														'taxonomy' => $taxonomy,
														'include'  => $term_ids
													) );
													$terms    = rtrim( trim( str_replace( '<br />', $separator, $terms ) ), $separator );
													echo $terms;
												} ?>
                                        </p>
										<?php echo do_shortcode( '[accp_item type=sku]' ); ?>
										<?php echo do_shortcode( '[accp_item type=price]' ); ?>
										<?php echo do_shortcode( '[accp_item type=brochures]' ); ?>
										<?php echo do_shortcode( '[accp_item type=specsheets]' ); ?>
                                    </div>
                                </div>
                                <div class="row entry">
                                    <div class="col">
                                        <h2>Product Description</h2>
										<?php the_content(); ?>
                                    </div>
                                </div>
							<?php
							endwhile; ?>
						<?php else : ?>
                            <h2 class="center">Not Found</h2>
                            <p class="center"><?php _e( "Sorry, but you are looking for something that isn't here." ); ?></p>
						<?php endif; ?>
                </div>
				<?php
					if ( is_active_sidebar( 'ac_single_product_sidebar' ) ) : ?>
                        <div class="col-3 offset-1 ac-product-single-sidebar">
							<?php dynamic_sidebar( 'ac_single_product_sidebar' ); ?>
                        </div>
					<?php
					endif; //is_active_sidebar ?>
            </div> <!-- row -->
			<?php // get the custom post type's taxonomy terms
				$custom_taxterms = wp_get_object_terms( $post->ID, 'ac_product_cat', array( 'fields' => 'ids' ) );
				$args            = array(
					'posts_per_page' => 3, // you may edit this number
					'orderby'        => 'ASC',
					'tax_query'      => array(
						array(
							'taxonomy' => 'ac_product_cat',
							'field'    => 'id',
							'terms'    => $custom_taxterms
						)
					),
					'post__not_in'   => array( $post->ID ),
				);
				$related_items   = new WP_Query( $args );
				// loop over query
				if ( $related_items->have_posts() ) : ?>
                    <div class="row similar-products">
                        <div class="col">
                            <h3>Similar Products</h3>
                            <div class="row">
								<?php
									while( $related_items->have_posts() ) :
										$related_items->the_post(); ?>
                                        <div class="product post col-4">
                                            <a class="link-wrap" href="<?php the_permalink() ?>">
                                                <div class="product-image"
                                                     style="background-image: url('<?php echo do_shortcode( ' [accp_item type=pic_size_medium_url] ' ); ?>')"></div>
                                            </a>
                                            <h4 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>"
                                                                                rel="bookmark"
                                                                                title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                                            </h4>
                                            <small>
												<?php echo do_shortcode( '[accp_item type=mfg]' ); ?>
                                                <p>Category: <?php $taxonomy = 'ac_product_cat';
														$post_terms          = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
														$separator           = ', ';
														if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
															$term_ids = implode( ',', $post_terms );
															$terms    = wp_list_categories( array(
																'title_li' => '',
																'style'    => 'none',
																'echo'     => false,
																'taxonomy' => $taxonomy,
																'include'  => $term_ids
															) );
															$terms    = rtrim( trim( str_replace( '<br />', $separator, $terms ) ), $separator );
															echo $terms;
														} ?>
                                                </p>
                                            </small>
                                            <div class="entry">
												<?php the_excerpt(); ?>
                                                <a href="<?php the_permalink() ?>" class="view-product">View Product</a>
                                            </div>
                                        </div>
									<?php
									endwhile;
									wp_reset_postdata(); ?>
                            </div>
                        </div>
                    </div><!--  row similar-products -->
				<?php
				endif; ?>
        </div><!-- container -->
    </div><!-- wrapper ac-product-single -->
<?php get_footer(); ?>