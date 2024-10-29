<?php

  if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
  }

  /**
   * Add new taxonomy, make it hierarchical (like categories)
   *
   * @link http://codex.wordpress.org/Function_Reference/register_post_type
   */
  $labels = array(
    'name'              => _x('Product Categories', 'taxonomy general name'),
    'singular_name'     => _x('Product Category', 'taxonomy singular name'),
    'search_items'      => __('Search categories'),
    'all_items'         => __('All categories'),
    'parent_item'       => __('Parent category'),
    'parent_item_colon' => __('Parent category:'),
    'edit_item'         => __('Edit category'),
    'update_item'       => __('Update category'),
    'add_new_item'      => __('Add new category'),
    'new_item_name'     => __('New category name'),
    'menu_name'         => __('Categories'),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'show_in_rest'      => true,
    'show_in_nav_menus' => true,
    'query_var'         => true,
    'rewrite'           => array('slug' => 'products/categories', 'hierarchical' => false),
  );

  register_taxonomy('ac_product_cat', array('ac_product'), $args);