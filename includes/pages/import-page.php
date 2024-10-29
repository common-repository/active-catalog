<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	/**
	 * Add options page
	 */
	function accp_add_import_page_catalog() {
		add_submenu_page(
			'edit.php?post_type=ac_product',
			'import-csv',
			'Import csv',
			'manage_options',
			'import-csv',
			'accp_add_import_page_catalog_callback'
		);
	}

	// add_action('admin_menu', 'accp_add_import_page_catalog');

	/**
	 * Add options page Callback
	 */
	function accp_add_import_page_catalog_callback() {
		?>
        <div class="wrap">
            <h1>Import catalog</h1>
        </div>
		<?php
	}