<?php

  if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
  }

  /**
   * Add new taxonomy, NOT hierarchical
   *
   * @link http://codex.wordpress.org/Function_Reference/register_post_type
   */
  $labels = array(
    'name'                       => _x('Product Tags', 'taxonomy general name'),
    'singular_name'              => _x('Product Tag', 'taxonomy singular name'),
    'search_items'               => __('Search tags'),
    'popular_items'              => __('Popular tags'),
    'all_items'                  => __('All tags'),
    'parent_item'                => null,
    'parent_item_colon'          => null,
    'edit_item'                  => __('Edit tag'),
    'update_item'                => __('Update tag'),
    'add_new_item'               => __('Add new tag'),
    'new_item_name'              => __('New tag name'),
    'separate_items_with_commas' => __('Separate tags with commas'),
    'add_or_remove_items'        => __('Add or remove tags'),
    'choose_from_most_used'      => __('Choose from the most used tags'),
    'not_found'                  => __('No Product tags found.'),
    'menu_name'                  => __('Tags'),
  );

  $args = array(
    'hierarchical'          => false,
    'labels'                => $labels,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'show_in_rest'          => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var'             => true,
    'rewrite'               => array('slug' => 'products/tags'),
  );

  register_taxonomy('ac_product_tag', 'ac_product', $args);