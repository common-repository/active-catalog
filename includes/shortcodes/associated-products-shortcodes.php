<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	function accp_catalog_related_items_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'max'  => '3',
			'sort' => 'ASC',
		), $atts, 'accp_item_related_items' );
		global $post;
		$options       = get_option( 'ac_catalog_options', '' );
		$catalog_tpl   = isset( $options['catalog-tpl'] ) && ! empty( $options['catalog-tpl'] ) ? $options['catalog-tpl'] : '';
		$related_terms = get_the_terms( $post, 'ac_product_cat' );

		if ( ! empty( $related_terms ) ) {
			$related_terms_slugs = array();
			foreach ( $related_terms as $key => $value ) {
				$related_terms_slugs[] = $value->slug;
			}
		} else {
			$related_terms_slugs = array();
		}

		$content          = '
        <div class="ac_catalog_associated_products">
    ';
		$args             = array(
			'post_type'      => 'ac_product',
			'post__not_in'   => array( $post->ID ),
			'posts_per_page' => $atts['max'],
			'tax_query'      => array(
				array(
					'taxonomy' => 'ac_product_cat',
					'field'    => 'slug',
					'terms'    => $related_terms_slugs
				)
			)
		);
		$wp_query         = new WP_Query( $args );
		$shortcode_output = '';

		if ( $wp_query->have_posts() ) {
			$shortcode_output .= '
                <div class="ac_catalog_associated_products">
                    <div class="ac-container">
            ';
			while( $wp_query->have_posts() ) {
				$wp_query->the_post();
				$shortcode_output .= '
                        <div class="ac-product-box">'
				                     . do_shortcode( $catalog_tpl ) .
				                     '</div>';
			}
			$shortcode_output .= '
                    </div>
                </div>
            ';
		}
		wp_reset_query();

		return $shortcode_output;
	}

	add_shortcode( 'accp_item_related_items', 'accp_catalog_related_items_shortcode' );
