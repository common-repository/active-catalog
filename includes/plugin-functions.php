<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	add_action( 'widgets_init', 'accp_plugin_widgets_init' );
	/**
	 * Register widget sidebars
	 */
	function accp_plugin_widgets_init() {
		register_sidebar( array(
			'name'          => 'Catalog Sidebar',
			'id'            => 'ac_catalog_sidebar',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="ac-catalog-title">',
			'after_title'   => '</h4>',
		) );
		register_sidebar( array(
			'name'          => 'Single Product Sidebar',
			'id'            => 'ac_single_product_sidebar',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="ac-catalog-title">',
			'after_title'   => '</h4>',
		) );
	}

	/**
	 * Create pagination function for archives
	 *
	 * @param string $pages
	 * @param int $range
	 */
	function accp_product_pagination( $pages = '', $range = 2 ) {
		$showitems = ( $range * 2 ) + 1;
		global $paged;
		if ( empty( $paged ) ) {
			$paged = 1;
		}
		if ( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if ( ! $pages ) {
				$pages = 1;
			}
		}
		if ( 1 != $pages ) {
			echo "<div class=\"ac-pagination\"><p>";

			global $wp_query;
			$page  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$ppp   = get_query_var( 'posts_per_page' );
			$end   = $ppp * $page;
			$start = $end - $ppp + 1;
			$total = $wp_query->found_posts;
			if ( $end < $total ) {
				echo "<span class='sor'>Showing $start - $end of $total results:</span>";
			}
			if ( $wp_query->max_num_pages == get_query_var( 'paged' ) ) {
				echo "<span class='sor'>Showing $start - $total of $total results</span>";
			}

			if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
				echo "<a href='" . get_pagenum_link( 1 ) . "' title='First Page'>&laquo;</a>";
			}
			if ( $paged > 1 && $showitems < $pages ) {
				echo "<a href='" . get_pagenum_link( $paged - 1 ) . "' title='Previous Page'>&lsaquo;</a>";
			}

			for ( $i = 1; $i <= $pages; $i ++ ) {
				if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
					echo ( $paged == $i ) ? "<a class=\"current\">" . $i . "</a>" : "<a href='" . get_pagenum_link( $i ) . "' class=\"inactive\">" . $i . "</a>";
				}
			}

			if ( $paged < $pages && $showitems < $pages ) {
				echo "<a href=\"" . get_pagenum_link( $paged + 1 ) . "\" title='Next Page'>&rsaquo;</a>";
			}
			if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
				echo "<a href='" . get_pagenum_link( $pages ) . "' title='Last Page'>&raquo;</a>";
			}
			echo "</p></div>\n";
		}
	}

	add_filter( 'admin_post_thumbnail_html', 'accp_change_featured_image_text' );
	/*
	 * Change the featured image metabox link text
	 */
	function accp_change_featured_image_text( $content ) {
		if ( 'ac_product' === get_post_type() ) {
			$content = str_replace( 'Set featured image', __( 'Set product image', 'ac' ), $content );
			$content = str_replace( 'Remove featured image', __( 'Remove product image', 'ac' ), $content );
		}

		return $content;
	}

	add_filter( 'gettext', 'accp_change_image_button', 10, 3 );
	/**
	 * Change button text in Media uploader
	 *
	 * @param $translation
	 * @param $text
	 * @param $domain
	 *
	 * @return string
	 */
	function accp_change_image_button( $translation, $text, $domain ) {
		if ( $text == 'Insert into post' ) {
			// Once is enough.
			remove_filter( 'gettext', 'accp_change_image_button' );

			return 'Use this';
		}

		return $translation;
	}

	add_filter( 'views_edit-ac_product', 'accp_add_import_button', 10 );
	/**
	 * Add import button in main products page
	 *
	 * @param $default
	 *
	 * @return mixed
	 */
	function accp_add_import_button( $default ) {
		if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'ac_product' ) {
			$string = '<div class="import-btn-container">';
			$string .= '    <a href="" class="button button-primary button-large ac-add-catalog">Import catalog</a><span class="spinner"></span>';
			$string .= '     <p><a download="CSVTemplateImport.csv" href="' . plugins_url( 'CSVTemplateImport.csv', dirname( __FILE__ ) ) . '" style="text-decoration: none;">Download CSV Template</a></p>';
			$string .= '</div>';
			$string .= '<div class="ac-import-popup">';
			$string .= '    <div class="import-popup-inner">';
			$string .= '        <i class="dashicons dashicons-no-alt"></i>';
			$string .= '        <div class="import-infobox">';
			$string .= '       </div>';
			$string .= '       <div class="button-box">';
			$string .= '			<p class="warning-popup-message">Please don\'t close this popup before the import is done as it will be interrupted.</p>';
			$string .= '           <span class="spinner"></span>';
			$string .= '           <button class="button button-primary button-large js-confirm-import">Confirm import</button>';
			$string .= '           <button class="button button-primary button-large js-cancel-import">Cancel</button>';
			$string .= '           <button class="button button-primary button-large js-close-import">Close</button>';
			$string .= '      </div>';
			$string .= '   </div>';
			$string .= '</div>';
			echo $string;
		}

		return $default;
	}

	/**
	 * Add search ability to custom columns
	 *
	 * @param $query
	 */
	function accp_search_meta_products_in_lists( $query ) {
		if ( is_admin() && ! empty( $query->get( 's' ) ) ) {
			$variable_to_send = isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
			wp_redirect( get_bloginfo( 'url' ) . '/wp-admin/options.php?page=ac-search&s=' . $variable_to_send );
			exit;
		}
	}

	add_action( 'pre_get_posts', 'accp_search_meta_products_in_lists' );

	/**
	 * Parse complex CSV fields for import procedure
	 *
	 * @param $field
	 * @param string $title
	 *
	 * @return array
	 */
	function accp_parce_complex_cvs( $field, $title = '' ) {
		preg_match_all( '/[^,]*[=>][^,]*/', $field, $matches );
		$field_arr       = array();
		$brochures_names = array();

		if ( sizeof( $matches[0] ) > 0 ) {
			foreach ( $matches[0] as $el ) {
				preg_match( '/^[^=>]*/', trim( $el ), $el_key );
				preg_match( '/[^=>]*$/', trim( $el ), $el_value );

				if ( isset( $el_key[0] ) && ! empty( $el_key[0] ) ) {
					$field_arr[ trim( $el_key[0] ) ] = isset( $el_value[0] ) ? trim( $el_value[0] ) : '';
				}
			}
		} else {
			preg_match_all( '/[^,]*[^,]*/', $field, $matches2 );
			foreach ( $matches2[0] as $el2 ) {
				$el2 = trim( $el2 );
				if ( isset( $el2 ) && ! empty( $el2 ) ) {
					$field_arr[ $title ] = $el2;
				}
			}
		}

		return $field_arr;
	}

	/**
	 * Get Attachement id from a src value
	 *
	 * @param $image_src
	 *
	 * @return bool|string|null
	 */
	function get_attachment_id_from_src( $image_src ) {
		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
		$id    = $wpdb->get_var( $query );

		return ! empty( $id ) ? $id : false;
	}

	/**
	 * Get modify date for post by post id
	 *
	 * @param $id
	 *
	 * @return bool|string|null
	 */
	function get_post_modify_date_by_id( $id ) {
		global $wpdb;
		$query       = "SELECT post_modified FROM {$wpdb->posts} WHERE ID='$id'";
		$modify_date = $wpdb->get_var( $query );

		return ! empty( $modify_date ) ? $modify_date : false;
	}

	/**
	 * Encode Urls Rules
	 *
	 * @param $string
	 *
	 * @return mixed
	 */
	function myUrlEncode( $string ) {
		$entities     = array(
			'%21',
			'%2A',
			'%27',
			'%28',
			'%29',
			'%3B',
			'%3A',
			'%40',
			'%26',
			'%3D',
			'%2B',
			'%24',
			'%2C',
			'%2F',
			'%3F',
			'%25',
			'%23',
			'%5B',
			'%5D'
		);
		$replacements = array(
			'!',
			'*',
			"'",
			"(",
			")",
			";",
			":",
			"@",
			"&",
			"=",
			"+",
			"$",
			",",
			"/",
			"?",
			"%",
			"#",
			"[",
			"]"
		);

		return str_replace( $entities, $replacements, urlencode( $string ) );
	}

	/**
	 * Change dinamically secondary images order if order 0 for all
	 *
	 * @param $image_order
	 * @param $arr
	 *
	 * @return int
	 */
	function accp_recursive_cheking( $image_order, $arr ) {
		if ( array_key_exists( $image_order, $arr ) ) {
			$image_order += 1;

			return accp_recursive_cheking( $image_order, $arr );
		} else {
			return $image_order;
		}
	}

	add_action( 'init', 'create_new_url_querystring' );
	/**
	 *
	 */
	function create_new_url_querystring() {
		add_rewrite_tag( '%sku-postname%', '([^/]*)' );

		add_rewrite_rule(
			'^products/([^/]*)_([^/]*)_([^/]*)$',
			'index.php?sku-postname=$matches[1]=$matches[2]&products=$matches[3]',
			'top'
		);

		add_rewrite_rule(
			'^products/([^/]*)$',
			'index.php?products=$matches[1]',
			'top'
		);

		flush_rewrite_rules();

		global $wp_query;
	}

	add_action( 'save_post', 'accp_add_query_vars' );
	/**
	 * @param $id
	 */
	function accp_add_query_vars( $id ) {
		global $wp_query;

		$ac_product_details = get_post_meta( $id, 'ac-product-details', true );

		if ( isset( $ac_product_details['sku'] ) && ! empty( $ac_product_details['sku'] ) ) {
			$sku = $ac_product_details['sku'];
			set_query_var( 'sku', $sku );
		} else {
			set_query_var( 'sku', '' );
		}
	}

	add_filter( 'post_type_link', 'accp_get_perm_html', 1, 3 );
	add_filter( 'post_link', 'accp_get_perm_html', 1, 3 );
	/**
	 * Refactor the permalink for products
	 *
	 * @param $permalink
	 * @param $post
	 * @param $leavename
	 *
	 * @return mixed|string|string[]|void|null
	 */
	function accp_get_perm_html( $permalink, $post, $leavename ) {
		if ( $post->post_type === 'ac_product' ) {
			$ac_product_details = get_post_meta( $post->ID, 'ac-product-details', true );

			if ( isset( $ac_product_details['sku'] ) && ! empty( $ac_product_details['sku'] ) ) {
				$sku = $ac_product_details['sku'];
			} else {
				$sku = false;
			}

			$permalink = str_replace( '%sku-postname%', $post->post_name, $permalink );

			if ( ! $sku ) {
				if ( $post->post_type === 'ac_product' ) {
					return site_url( 'products/' . $post->post_name );
				}

				$permalink = preg_replace( '/\/__/', '/', $permalink );

				return site_url( $post->post_name );
			}

			return site_url( 'products/' . $post->post_name );
		}

		return $permalink;
	}

	/**
	 * Template files for archive and single views
	 *
	 * @param $template
	 *
	 * @return string
	 */
	function accp_plugin_templates( $template ) {
		$taxonomies = array( 'ac_product_cat', 'ac_product_tag' );
		if ( is_post_type_archive( 'ac_product' ) || is_tax( $taxonomies ) && file_exists( plugin_dir_path( __FILE__ ) . '../templates/archive-ac_product.php' ) ) {
			$template = plugin_dir_path( __FILE__ ) . '../templates/archive-ac_product.php';
		}
		if ( is_singular( 'ac_product' ) && file_exists( plugin_dir_path( __FILE__ ) . '../templates/single-ac_product.php' ) ) {
			$template = plugin_dir_path( __FILE__ ) . '../templates/single-ac_product.php';
		}

		return $template;
	}

	add_filter( 'template_include', 'accp_plugin_templates' );

	/**
	 * Add mera description to products when available
	 */
	function accp_catalog_add_meta_description() {
		global $post;
		if ( $post != null ) {
			$ac_product_details = get_post_meta( $post->ID, 'ac-product-details', true );

			if ( isset( $ac_product_details['meta-description'] ) && ! empty( $ac_product_details['meta-description'] ) ) {
				?>
                <meta name="description" content="<?php echo $ac_product_details['meta-description']; ?>">
				<?php
			}
		}
	}

	add_action( 'wp_head', 'accp_catalog_add_meta_description' );

	/**
	 * Fallback if ACF is not installed, prevents site from breaking.
	 *
	 * @param $field
	 * @param $post
	 *
	 * @return array|mixed
	 */
	function accp_get_field( $field, $post ) {
		if ( function_exists( 'get_field' ) ) {
			return get_field( $field, $post );
		}

		return array();
	}

	/**
	 * Log to File | Log into system php error log, usefull for Ajax and stuff that FirePHP doesn't catch
	 *
	 * @param $msg
	 * @param string $name
	 */
	function acLog( $msg, $name = '' ) {
		// Print the name of the calling function if $name is left empty
		$trace = debug_backtrace();
		$name  = ( '' == $name ) ? $trace[1]['function'] : $name;

		$error_dir = ACCP_CATALOG_PLUGIN_URL . 'ac.log';
		$msg       = print_r( $msg, true );
		$log       = $name . "  |  " . $msg . "\n";
		error_log( $log, 3, $error_dir );
	}