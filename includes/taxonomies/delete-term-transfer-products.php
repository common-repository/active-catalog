<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	// Add new delete type for categories
	function accp_add_new_type_of_deleting( $actions, $tag ) {
		$actions['delete_transfer'] = '<a href="' . get_admin_url() . 'edit.php?post_type=ac_product&page=delete_transfer&term_id=' . $tag->term_id . '" class="" data-id="' . $tag->term_id . '">Delete & Transfer</a>';

		return $actions;
	}

	add_filter( 'ac_product_cat_row_actions', 'accp_add_new_type_of_deleting', 10, 2 );