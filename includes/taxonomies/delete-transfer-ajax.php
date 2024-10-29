<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	function accp_delete_transfer_action() {
		$posts = isset( $_POST['posts'] ) ? (array) $_POST['posts'] : array();
		$posts = array_map( 'sanitize_text_field', $posts );

		$cats = isset( $_POST['cats'] ) ? (array) $_POST['cats'] : array();
		$cats = array_map( 'sanitize_text_field', $cats );

		if ( ! empty( $posts ) && ! empty( $cats ) ) {
			function wpm_int( $n ) {
				return intval( $n );
			}

			$new_cats_arr  = array_map( 'wpm_int', $cats );
			$new_posts_arr = array_map( 'wpm_int', $posts );

			// Trasnfer all products to selected cats
			foreach ( $new_posts_arr as $post ) {
				wp_set_object_terms( $post, $new_cats_arr, 'ac_product_cat', true );
			}

			// Delete term
			if ( isset( $_POST['deleting'] ) && ! empty( $_POST['deleting'] ) ) {
				wp_delete_term( intval( $_POST['deleting'] ), 'ac_product_cat' );
			}
		}

		wp_die();
	}

	add_action( 'wp_ajax_accp_delete_transfer_action', 'accp_delete_transfer_action' );