<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	/**
	 * Change the featured image metabox title text
	 */
	function accp_recreate_metaboxes() {
		// Metabox 'Product Image'
		remove_meta_box( 'postimagediv', 'ac_product', 'side' );
		add_meta_box( 'postimagediv', __( 'Product images', 'ac' ), 'accp_product_images_meta_box', 'ac_product', 'side' );

		// Metabox 'Product Options'
		add_meta_box( 'product-detailsdiv', __( 'Product details', 'ac' ), 'accp_productdetails', 'ac_product', 'normal', 'high' );

		// Metabox 'Product Options'
		add_meta_box( 'product-metainfodiv', __( 'Product meta-description', 'ac' ), 'accp_set_metadescription', 'ac_product', 'normal', 'high' );
	}

	add_action( 'do_meta_boxes', 'accp_recreate_metaboxes', 10 );
