<?php

  if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
  }

  /**
   * Register a product post type.
   *
   * @link http://codex.wordpress.org/Function_Reference/register_post_type
   */
  $labels = array(
    'name'               => __('Products', 'ac'),
    'singular_name'      => __('Product', 'ac'),
    'menu_name'          => __('AC Catalog', 'ac'),
    'name_admin_bar'     => __('Product', 'ac'),
    'add_new'            => __('Add new', 'ac'),
    'add_new_item'       => __('Add new product', 'ac'),
    'new_item'           => __('New product', 'ac'),
    'edit_item'          => __('Edit product', 'ac'),
    'view_item'          => __('View product', 'ac'),
    'view_items'         => __('View products', 'ac'),
    'all_items'          => __('Products', 'ac'),
    'search_items'       => __('Search products', 'ac'),
    'parent_item_colon'  => __('Parent products:', 'ac'),
    'not_found'          => __('No products found.', 'ac'),
    'not_found_in_trash' => __('No products found in the trash.', 'ac')
  );

  $args = array(
    'labels'             => $labels,
    'description'        => __('Products', 'ac'),
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'show_in_rest'       => true,
    'show_in_nav_menus'  => true,
    'query_var'          => 'products',
    'capability_type'    => 'post',
    'has_archive'        => 'products',
    'hierarchical'       => true,
    'menu_position'      => null,
    'menu_icon'          => plugins_url('assets/images/favicon.png', dirname(__FILE__)),
    'supports'           => array(
      'title',
      'custom-fields',
      'editor',
      'author',
    )
  );

  register_post_type('ac_product', $args);