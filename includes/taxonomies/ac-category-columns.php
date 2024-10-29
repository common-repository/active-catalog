<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	// Add thumbnail column to terms table for ac_product_cat taxonomy in admin panel
	function accp_modify_ac_product_cat_table( $column ) {
		$product_arr = array( 'image' => 'Image' );
		$new_arr     = array();

		foreach ( $column as $key => $value ) {
			$new_arr[ $key ] = $value;
			if ( $key == 'cb' ) {
				$new_arr = $new_arr + $product_arr;
			}
		}

		return $new_arr;
	}

	add_filter( 'manage_edit-ac_product_cat_columns', 'accp_modify_ac_product_cat_table' );

	// Add thumbnail image to thumbnail column in post table in admin panel
	function accp_modify_ac_product_cat_table_row( $empty, $column_name, $term_id ) {
		if ( $column_name == 'image' ) {
			$cat_image = get_term_meta( $term_id, 'ac_attachment_file' );

			if ( ! empty( $cat_image[0] ) ) {
				echo '<span class="helper"></span><img src="' . $cat_image[0] . '" alt="" class="term-column-thumb">';
			} else {
				echo '<i class="thumb-if-not-image dashicons-before dashicons-format-image"></i>';
			}
		}
	}

	add_action( 'manage_ac_product_cat_custom_column', 'accp_modify_ac_product_cat_table_row', 10, 3 );