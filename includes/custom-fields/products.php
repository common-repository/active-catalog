<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	function accp_register_products_custom_fields() {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			acf_add_local_field_group( array(
				'key'                   => 'group_5b237769975b9',
				'title'                 => 'Product Downloads',
				'fields'                => array(
					array(
						'key'               => 'field_5b237776f9139',
						'label'             => 'Brochures',
						'name'              => 'ac_brochures',
						'type'              => 'repeater',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'collapsed'         => '',
						'min'               => 0,
						'max'               => 0,
						'layout'            => 'table',
						'button_label'      => 'Add Brochure',
						'sub_fields'        => array(
							array(
								'key'               => 'field_5b237797f913a',
								'label'             => 'File',
								'name'              => 'file',
								'type'              => 'file',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'return_format'     => 'url',
								'library'           => 'all',
								'min_size'          => '',
								'max_size'          => '',
								'mime_types'        => '',
							),
							array(
								'key'               => 'field_5b24015c5eb3e',
								'label'             => 'Title',
								'name'              => 'title',
								'type'              => 'text',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
								'placeholder'       => '',
								'prepend'           => '',
								'append'            => '',
								'maxlength'         => '',
							),
						),
					),
					array(
						'key'               => 'field_5b2377b3f913b',
						'label'             => 'Spec Sheets',
						'name'              => 'ac_spec_sheets',
						'type'              => 'repeater',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'collapsed'         => '',
						'min'               => 0,
						'max'               => 0,
						'layout'            => 'table',
						'button_label'      => 'Add Spec Sheet',
						'sub_fields'        => array(
							array(
								'key'               => 'field_5b2377bef913c',
								'label'             => 'File',
								'name'              => 'file',
								'type'              => 'file',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'return_format'     => 'url',
								'library'           => 'all',
								'min_size'          => '',
								'max_size'          => '',
								'mime_types'        => '',
							),
							array(
								'key'               => 'field_5b24016b5eb3f',
								'label'             => 'Title',
								'name'              => 'title',
								'type'              => 'text',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
								'placeholder'       => '',
								'prepend'           => '',
								'append'            => '',
								'maxlength'         => '',
							),
						),
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'ac_product',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => 1,
				'description'           => '',
			) );
		}
	}

	add_action( 'init', 'accp_register_products_custom_fields' );