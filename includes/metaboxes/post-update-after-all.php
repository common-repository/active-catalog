<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	/**
	 * After we checked some terms we need them associate with post after post "Update"
	 */
	function accp_associate_terms_after_post_updated( $post_id ) {
		// pr($_POST); exit;

		// Main Image
		if ( isset( $_POST['ac-main-product-image'] ) ) {
			$mainImage = str_replace( '\\', '', $_POST['ac-main-product-image'] );
			$mainImage = str_replace( "'", '"', $mainImage );
			array_walk_recursive( json_decode( $mainImage, true ), function( &$value, $key ) {
				if ( ! is_array( $value ) && ( $key === 'url' || $key === 'image_url' ) ) {
					$value = esc_url_raw( $value );
				} else if ( ! is_array( $value ) ) {
					$value = sanitize_text_field( $value );
				}
			} );
			update_post_meta( $post_id, '_ac_main_product_image', $mainImage );
		}

		// Additional Images
		if ( isset( $_POST['ac-additional-product-images'] ) ) {
			$additionalImages = str_replace( '\\', '', $_POST['ac-additional-product-images'] );
			$additionalImages = str_replace( "'", '"', $additionalImages );
			array_walk_recursive( json_decode( $additionalImages, true ), function( &$value, $key ) {
				if ( ! is_array( $value ) && ( $key === 'url' || $key === 'image_url' ) ) {
					$value = esc_url_raw( $value );
				} else if ( ! is_array( $value ) ) {
					$value = sanitize_text_field( $value );
				}
			} );
			update_post_meta( $post_id, '_ac_additional_product_images', $additionalImages );
		}

		// Product details
		if ( isset( $_POST['ac-product-details'] ) ) {
			$productDetails                     = array();
			$productDetails['sku']              = sanitize_text_field( $_POST['ac-product-details']['sku'] );
			$productDetails['manufacturer']     = sanitize_text_field( $_POST['ac-product-details']['manufacturer'] );
			$productDetails['price']            = sanitize_text_field( $_POST['ac-product-details']['price'] );
			$productDetails['meta-description'] = sanitize_text_field( $_POST['ac-product-details']['meta-description'] );
			update_post_meta( $post_id, 'ac-product-details', $productDetails );
		}
	}

	add_action( 'save_post', 'accp_associate_terms_after_post_updated', 10 );