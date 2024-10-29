<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	function accp_product_shortcodes( $atts, $content = null ) {
		global $post, $wpdb;
		$global_post       = $post;
		$global_post_id    = $post->ID;
		$atts              = shortcode_atts( array(
			'type'     => '',
			'position' => '',
			'id'       => $post->ID,
		), $atts, 'accp_item' );
		$options           = get_option( 'ac_catalog_options', '', true );
		$results           = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value LIKE '%{$atts['id']}%'", ARRAY_A );
		$shortcode_post_id = ( ! empty( $results[0]['post_id'] ) ) ? $results[0]['post_id'] : '';
		if ( isset( $options['currency_symbol'] ) ) {
			$currency_symbol = $options['currency_symbol'];
		}

		$shortcode_output = '';
		$style            = '';
		$post_type        = get_post_type( $post );

		if ( $global_post_id != $atts['id'] ) {
			if ( $atts['id'] == '{Please' ) {
				return __( 'Please enter a SKU for this shortcode', 'ac' ) . '</br>';
			} elseif ( empty( $results ) ) {
				return __( "This product SKU doesn't exist", 'ac' ) . "</br>";
			}
		}

		// Is it POST & PAGE or Options page
		if ( $global_post_id != $atts['id']
		     && $atts['type'] != 'currency'
		     && ( ! empty( $shortcode_post_id ) || $post->post_type !== 'ac_product' ) ) {
			$post             = get_post( $shortcode_post_id );
			$post_meta        = get_post_meta( $shortcode_post_id, 'ac-product-details', true );
			$main_image       = get_post_meta( $shortcode_post_id, '_ac_main_product_image', true );
			$secondary_images = get_post_meta( $shortcode_post_id, '_ac_additional_product_images', true );
			$title            = get_the_title( $shortcode_post_id );
			$out_id           = $shortcode_post_id;
		} else {
			$post_meta        = get_post_meta( $post->ID, 'ac-product-details', true );
			$main_image       = get_post_meta( $post->ID, '_ac_main_product_image', true );
			$secondary_images = get_post_meta( $post->ID, '_ac_additional_product_images', true );
			$title            = get_the_title( $post->ID );
			$out_id           = $post->ID;
		}

		// SKU
		if ( $atts['type'] === 'sku' ) {
			if ( isset( $post_meta['sku'] ) && strlen( $post_meta['sku'] ) > 0 ) {
				$class            = preg_replace( "![^#a-z0-9]+!i", "-", $post_meta['sku'] );
				$shortcode_output .= '<p class="sku">SKU: <span class="ac_pc_item_sku_' . $class . '">' . $post_meta['sku'] . '</span></p>';
			}
		}

		// ID
		if ( $atts['type'] === 'id' ) {
			if ( isset( $out_id ) ) {
				$shortcode_output .= $out_id;
			}
		}

		// Name
		if ( $atts['type'] === 'name' ) {
			$class            = preg_replace( "![^#a-z0-9]+!i", "-", $title );
			$shortcode_output .= '<h2 class="ac_pc_item_name_' . $class . '" id="post-' . $out_id . '">' . $title . '</h2>';
		}

		// Manufacturer
		if ( $atts['type'] === 'mfg' ) {
			if ( isset( $post_meta['manufacturer'] ) && strlen( $post_meta['manufacturer'] ) > 0 ) {
				$class            = preg_replace( "![^#a-z0-9]+!i", "-", $post_meta['manufacturer'] );
				$shortcode_output .= '<p>Manufacturer: <span class="ac_pc_item_mfg_' . $class . '">' . $post_meta['manufacturer'] . '</span></p>';
			}
		}

		// Main Image
		if ( $atts['type'] === 'main_pic' ) {
			$image_data = json_decode( $main_image );
			if ( ! is_object( $image_data ) ) {
				return '<img class="ac-product-image-main ac-image-medium" src="' . plugins_url('../assets/images/placeholder-image.png', dirname(__FILE__)) . '" alt="" />';
			};
			foreach ( $image_data as $key => $image_attr ) {
				$shortcode_output .= '<a href="' . $image_attr->image_url . '" rel="ac-product-gallery"><img class="ac-product-image-main ac-image-medium" src="' . $image_attr->image_sizes->full->url . '" alt="' . $title . '" /></a>';
			}
		}
		// Secondary Images
		if ( $atts['type'] === 'sec_pics' ) {
			$image_data = json_decode( $secondary_images );
			if ( ! is_object( $image_data ) ) {
				return '<!-- No Images Available -->';
			}
			foreach ( $image_data as $key => $image_attr ) {
				$shortcode_output .= '
                <a href="' . $image_attr->image_url . '" rel="ac-product-gallery"><img class="ac-product-image" src="' . $image_attr->image_sizes->thumbnail->url . '" alt="' . $title . '" /></a>
            ';
			}
		}

		// Image - origin size
		if ( $atts['type'] === 'pic_size_origin' ) {
			$image_data = json_decode( $main_image );
			if ( ! is_object( $image_data ) ) {
				return '<!-- No Images Available -->';
			}
			foreach ( $image_data as $key => $image_attr ) {
				$shortcode_output .= '<img class="ac-product-image-main ac-image-full" src="' . $image_attr->image_url . '" alt="' . $title . '" />';
			}
		}

		// Image - thumbnail size
		if ( $atts['type'] === 'pic_size_thumbnail' ) {
			$image_data = json_decode( $main_image );
			if ( ! is_object( $image_data ) ) {
				return '<!-- No Images Available -->';
			}
			foreach ( $image_data as $key => $image_attr ) {
				$shortcode_output .= '<img class="ac-product-image-main ac-image-thumb" src="' . $image_attr->image_sizes->thumbnail->url . '" alt="' . $title . '" />';
			}
		}

		// Image - medium size
		if ( $atts['type'] === 'pic_size_medium' ) {
			$image_data = json_decode( $main_image );
			if ( ! is_object( $image_data ) ) {
				return '<!-- No Images Available -->';
			}
			foreach ( $image_data as $key => $image_attr ) {
				if ( isset( $image_attr->image_sizes->medium ) ) {
					$image_size_url = $image_attr->image_sizes->medium->url;
				} else {
					$image_size_url = $image_attr->image_sizes->full->url;
				}
				$shortcode_output .= '<img class="ac-product-image-main ac-image-medium" src="' . $image_size_url . '" alt="' . $title . '" />';
			}
		}
		// Image - medium (background)
		if ( $atts['type'] === 'pic_size_medium_url' ) {
			$image_data = json_decode( $main_image );
			if ( ! is_object( $image_data ) ) {
				return plugins_url('assets/images/placeholder-image.png', dirname(__FILE__));
			}
			foreach ( $image_data as $key => $image_attr ) {
				if ( isset( $image_attr->image_sizes->medium ) ) {
					$image_size_url = $image_attr->image_sizes->medium->url;
				} else {
					$image_size_url = $image_attr->image_sizes->full->url;
				}
				$shortcode_output .= $image_size_url;
			}
		}

		// Image - large size
		if ( $atts['type'] === 'pic_size_large' ) {
			$image_data = json_decode( $main_image );

			if ( ! is_object( $image_data ) ) {
				return '<!-- No Images Available -->';
			}
			foreach ( $image_data as $key => $image_attr ) {
				if ( isset( $image_attr->image_sizes->large ) ) {
					$image_size_url = $image_attr->image_sizes->large->url;
				} else {
					$image_size_url = $image_attr->image_sizes->full->url;
				}
				$shortcode_output .= '<img class="ac-product-image-main ac-image-large" src="' . $image_size_url . '" alt="' . $title . '" />';
			}
		}

		// Price
		if ( $atts['type'] === 'price' ) {
			if ( isset( $post_meta['price'] ) && strlen( $post_meta['price'] ) > 0 ) {
				$currency_symbol  = isset( $options['currency_symbol'] ) ? '<span class="ac_pc_item_currency">' . $options['currency_symbol'] . '</span>' : "";
				$shortcode_output .= '<h4 class="price">Price: ' . $currency_symbol . $post_meta['price'] . '</span></h4>';
			}
		}

		// Currency
		if ( $atts['type'] === 'currency' ) {
			if ( isset( $options['currency_symbol'] ) ) {
				$shortcode_output .= '<span class="ac_pc_item_currency">' . $options['currency_symbol'] . '</span>';
			}
		}

		// Size
		if ( $atts['type'] === 'size' ) {
			if ( isset( $post_meta['size'] ) ) {
				$shortcode_output .= '<span class="ac_pc_item_size">' . $post_meta['size'] . '</span>';
			}
		}

		// Categories
		if ( $atts['type'] === 'category' ) {
			$taxonomy   = 'ac_product_cat';
			$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
			$separator  = ', ';
			if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
				$term_ids         = implode( ',', $post_terms );
				$terms            = wp_list_categories( array(
					'title_li' => '',
					'style'    => 'none',
					'echo'     => false,
					'taxonomy' => $taxonomy,
					'include'  => $term_ids
				) );
				$terms            = rtrim( trim( str_replace( '<br />', $separator, $terms ) ), $separator );
				$shortcode_output .= $terms;
			}
		}

		// Brochures
		if ( $atts['type'] === 'brochures' ) {
			$brochures = accp_get_field( 'ac_brochures', $post->ID );
			if ( ! empty( $brochures ) ) {
				$shortcode_output .= '
                <ul class="ac_pc_item_brocdoc">
            ';
				foreach ( $brochures as $brochure ) {
					$brochure_url   = $brochure['file'];
					$brochure_title = $brochure['title'];
					if ( ! empty( $brochure_url ) ) {
						$shortcode_output .= '
                            <li class="ac_pc_item_brocdoc">
                                <a class="ac_pc_item_brocdoc_link" href="' . $brochure_url . '" target="_blank" download>
                        ';
						if ( $brochure_title ) {
							$shortcode_output .= $brochure_title;
						} else {
							$shortcode_output .= ' Brochure ';
						}
						$shortcode_output .= '
                                </a>
                            </li>
                        ';
					}
				}
				$shortcode_output .= '
                </ul>
            ';
			}
		}

		// Specsheets
		if ( $atts['type'] === 'specsheets' ) {
			$specs = accp_get_field( 'ac_spec_sheets', $post->ID );
			if ( ! empty( $specs ) ) {
				$shortcode_output .= '
                <ul class="ac_pc_item_specdoc">
            ';
				foreach ( $specs as $spec ) {
					$spec_url   = $spec['file'];
					$spec_title = $spec['title'];
					if ( ! empty( $spec_url ) ) {
						$shortcode_output .= '
                            <li class="ac_pc_item_brocdoc">
                                <a class="ac_pc_item_brocdoc_link" href="' . $spec_url . '" target="_blank" download>
                        ';
						if ( $spec_title ) {
							$shortcode_output .= $spec_title;
						} else {
							$shortcode_output .= ' Spec Sheet ';
						}
						$shortcode_output .= '
                                </a>
                            </li>
                        ';
					}
				}
				$shortcode_output .= '
                </ul>
            ';
			}
		}

		return $shortcode_output;
	}

	add_shortcode( 'accp_item', 'accp_product_shortcodes' );
