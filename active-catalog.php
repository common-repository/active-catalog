<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	/**
	 * Plugin Name: Active Catalog
	 * Plugin URI: https://activeiq.co/activecatalog-plugin/
	 * Description: AIQ Product Catalog for WordPress
	 * Version: 1.3
   * Requires at least: 6.3.2
	 * Author: ActiveIQ
	 * Author URI: https://www.activeiq.co/
	 **/

	define( 'ACCP_CATALOG_PLUGIN_VERSION', '1.2' );
	define( 'ACCP_CATALOG_PLUGIN_NAME', 'Active Catalog ' . ACCP_CATALOG_PLUGIN_VERSION );
	define( 'ACCP_CATALOG_PLUGIN_URL', plugin_dir_path( __FILE__ ) );

	if ( ! defined( 'ACCP_CATALOG_LOG_ACTIVITY' ) ) {
		define( 'ACCP_CATALOG_LOG_ACTIVITY', true );
	}
	if ( ! defined( 'ACCP_CATALOG_IL8N_TEXT_DOMAIN' ) ) {
		define( 'ACCP_CATALOG_IL8N_TEXT_DOMAIN', 'ac' );
	}

	add_action( 'admin_enqueue_scripts', 'accp_catalog_add_resources' );
	/**
	 * Create the menu page
	 */
	function accp_catalog_add_resources() {
		wp_enqueue_style( 'ac_css.css', plugins_url( 'ac_css.css', __FILE__ ) );
	}

	register_uninstall_hook( __FILE__, 'accp_catalog_uninstall' );
	/**
	 * Event when plugin is uninstalled
	 */
	function accp_catalog_uninstall() {
	}

	register_activation_hook( __FILE__, 'accp_catalog_activate' );
	/**
	 * Event when plugin is activated
	 */
	function accp_catalog_activate() {

	}

	register_deactivation_hook( __FILE__, 'accp_catalog_deactivate' );
	/**
	 * Event when plugin is deactivated
	 */
	function accp_catalog_deactivate() {
	}

	/**
	 * @param $name
	 * @param $value
	 */
	function accp_catalog_save_or_update_option( $name, $value ) {
		$exists_option = ( get_option( $name, null ) != null );
		if ( $exists_option ) {
			update_option( $name, $value );
		} else {
			add_option( $name, $value );
		}
	}

	$plugin = plugin_basename( __FILE__ );
	add_filter( "plugin_action_links_$plugin", 'accp_catalog_plugin_settings_link' );
	/**
	 * Add settings link on plugin page
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	function accp_catalog_plugin_settings_link( $links ) {
		$settings_link = '<a href="' . menu_page_url( 'catalog-settings', false ) . '">Settings</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Debug tool
	 */
	if ( ! function_exists( 'pr' ) ) {
		function pr( $val ) {
			echo '<pre class="debug-tool">';
			print_r( $val );
			echo '</pre>';
		}
	}
	if ( ! function_exists( 'pra' ) ) {
		function pra( $val ) {
			echo '<pre class="debug-tool">';
			print_r( $val, true );
			echo '</pre>';
		}
	}

	// Plugin Functions
	require_once( ACCP_CATALOG_PLUGIN_URL . 'includes/plugin-functions.php' );
	// Products functions
	require_once( ACCP_CATALOG_PLUGIN_URL . 'includes/products/products-columns.php' );
	// Taxonomies custom fields
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/taxonomies/ac-category-columns.php" );
	require_once( ACCP_CATALOG_PLUGIN_URL . 'includes/taxonomies/taxonomy-fields.php' );
	require_once( ACCP_CATALOG_PLUGIN_URL . 'includes/taxonomies/ajax-sorting-terms-order.php' );
	// Metaboxes structure
	require_once( ACCP_CATALOG_PLUGIN_URL . 'includes/metaboxes/images-box/images-metabox-view.php' );
	require_once( ACCP_CATALOG_PLUGIN_URL . 'includes/metaboxes/post-details/post-details-metabox-view.php' );
	require_once( ACCP_CATALOG_PLUGIN_URL . 'includes/metaboxes/meta-description/meta-description.php' );
	// Recreate metaboxes
	require_once( ACCP_CATALOG_PLUGIN_URL . 'includes/metaboxes/recreate-metaboxes.php' );
	// Update post in admin panel with metaboxes fields like meta_key -> meta_value
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/metaboxes/post-update-after-all.php" );
	// Admin search results page
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/admin-search/search-results-page.php" );
	// Add catalog options page
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/pages/options-page/options-page.php" );
	// Add custom filter to catalog listing
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/admin-filter/admin-filter.php" );
	// Add delete & transfer parts
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/taxonomies/delete-term-transfer-products.php" );
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/taxonomies/detele-term-transfer-page.php" );
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/taxonomies/delete-transfer-ajax.php" );
	// Importer
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/products/import-csv.php" );
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/pages/import-page.php" );
	// Shortcodes
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/shortcodes/catalog-tpl-shortcodes.php" );
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/shortcodes/associated-products-shortcodes.php" );
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/shortcodes/ac-item-shortcodes.php" );
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/shortcodes/ac-item-permalink-shortcodes.php" );

	// Custom fields.
	require_once( ACCP_CATALOG_PLUGIN_URL . "includes/custom-fields/products.php" );

	function accp_plugin_add_new_entities() {
		/**
		 * MODEL
		 */
		require_once( ACCP_CATALOG_PLUGIN_URL . 'model/Products.php' );
		require_once( ACCP_CATALOG_PLUGIN_URL . 'model/Products-tags.php' );
		require_once( ACCP_CATALOG_PLUGIN_URL . 'model/Products-categories.php' );

		/**
		 * Init Dependences
		 */
		require_once( ACCP_CATALOG_PLUGIN_URL . 'includes/dependences/dependences-functions.php' );
	}

	add_action( 'init', 'accp_plugin_add_new_entities' );

	/**
	 * Styles & Scripts for Frontend
	 */
	function accp_add_frontend_assets() {
		// Register styles for frontend
		wp_register_style( 'accp_frontend_style', plugins_url( 'assets/css/ac-frontend.css', __FILE__ ) );

		// Enqueue styles for frontend
		wp_enqueue_style( 'accp_frontend_style' );
	}

	add_action( 'wp_enqueue_scripts', 'accp_add_frontend_assets' );

	/**
	 * Styles & Scripts for Admin
	 */
	function accp_add_admin_assets() {
		// Register styles for admin
		wp_register_style( 'ac-admin', plugins_url( 'assets/css/ac-admin.css', __FILE__ ) );
		wp_register_style( 'ac-code-mirror-style', "/wp-includes/js/codemirror/codemirror.min.css" );
		wp_register_style( 'ac-admin-mediaqueries', plugins_url( 'assets/css/ac-admin-mediaqueries.css', __FILE__ ) );

		// Rsgister scripts for admin
		wp_register_script( 'ac-metaboxes', plugins_url( 'assets/js/ac-metaboxes.js', __FILE__ ) );
		wp_register_script( 'ac-main', plugins_url( 'assets/js/ac-main.js', __FILE__ ), array(
			'ac-code-mirror-script',
			'ac-code-mirror-css-highlight'
		) );
		wp_register_script( 'ac-import', plugins_url( 'assets/js/ac-import.js', __FILE__ ) );
		wp_register_script( 'ac-code-mirror-script', "/wp-includes/js/codemirror/codemirror.min.js", array( 'mce-view' ) );
		wp_register_script( 'ac-code-mirror-css-highlight', plugins_url( 'assets/js/codemirror-css.js', __FILE__ ), array( 'mce-view' ) );

		// Enqueue styles for admin
		wp_enqueue_style( 'jquery-ui-sortable' );
		wp_enqueue_style( 'ac-admin' );
		wp_enqueue_style( 'ac-admin-mediaqueries' );
		wp_enqueue_style( 'ac-code-mirror-style' );

		// Enqueue scripts for admin
		wp_enqueue_script( 'underscore' );
		wp_enqueue_script( 'ac-metaboxes' );
		wp_enqueue_script( 'ac-main' );
		wp_enqueue_script( 'ac-import' );
		wp_enqueue_script( 'ac-code-mirror-script' );
		wp_enqueue_script( 'ac-code-mirror-css-highlight' );
		wp_enqueue_script( 'jquery-ui-tabs' );

		// Ajax URL
		wp_localize_script( 'ac-metaboxes', 'acPlugin', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		// Media lib scripts
		wp_enqueue_media();
	}

	add_action( 'admin_enqueue_scripts', 'accp_add_admin_assets' );

  function accp_product_image_sizes() {
    add_image_size('ac_product_image', 640, 480, true);
  }
  add_action( 'init', 'accp_product_image_sizes' );

