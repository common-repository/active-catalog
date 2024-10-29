<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	include_once( ABSPATH . 'wp-admin/includes/admin.php' );

	if ( ! function_exists( 'accp_product_files_import' ) ) {
		function accp_product_files_import( $post, $files, $field_keys, $field_slug ) {
			$attachments = array();
			$i           = 0;

			foreach ( $files as $key => $value ) {
				$value = esc_url($value);
				$file_name = preg_replace( '/\.[^.]+$/', '', basename( $value ) );

				$file_name_with_ext = wp_basename( $value );

				if ( strlen( $file_name_with_ext ) >= 1 ) {
					$i ++;
					$attach_id = get_page_by_title( $file_name_with_ext, OBJECT, 'attachment' );

					if ( $attach_id == null ) {
						$uploaddir  = wp_upload_dir();
						$uploadfile = $uploaddir['path'] . '/' . $file_name_with_ext;

						$contents = wp_remote_retrieve_body( wp_remote_get( $value ) );
						$savefile = fopen( $uploadfile, 'w' );
						fwrite( $savefile, $contents );
						fclose( $savefile );
						$wp_filetype = wp_check_filetype( $file_name_with_ext, null );

						$attachment = array(
							'guid'           => $uploaddir['url'] . '/' . $file_name_with_ext,
							'post_mime_type' => $wp_filetype['type'],
							'post_title'     => $file_name_with_ext,
							'post_content'   => '',
							'post_status'    => 'inherit'
						);

						$attach_id = wp_insert_attachment( $attachment, $uploadfile );
					} else {
						$attach_id = $attach_id->ID;
					}

					$file_url = wp_get_attachment_url( $attach_id );

					$j = $i - 1;

					update_post_meta( $post, $field_slug . '_' . $j . '_file', $attach_id );
					update_post_meta( $post, '_' . $field_slug . '_' . $j . '_file', $field_keys['file'] );

					update_post_meta( $post, $field_slug . '_' . $j . '_title', $key );
					update_post_meta( $post, '_' . $field_slug . '_' . $j . '_title', $field_keys['title'] );
				}
			}

			update_post_meta( $post, $field_slug, $i );
			update_post_meta( $post, '_' . $field_slug, $field_keys['parent'] );
		}
	}

	accp_product_files_import(
		$post,
		$brochures, array(
		'file'   => 'field_5b237797f913a',
		'title'  => 'field_5b24015c5eb3e',
		'parent' => 'field_5b237776f9139'
	),
		'ac_brochures'
	);

	accp_product_files_import(
		$post,
		$spec_sheets,
		array(
			'file'   => 'field_5b2377bef913c',
			'title'  => 'field_5b24016b5eb3f',
			'parent' => 'field_5b2377b3f913b',
		),
		'ac_spec_sheets'
	);
