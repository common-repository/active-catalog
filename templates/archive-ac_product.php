<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}
	$options = get_option( 'ac_catalog_options', '' ); ?>
<?php get_header(); ?>
    <div class="wrapper ac-product-archive">
        <div class="container">
            <div class="row">
                <div class="col<?php if ( is_active_sidebar( 'ac_catalog_sidebar' ) ) {
					echo( '-8' );
				} ?>">
					<?php if ( have_posts() ) : ?>
                        <div class="row ac-product-navigation">
                            <div class="col">
								<?php echo accp_product_pagination(); ?>
                            </div>
                        </div>
                        <div class="row stretch products-wrapper">
							<?php
								while( have_posts() ) :
									the_post(); ?>
                                    <div class="product post col-6">
                                        <a class="link-wrap" href="<?php the_permalink() ?>">
                                            <div class="product-image"
                                                 style="background-image: url('<?php echo do_shortcode( '[accp_item type=pic_size_medium_url]' ); ?>');">
                                            </div>
                                        </a>
                                        <h2 id="post-<?php the_ID(); ?>">
                                            <a href="<?php the_permalink() ?>" rel="bookmark"
                                               title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <small>
                                            <p style="margin-bottom: 0;">
												<?php echo do_shortcode( '[accp_item type=mfg]' ); ?></p>
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
								<?php endwhile; ?>
                        </div>
                        <div class="row ac-product-navigation">
                            <div class="col">
								<?php echo accp_product_pagination(); ?>
                            </div>
                        </div>
					<?php else : ?>
                        <div class="row no-products">
                            <div class="col">
                                <h2 class="center">Not Found</h2>
                                <p class="center"><?php _e( "Sorry, but you are looking for something that isn't here." ); ?></p>
                            </div>
                        </div>
					<?php endif; ?>
                </div> <!-- col -->
				<?php
					if ( is_active_sidebar( 'ac_catalog_sidebar' ) ) : ?>
                        <div class="col-3 offset-1 ac-product-archive-sidebar">
							<?php dynamic_sidebar( 'ac_catalog_sidebar' ); ?>
                        </div>
					<?php
					endif; //is_active_sidebar
				?>
            </div> <!-- row -->
        </div> <!-- container -->
    </div> <!-- wrapper ac-product-archive -->
<?php get_footer(); ?>