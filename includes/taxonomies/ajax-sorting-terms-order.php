<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	// Sorting terms
	function accp_sorting_terms() {
		$sort_obj = isset( $_POST['sortObj'] ) ? (array) $_POST['sortObj'] : array();
		$sort_obj = array_map( 'sanitize_text_field', $sort_obj );

		foreach ( $sort_obj as $key => $value ) {
			update_term_meta( $key, 'order', $value );
		}

		wp_die();
	}

	add_action( 'wp_ajax_accp_sorting_terms', 'accp_sorting_terms' );