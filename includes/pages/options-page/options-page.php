<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	// Add options page
	function accp_add_options_page_catalog() {
		add_submenu_page(
			'edit.php?post_type=ac_product',
			'Settings',
			'Settings',
			'manage_options',
			'catalog-settings',
			'accp_add_options_page_catalog_callback'
		);
	}

	add_action( 'admin_menu', 'accp_add_options_page_catalog' );

	function accp_add_options_page_catalog_callback() {
		$options = ! empty( get_option( 'ac_catalog_options' ) ) ? get_option( 'ac_catalog_options' ) : array();
		if ( isset( $_POST['ac_catalog_options'] ) ) {
			$postOptions = isset( $_POST['ac_catalog_options'] ) ? (array) $_POST['ac_catalog_options'] : array();
			$postOptions = array_map( 'sanitize_text_field', $postOptions );
			update_option( 'ac_catalog_options', array_merge( $options, $postOptions ) );
		}

		if ( isset( $_POST['saved'] ) && $_POST['saved'] === 'ok' ) : ?>
            <div id="message" class="updated notice notice-success is-dismissible products-options-notify">
                <p>Settings saved.</p>
                <button type="button" class="notice-dismiss"><span
                            class="screen-reader-text">Dismiss this notice.</span></button>
            </div>
		<?php endif; ?>
        <div class="wrap">
            <h1>AC Catalog settings</h1>
			<?php
				// Options page tabs
				require_once( ACCP_CATALOG_PLUGIN_URL . 'includes/pages/options-page/tabs.php' );

				// Main Settings
				require_once( ACCP_CATALOG_PLUGIN_URL . 'includes/pages/options-page/main.php' );

			?>
        </div>
		<?php
	}