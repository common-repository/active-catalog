<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	function accp_catalog_page_shortcode( $atts = [], $content = null, $tag = '' ) {
		$options          = get_option( 'ac_catalog_options', '' );
		$shortcode_output = '';
		if ( isset( $options ) && isset( $options['catalog-tpl'] ) ) {
			$paged    = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$args     = array(
				'post_type'      => 'ac_product',
				'posts_per_page' => get_option( 'posts_per_page' ),
				'paged'          => $paged
			);
			$wp_query = new WP_Query( $args );

			$ac_catalog_atts = shortcode_atts( [
				'container' => '',
				'classes'   => '',
			], $atts, $tag );

			if ( empty( $ac_catalog_atts['classes'] ) ) {
				$classes = 'product';
			} else {
				$classes = 'product ' . $ac_catalog_atts['classes'];
			}

			if ( empty( $ac_catalog_atts['container'] ) ) {
				$container_classes = 'ac-container';
			} else {
				$container_classes = $ac_catalog_atts['container'];
			}

			if ( $wp_query->have_posts() ) {
				$shortcode_output .= '
                <div class="' . $container_classes . '">
            ';
				while( $wp_query->have_posts() ) {
					$wp_query->the_post();
					$shortcode_output .= '<div class="' . $classes . '">' . do_shortcode( stripslashes( $options['catalog-tpl'] ) ) . '</div>';
				}
				$shortcode_output .= '
                </div>
                <div class="ac-pagination">
            ';
				$big              = 999999999; // need an unlikely integer
				$translated       = __( 'Page', 'mytextdomain' ); // Supply translatable string

				echo paginate_links( array(
					'base'               => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'             => '?paged=%#%',
					'current'            => max( 1, get_query_var( 'paged' ) ),
					'total'              => $wp_query->max_num_pages,
					'before_page_number' => '<span class="screen-reader-text">' . $translated . ' </span>'
				) );
				$shortcode_output .= '
                </div>
            ';
			}
			wp_reset_query();
		}

		return $shortcode_output;
	}

	add_shortcode( 'accp_catalog', 'accp_catalog_page_shortcode' );
