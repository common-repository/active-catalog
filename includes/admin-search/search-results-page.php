<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	// Add search results page
	function accp_add_admin_page_search_result() {
		add_submenu_page(
			'options.php',
			'Search results',
			'Search results',
			'manage_options',
			'ac-search',
			'accp_admin_page_search_result'
		);
	}

	add_action( 'admin_menu', 'accp_add_admin_page_search_result' );

	// Callback function for search results
	function accp_admin_page_search_result() {
		$search = isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : 'error';

		?>
        <a class="ac-back-link" href="<?php bloginfo( 'url' ); ?>/wp-admin/edit.php?post_type=ac_product">
            <i class="dashicons-before dashicons-arrow-left-alt"></i><?php _e( 'Back to catalog', 'ac' ); ?>
        </a>
		<?php if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) : ?>
            <h1>Search results for: "<?php echo esc_html($_GET['s']); ?>"</h1>
		<?php endif; ?>
		<?php

		// We search in meta_field
		$args     = array(
			'posts_per_page'     => - 1,
			'post_type'          => 'ac_product',
			'orderby'            => 'title',
			'order'              => 'ASC',
			'catalog_item_title' => $search,
			'meta_query'         => array(
				'relation' => 'OR',
				array(
					'key'     => 'ac-product-details',
					'value'   => $search,
					'compare' => 'REGEXP'
				)
			)
		);
		$wp_query = new WP_Query( $args );

		// If no posts we search in taxonomies
		if ( ! $wp_query->have_posts() ) {
			$args     = array(
				'posts_per_page' => - 1,
				'post_type'      => 'ac_product',
				'orderby'        => 'title',
				'order'          => 'ASC',
				'tax_query'      => array(
					'relation' => 'OR',
					array(
						'taxonomy' => 'ac_product_tag',
						'field'    => 'slug',
						'terms'    => array( $search )
					),
					array(
						'taxonomy' => 'ac_product_cat',
						'field'    => 'slug',
						'terms'    => array( $search )
					),

				)
			);
			$wp_query = new WP_Query( $args );
		}
		$count = count( $wp_query->posts );
		?>
        <div class="wrap ac-search-results-page-wrap">
            <h3>Found: <?php echo $count; ?></h3>
            <table class="wp-list-table widefat fixed striped posts">
				<?php if ( $wp_query->have_posts() ) : ?>
					<?php while( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
                        <tr>
                            <td width="5%" class="product_image column-product_image ac-td-catalog-product-name"
                                data-colname="Image">
								<?php
									$product_image = get_post_meta( get_the_ID(), '_ac_main_product_image', true );
									$product_image = json_decode( $product_image );

									if ( isset( $product_image ) && ! empty( $product_image ) ) {
										foreach ( $product_image as $value ) {
											$url = $value->image_sizes->thumbnail->url;
											echo '<a href="' . get_edit_post_link( get_the_ID() ) . '"><img width="43" height="43" src="' . $url . '" alt=""><a>';
										}
									} else {
										echo '<i class="thumb-if-not-image dashicons-before dashicons-format-image"></i>';
									}
								?>
                            </td>
                            <td width="95%" class="ac-td-catalog-product-name">
                                <strong>
                                    <a href="<?php echo get_edit_post_link( get_the_ID() ); ?>">
										<?php the_title(); ?>
                                        <a>
                                </strong>
                            </td>
                        </tr>
					<?php endwhile; ?>
				<?php else : ?>
                    <h2>No results...</h2>
				<?php endif; ?>
            </table>
        </div>
		<?php
	}

// Add to query the title search with "OR" relation
function ac_change_posts_where( $where, &$wp_query ) {

    global $wpdb;
    if ( $catalog_item_title = $wp_query->get( 'catalog_item_title' ) ) {
        $where .= ' OR ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql ( $wpdb->esc_like( $catalog_item_title ) )  . '%\'';
    }
    return $where;

}
add_filter( 'posts_where', 'ac_change_posts_where', 10, 2 );