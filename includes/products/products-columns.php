<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	/**
	 * Add thumbnail column to post table in admin panel
	 *
	 * @param $column
	 *
	 * @return array
	 */
	function accp_modify_post_table( $column ) {
		$product_arr = array( 'product_image' => '<i class="dashicons-before product-list-item"></i>Image' );
		$modify_arr  = array( 'modified' => '<i class="dashicons-before product-list-item"></i>Modified' );
		$sku_arr     = array( 'sku' => '<i class="mce-ico mce-i-icon product-list-item"></i>SKU' );
		$new_arr     = array();

		foreach ( $column as $key => $value ) {
			$new_arr[ $key ] = $value;
			if ( $key == 'cb' ) {
				$new_arr = $new_arr + $product_arr;
			}

			// Title
			if ( $key == 'title' ) {
				$new_arr[ $key ] = '<i class="dashicons-before product-list-item"></i>Name';
				$new_arr         = $new_arr + $sku_arr;
			}

			// Tags
			if ( $key == 'taxonomy-ac_product_tag' ) {
				$new_arr[ $key ] = '<i class="dashicons-before product-list-item"></i>Tags';
			}

			// Categories
			if ( $key == 'taxonomy-ac_product_cat' ) {
				$new_arr[ $key ] = '<i class="dashicons-before product-list-item"></i>Categories';
			}

			// Comments
			if ( $key == 'comments' ) {
				unset( $new_arr[ $key ] );
			}

			// Author
			if ( $key == 'author' ) {
				unset( $new_arr[ $key ] );
			}

			// Date
			if ( $key == 'date' ) {
				$new_arr[ $key . '_created' ] = '<i class="dashicons-before product-list-item"></i>Created';
				$new_arr                      = $new_arr + $modify_arr;
				unset( $column['date'] );
			}
		}

		return $new_arr;
	}

	add_filter( 'manage_ac_product_posts_columns', 'accp_modify_post_table' );

	/**
	 * Add content to columns in catalog table in admin panel
	 *
	 * @param $column_name
	 * @param $post_id
	 */
	function modify_post_table_row( $column_name, $post_id ) {
		// Image column
		if ( $column_name == 'product_image' ) {
			$product_image = get_post_meta( $post_id, '_ac_main_product_image', true );
			$product_image = json_decode( $product_image );

			if ( isset( $product_image ) && ! empty( $product_image ) ) {
				foreach ( $product_image as $value ) {
					$url = $value->image_sizes->thumbnail->url;
					echo '<img src="' . $url . '" alt="">';
				}
			} else {
				echo '<i class="thumb-if-not-image dashicons-before"></i>';
			}
		}

		// SKU column
		if ( $column_name == 'sku' ) {
			$ac_product_details = get_post_meta( $post_id, 'ac-product-details', true );

			if ( isset( $ac_product_details['sku'] ) &&
			     ! empty( $ac_product_details['sku'] ) ) {
				echo $ac_product_details['sku'];
			}
		}

		// Modified
		if ( $column_name == 'modified' ) {
			$ac_product_modified = get_post_meta( $post_id, 'ac-product-details', true );
			echo date( 'Y.m.d | H:i:s', get_post_modified_time( 'U', $post_id ) );
		}

		if ( $column_name == 'date_created' ) {
			$ac_product_created = get_the_date( 'Y.m.d | H:i:s', $post_id );
			echo $ac_product_created;
		}
	}

	add_action( 'manage_ac_product_posts_custom_column', 'modify_post_table_row', 10, 2 );

	/**
	 * Sortable
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	function modify_post_table_row_sortable( $columns ) {
		$columns['date_created'] = 'date_created';
		$columns['modified']     = 'modified';

		return $columns;
	}

	add_action( 'manage_edit-ac_product_sortable_columns', 'modify_post_table_row_sortable' );

	/**
	 * Remove default Date column from catalog
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	function accp_manage_columns( $columns ) {
		unset( $columns['date'] );

		return $columns;
	}

	/**
	 *
	 */
	function accp_column_init() {
		add_filter( 'manage_ac_product_posts_columns', 'accp_manage_columns' );
	}

	add_action( 'admin_init', 'accp_column_init' );
