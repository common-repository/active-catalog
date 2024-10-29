<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	function ba_admin_posts_filter_restrict_manage_posts() {
		global $wpdb, $wp_query;

		if ( $wp_query->get( 'post_type' ) !== 'ac_product' ) {
			return false;
		}

		$sql       = 'SELECT DISTINCT meta_key FROM ' . $wpdb->postmeta . ' ORDER BY 1';
		$fields    = $wpdb->get_results( $sql, ARRAY_N );
		$get_param = isset( $_GET['ac_filter_by_meta'] ) ? sanitize_text_field( $_GET['ac_filter_by_meta'] ) : '';
		?>
        <select name="ac_filter_by_meta">
            <option><?php _e( 'Filter by', 'ac' ); ?></option>
            <option <?php echo $get_param === 'sku' ? 'selected="selected"' : ''; ?>
                    value="sku"><?php _e( 'SKU', 'ac' ); ?></option>
            <option <?php echo $get_param === 'price' ? 'selected="selected"' : ''; ?>
                    value="price"><?php _e( 'Price', 'ac' ); ?></option>
            <option <?php echo $get_param === 'manufacturer' ? 'selected="selected"' : ''; ?>
                    value="manufacturer"><?php _e( 'Manufacturer', 'ac' ); ?></option>
            <option <?php echo $get_param === 'tags' ? 'selected="selected"' : ''; ?>
                    value="tags"><?php _e( 'Tags', 'ac' ); ?></option>
            <option <?php echo $get_param === 'status_draft' ? 'selected="selected"' : ''; ?>
                    value="status_draft"><?php _e( 'Status: Draft', 'ac' ); ?></option>
            <option <?php echo $get_param === 'status_published' ? 'selected="selected"' : ''; ?>
                    value="status_published"><?php _e( 'Status:  Published', 'ac' ); ?></option>
        </select>
		<?php
	}

	add_action( 'restrict_manage_posts', 'ba_admin_posts_filter_restrict_manage_posts' );

	function accp_custom_catalog_filter( $query ) {
		global $pagenow;

		if ( is_admin() &&
		     $pagenow == 'edit.php' &&
		     isset( $_GET['ac_filter_by_meta'] ) &&
		     $_GET['ac_filter_by_meta'] != '' ) {
			$type = 'ac_product';

			// If Tags in filter
			if ( $_GET['ac_filter_by_meta'] === 'tags' ) {
				$tags = array();

				$tags_obj = get_terms( array(
					'taxonomy'   => 'ac_product_tag',
					'hide_empty' => false,
				) );

				if ( ! $tags_obj instanceof WP_Error ) {
					foreach ( $tags_obj as $value ) {
						$tags[] = $value->name;
					}
				}

				$tax_query = array(
					array(
						'taxonomy' => 'ac_product_tag',
						'field'    => 'slug',
						'terms'    => $tags,
						'operator' => 'IN',
					)
				);

				$query->set( 'tax_query', $tax_query );
			} elseif ( $_GET['ac_filter_by_meta'] === 'manufacturer' ) {
				$pattern    = '"manufacturer";s:[^0][\d]*';
				$mata_query = array(
					array(
						'key'     => 'ac-product-details',
						'value'   => $pattern,
						'compare' => 'REGEXP'
					)
				);

				$query->set( 'meta_query', $mata_query );
			} elseif ( $_GET['ac_filter_by_meta'] === 'sku' ) {
				$pattern    = '"sku";s:[^0][\d]*';
				$mata_query = array(
					array(
						'key'     => 'ac-product-details',
						'value'   => $pattern,
						'compare' => 'REGEXP'
					)
				);

				$query->set( 'meta_query', $mata_query );
			} elseif ( $_GET['ac_filter_by_meta'] === 'price' ) {
				$pattern    = '"price";s:[^0][\d]*';
				$mata_query = array(
					array(
						'key'     => 'ac-product-details',
						'value'   => $pattern,
						'compare' => 'REGEXP'
					)
				);

				$query->set( 'meta_query', $mata_query );
			} elseif ( $_GET['ac_filter_by_meta'] === 'status_published' ) {
				$query->set( 'post_status', 'publish' );
			} elseif ( $_GET['ac_filter_by_meta'] === 'status_draft' ) {
				$query->set( 'post_status', 'draft' );
			}
		}
	}

	add_filter( 'parse_query', 'accp_custom_catalog_filter' );