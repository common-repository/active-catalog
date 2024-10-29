<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	/**
	 * Upload CSV file
	 */
	function accp_import_catalog() {
		$csv_url   = isset( $_POST['csv_url'] ) ? esc_url_raw($_POST['csv_url']) : '';
		$row       = 0;
		$file      = fopen( $csv_url, "r" );
		$statistic = array();
		//	$created = 0;
		//	$updated = 0;
		$data = fgetcsv( $file );

		// If no user detection and no correct CSV first row structure
		if ( $data[0] != 'product' ||
		     $data[1] != 'sku' ||
		     $data[2] != 'tags' ||
		     $data[3] != 'categories' ||
		     $data[4] != 'brochures' ||
		     $data[5] != 'spec sheets' ||
		     $data[6] != 'manufacturer' ||
		     $data[7] != 'price' ||
		     $data[8] != 'content' ||
		     $data[9] != 'main image' ||
		     $data[10] != 'secondary images' ) {
			echo 'error';
			wp_die();
		}

		// Grap statistic
		while( ! feof( $file ) ) {
			$data = fgetcsv( $file );
			//		$post_exist = get_page_by_title( $data[0], OBJECT, 'ac_product');

			//		if ( $post_exist !== NULL ) {
			//			$updated++;
			//		} else {
			//			$created++;
			//		}

			$row ++;
		}

		$statistic = array(
			'total' => $row,
			//		'created' => $created,
			//		'updated' => $updated,
		);

		fclose( $file );

		wp_send_json( $statistic );

		wp_die();
	}

	add_action( 'wp_ajax_accp_import_catalog', 'accp_import_catalog' );

	// Confirm import after upload
	function accp_confirm_import_catalog() {
		$csv_url       = esc_url_raw($_POST['csv_url']);
		$row           = 0;
		$skipped       = 0;
		$items_created = 0;
		$file          = fopen( $csv_url, "r" );
		$data          = fgetcsv( $file );
		$user          = get_userdata( get_current_user_id() );

		// If no user detection and no correct CSV first row structure

		try {
			if ( ! $user ) {
				echo 'error';
				wp_die();
			}
			while( ! feof( $file ) ) {
				$data             = fgetcsv( $file );
				$title            = isset( $data[0] ) && ! empty( $data[0] ) ? sanitize_text_field($data[0]) : '';
				$title            = preg_replace( "/[^A-Za-z0-9 \/_=+*-]/", '', $title );
				$sku              = isset( $data[1] ) && ! empty( $data[1] ) ? sanitize_text_field( $data[1] ) : '';
				$tags             = isset( $data[2] ) && ! empty( $data[2] ) ? sanitize_text_field($data[2]) : '';
				$categories       = isset( $data[3] ) && ! empty( $data[3] ) ? sanitize_text_field($data[3]) : '';
				$categories       = explode( ';', $categories );
				$cat_terms        = array();
				$brochures        = isset( $data[4] ) && ! empty( $data[4] ) ? $data[4] : '';
				$spec_sheets      = isset( $data[5] ) && ! empty( $data[5] ) ? $data[5] : '';
				$manufacturer     = isset( $data[6] ) && ! empty( $data[6] ) ? sanitize_text_field($data[6]) : '';
				$price            = isset( $data[7] ) && ! empty( $data[7] ) ? sanitize_text_field($data[7]) : '';
				$content          = isset( $data[8] ) && ! empty( $data[8] ) ? $data[8] : '';
				$content          = preg_replace( "/[^A-Za-z0-9 \/_=+*-]/", '', $content );
				$main_image       = isset( $data[9] ) && ! empty( $data[9] ) ? esc_url($data[9]) : '';
				$secondary_images = isset( $data[10] ) && ! empty( $data[10] ) ? $data[10] : '';
				$secondary_images = explode( ',', $secondary_images );

				$id = get_page_by_title( $title, OBJECT, 'ac_product' );
				// TODO Validate also SKU, not only title, lloking for records to be updated.

				if ( $id != null ) {
					$id = $id->ID;
					$skipped ++;
				} else {
					$id = '';
					$items_created ++;
				}

				// Parse brochures
				$brochures           = accp_parce_complex_cvs( $brochures, 'Brochure' );
				$brochures_names_arr = array();
				foreach ( $brochures as $key => $value ) {
					$brochures_names_arr[] = sanitize_text_field($key);
				}

				// Parse spec sheets
				$spec_sheets           = accp_parce_complex_cvs( $spec_sheets, 'Spec Sheet' );
				$spec_sheets_names_arr = array();
				foreach ( $spec_sheets as $key => $value ) {
					$spec_sheets_names_arr[] = sanitize_text_field($key);
				}

				// Parse categories
				foreach ( $categories as $csv_field ) {
					if ( empty( $csv_field ) ) {
						continue;
					}

					$children_terms_pattern = '/\(.+\)/';
					$main_term_pattern      = '/^[^(]*/';

					preg_match( $main_term_pattern, $csv_field, $main_term_matches );
					preg_match_all( $children_terms_pattern, $csv_field, $children_matches );

					// Create main term if not exits
					$term = term_exists( $main_term_matches[0], 'ac_product_cat' );
					if ( ! is_array( $term ) || $term === 0 || $term === null ) {
						$term = wp_insert_term( sanitize_text_field($main_term_matches[0]), 'ac_product_cat' );
					}
					if ( is_array( $term ) ) {
						$term_id = $term['term_id'];
						array_push( $cat_terms, intval( $term_id ) );
					}

					// Create children term if not exits
					if ( ! empty( array_filter( $children_matches ) ) ) {
						if ( $children_matches[0][0] ) {
							$childrens_arr = explode( ',', trim( $children_matches[0][0], '()' ) );
							foreach ( $childrens_arr as $child_term ) {
								$child_post = term_exists( $child_term, 'ac_product_cat' );
								if ( ! is_array( $child_post ) || $child_post === 0 || $child_post === null ) {
									$child_post = wp_insert_term( sanitize_text_field($child_term), 'ac_product_cat', array( 'parent' => $term['term_id'] ) );
								}
								if ( is_array( $child_post ) ) {
									array_push( $cat_terms, intval( $child_post['term_id'] ) );
								}
							}
						}
					}
				}

				// Args for import
				$args = array(
					'ID'           => $id,
					'post_title'   => $title,
					'post_author'  => $user->user_login,
					'post_content' => $content,
					'post_status'  => 'publish',
					'post_type'    => 'ac_product',
					'tax_input'    => array(
						'ac_product_tag' => $tags,
					),
					'meta_input'   => array(
						'ac-product-details' => array(
							'sku'          => $sku,
							'manufacturer' => $manufacturer,
							'price'        => $price,
						)
					)

				);

				$post = wp_insert_post( $args );
				wp_set_object_terms( $post, $cat_terms, 'ac_product_cat' );

				// Files import functionality
				require( ACCP_CATALOG_PLUGIN_URL . 'includes/products/import-tax-files.php' );

				// Image import functionality
				$post_main_image        = get_post_meta( $post, '_ac_main_product_image', true );
				$post_additional_images = get_post_meta( $post, '_ac_additional_product_images', true );
				if ( ! empty( $post_main_image ) ) {
					$post_main_image = json_decode( $post_main_image );

					foreach ( $post_main_image as $key => $value ) {
						wp_delete_attachment( $value->image_id, true );
					}

					update_post_meta( $post, '_ac_main_product_image', '' );
				}

				if ( ! empty( $post_additional_images ) ) {
					$post_additional_images = json_decode( $post_additional_images );

					foreach ( $post_additional_images as $key => $value ) {
						wp_delete_attachment( $value->image_id, true );
					}

					update_post_meta( $post, '_ac_additional_product_images', '' );
				}

				require( ACCP_CATALOG_PLUGIN_URL . 'includes/products/import-main-image.php' );
				// Secondary images functionality
				require( ACCP_CATALOG_PLUGIN_URL . 'includes/products/import-secondary-images.php' );

				$row ++;

				global $wpdb;
				$duplicate_titles = $wpdb->get_col( "SELECT post_title FROM {$wpdb->posts} WHERE post_type = 'attachment' GROUP BY post_title HAVING COUNT(*) > 1" );
				foreach ( $duplicate_titles as $title ) {
					$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_title=%s", $title ) );
					foreach ( array_slice( $post_ids, 1 ) as $post_id ) {
						$wpdb->delete( $wpdb->posts, array( 'ID' => $post_id ) );
					}
				}
			}

			$statistic = array(
				'skipped'       => $skipped,
				'items_created' => $items_created,
			);

			fclose( $file );

			wp_send_json( $statistic );

			wp_die();
		} catch( Exception $e ) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}

	add_action( 'wp_ajax_accp_confirm_import_catalog', 'accp_confirm_import_catalog' );