<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	function accp_product_permalink_shortcodes( $atts, $content = null ) {
		global $post, $wpdb;
		$global_post       = $post;
		$global_post_id    = $post->ID;
		$atts              = shortcode_atts( array(
			'id'    => $global_post_id,
			'class' => '',
			'rel'   => '',
			'title' => __( 'Permanent Link to ', 'ac' ),
		), $atts, 'accp_item' );
		$options           = get_option( 'ac_catalog_options', '', true );
		$results           = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value LIKE '%{$atts['id']}%'", ARRAY_A );
		$shortcode_post_id = ( ! empty( $results[0]['post_id'] ) ) ? $results[0]['post_id'] : '';
		$shortcode_output  = '';
		$post_type         = get_post_type( $post );
		$xtra              = ' ';

		if ( $global_post_id != $atts['id'] ) {
			if ( $atts['id'] == __( '{Please', 'ac' ) ) {
				return __( 'Please enter a SKU for this shortcode', 'ac' ) . '</br>';
			} elseif ( empty( $results ) ) {
				return __( "This product SKU doesn't exist", 'ac' ) . "</br>";
			}
		}

		// Is it POST & PAGE or Options page
		if ( $global_post_id != $atts['id']
		     && $atts['type'] != 'currency'
		     && ( ! empty( $shortcode_post_id ) || $post->post_type !== 'ac_product' ) ) {
			$post      = get_post( $shortcode_post_id );
			$post_meta = get_post_meta( $shortcode_post_id, 'ac-product-details', true );
			$permalink = get_permalink( $shortcode_post_id );
			$title     = get_the_title( $shortcode_post_id );
			$out_id    = $shortcode_post_id;
		} else {
			$post_meta = get_post_meta( $post->ID, 'ac-product-details', true );
			$permalink = get_the_permalink( $post->ID );
			$title     = get_the_title( $post->ID );
			$out_id    = $post->ID;
		}

		$atts['title'] .= $title;

		// Permalink
		if ( isset( $post_meta['sku'] ) && isset( $content ) && ! empty( $content ) ) {
			$class = preg_replace( "![^#a-z0-9]+!i", "-", $post_meta['sku'] );
			if ( ! empty( $atts['class'] ) ) {
				$class .= ' ' . $atts['class'];
			}
			if ( ! empty( $atts['rel'] ) ) {
				$xtra .= 'rel="' . $atts['rel'] . '" ';
			}
			if ( ! empty( $atts['title'] ) ) {
				$xtra .= 'title="' . $atts['title'] . '" ';
			}
			$shortcode_output .= '<a data-atts-id="' . $out_id . '" data-shortcodes-id="' . $out_id . '" href="' . $permalink . '" class="ac_pc_item_permalink_' . $class . '"' . $xtra . '>' . do_shortcode( $content ) . '</a>';
		}

		return $shortcode_output;
	}

	add_shortcode( 'accp_permalink', 'accp_product_permalink_shortcodes' );
