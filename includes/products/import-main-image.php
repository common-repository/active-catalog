<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	include_once( ABSPATH . 'wp-admin/includes/admin.php' );

	// Import images
	$thumb_url = trim( $main_image );
	if ( $thumb_url ) {
		$file_name = preg_replace( '/\.[^.]+$/', '', basename( $thumb_url ) );

		preg_match( '/(\w+)(\.\w+)+(?!.*(\w+)(\.\w+)+)/', $thumb_url, $matches );
		$file_name_with_ext = $matches[0];

		$uploaddir  = wp_upload_dir();
		$uploadfile = $uploaddir['path'] . '/' . $file_name_with_ext;

		$contents = wp_remote_retrieve_body( wp_remote_get( $thumb_url ) );

		if ( $contents ) {
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

			$imagenew     = get_post( $attach_id );
			$fullsizepath = get_attached_file( $imagenew->ID );
			$attach_data  = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
			wp_update_attachment_metadata( $attach_id, $attach_data );

			$image                    = wp_get_attachment_metadata( $attach_id );
			$image_attached           = wp_get_attachment_image_src( $attach_id, 'full' );
			$image_attached_large     = wp_get_attachment_image_src( $attach_id, 'large' );
			$image_attached_medium    = wp_get_attachment_image_src( $attach_id, 'medium' );
			$image_attached_thumbnail = wp_get_attachment_image_src( $attach_id, 'thumbnail' );
			$image_url                = $image_attached[0];

			$thumb_h   = $image_attached_thumbnail[2];
			$thumb_w   = $image_attached_thumbnail[1];
			$thumb_url = wp_get_attachment_image_src( $attach_id, 'thumbnail' );
			$thumb_url = $thumb_url ? $thumb_url[0] : '';

			$medium_h   = $image_attached_medium[2];
			$medium_w   = $image_attached_medium[1];
			$medium_url = wp_get_attachment_image_src( $attach_id, 'medium' );
			$medium_url = $medium_url ? $medium_url[0] : '';

			$large_h   = $image_attached_large[2];
			$large_w   = $image_attached_large[1];
			$large_url = wp_get_attachment_image_src( $attach_id, 'large' );
			$large_url = $large_url ? $large_url[0] : '';

			$full_h   = $image_attached[2];
			$full_w   = $image_attached[1];
			$full_url = wp_get_attachment_image_src( $attach_id, 'full' );
			$full_url = $full_url ? $full_url[0] : '';

			$mian_image_arr  = array(
				$file_name => array(
					'image_url'   => $image_url,
					'image_id'    => $attach_id,
					'image_title' => $file_name,
					'image_sizes' => array(
						'thumbnail' => array(
							'height' => $thumb_h,
							'width'  => $thumb_w,
							'url'    => $thumb_url,
						),
						'medium'    => array(
							'height' => $medium_h,
							'width'  => $medium_w,
							'url'    => $medium_url,
						),
						'large'     => array(
							'height' => $large_h,
							'width'  => $large_w,
							'url'    => $large_url,
						),
						'full'      => array(
							'height' => $full_h,
							'width'  => $full_w,
							'url'    => $full_url,
						),
					),
				)
			);
			$main_image_json = json_encode( $mian_image_arr );
			update_post_meta( $post, '_ac_main_product_image', $main_image_json );
		}
	}