<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	// Delete meta fields after attachment delete
	function accp_tax_delete_attachment( $attachment_id ) {
		$attachment_url = wp_get_attachment_url( $attachment_id );

		$terms = get_terms( array(
			'taxonomy'   => array(
				'ac_product_cat',
			),
			'hide_empty' => false,
		) );

		foreach ( $terms as $value ) {
			$attachment_url_meta = get_term_meta( $value->term_id, 'ac_attachment_file', true );

			if ( $attachment_url_meta == $attachment_url ) {
				update_term_meta( $value->term_id, 'ac_attachment_file', '' );
			}
		}
	}

	add_action( 'delete_attachment', 'accp_tax_delete_attachment' );