<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	// Add detele-transfer page
	function accp_add_delete_transfer_admin_page() {
		add_submenu_page(
			null,
			'Delete & transfer',
			'Delete & transfer',
			'manage_options',
			'delete_transfer',
			'accp_add_delete_transfer_page_callback'
		);
	}

	add_action( 'admin_menu', 'accp_add_delete_transfer_admin_page' );

	function accp_add_delete_transfer_page_callback() {
		$deleting_terms = isset( $_GET['term_id'] ) && ! empty( $_GET['term_id'] ) ? sanitize_text_field($_GET['term_id']) : '';
		$args           = array(
			'post_type' => 'ac_product',
			'tax_query' => array(
				array(
					'taxonomy' => 'ac_product_cat',
					'field'    => 'id',
					'terms'    => array( $deleting_terms )
				)
			)
		);
		$wp_query       = new WP_Query( $args );
		?>
        <form action="" method="post" class="ac-transfer-form">
            <h1 class="ac-admin-main-title"><span>Delete & transfer</span>
				<?php if ( $wp_query->have_posts() ) : ?>
                    <button class="button button-primary js-transfer">Transfer</button>
				<?php endif; ?>
            </h1>
            <a class="ac-admin-back-link"
               href="<?php echo get_admin_url() . 'edit-tags.php?taxonomy=ac_product_cat&post_type=ac_product' ?>"><i
                        class="dashicons-before dashicons-arrow-left-alt"></i>Back to categories</a>
            <div class="wrap">
				<?php if ( $wp_query->have_posts() ) : ?>
                    <div class="ac-transfer-container">
                        <div class="ac-postbox">
                            <h2 class="ac-post-box-header">Select products from deleting category</h2>
                            <ul class="ac-products-list">
                                <li class="ac-select-all-products-li">
                                    <label for="ac-select-all-products">
                                        <input id="ac-select-all-products" type="checkbox" value="select-all-products">
                                        Select All
                                    </label>
                                </li>
								<?php while( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
                                    <li>
                                        <label for="<?php echo get_the_title() . '-' . get_the_ID(); ?>">
                                            <input id="<?php echo get_the_title() . '-' . get_the_ID(); ?>"
                                                   type="checkbox" value="<?php the_ID(); ?>"
                                                   name="products_for_transfer">
											<?php the_title(); ?>
                                        </label>
                                    </li>
								<?php endwhile; ?>
                            </ul>
                        </div>
                        <div class="ac-postbox">
                            <h2 class="ac-post-box-header">Associate chosen products with these categories</h2>
							<?php
								$ac_categories = get_terms(
									array(
										'taxonomy'   => 'ac_product_cat',
										'hide_empty' => false,
									)
								);
							?>

							<?php if ( ! $ac_categories instanceof WP_Error ) : ?>
                                <ul class="ac-cats-list">
                                    <li class="ac-select-all-products-li">
                                        <label for="ac-select-all-cats">
                                            <input id="ac-select-all-cats" type="checkbox" value="select-all-cats">
                                            Select All
                                        </label>
                                    </li>
									<?php foreach ( $ac_categories as $term_obj ) : ?>
										<?php
										if ( isset( $_GET['term_id'] ) && ! empty( $_GET['term_id'] ) && $term_obj->term_id == $_GET['term_id'] ) {
											continue;
										}
										?>
                                        <li>
                                            <label for="<?php echo $term_obj->name . '-' . $term_obj->term_id; ?>">
                                                <input id="<?php echo $term_obj->name . '-' . $term_obj->term_id; ?>"
                                                       type="checkbox" value="<?php echo $term_obj->term_id; ?>"
                                                       name="categories_for_transfer">
												<?php echo $term_obj->name; ?>
                                            </label>
                                        </li>
									<?php endforeach; ?>
                                </ul>
							<?php endif; ?>
                        </div>
                    </div>
				<?php else : ?>
                    <h3>This category is empty...</h3>
				<?php endif; ?>
            </div>
        </form>
		<?php
	}