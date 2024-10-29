<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	/**
	 * Images metabox view
	 */
	function accp_product_images_meta_box() {
		global $post;
		$main_image               = get_post_meta( $post->ID, '_ac_main_product_image', true );
		$main_image_correct_quote = str_replace( '"', "'", $main_image );
		$main_image_obj           = json_decode( str_replace( "'", '"', $main_image ) );
		$secondary_images         = get_post_meta( $post->ID, '_ac_additional_product_images', true );

		$secondary_images_correct_quote = str_replace( '"', "'", $secondary_images );
		$secondary_images_obj           = json_decode( str_replace( "'", '"', $secondary_images ) );
		?>
        <div class="images-metabox-container">
            <ul class="ac-images-metabox-main-image connectedSortable ac-main-image-empty-border">
				<?php if ( ! empty( $main_image_obj ) ) : ?>
					<?php foreach ( $main_image_obj as $key => $value ) : ?>
						<?php if ( isset( $value->image_sizes->meduim ) ) {
							$url = $value->image_sizes->meduim->url;
						} else {
							$url = $value->image_sizes->full->url;
						} ?>
                        <li data-name="<?php echo $value->image_title; ?>"
                            data-json="<?php echo $main_image_correct_quote; ?>">
                            <div class="ac-image-metabox-header">
							<span class="ac-image-metabox-header-name">
								<?php echo $value->image_title; ?>
							</span>
                                <i class="dashicons-before dashicons-no-alt"></i>
                            </div>
                            <div class="ac-image-metabox-thumb-box">
                                <img src="<?php echo $url; ?>" alt="" class="ac-images-metabox-thumbnail">
                            </div>
                        </li>
					<?php endforeach; ?>
				<?php endif; ?>
            </ul>
            <ul class="ac-images-metabox-list connectedSortable">
				<?php if ( ! empty( $secondary_images_obj ) ) : ?>
					<?php
					$secondary_ordered_array = array();
					foreach ( $secondary_images_obj as $key => $value ) {
						if ( ! isset( $value->image_order ) ) {
							$value->image_order = 0;
						}
						$image_order                             = $value->image_order;
						$image_order                             = accp_recursive_cheking( $image_order, $secondary_ordered_array );
						$secondary_ordered_array[ $image_order ] = $value;
					}
					ksort( $secondary_ordered_array );
					?>
					<?php foreach ( $secondary_ordered_array as $value ) : ?>
						<?php
						$image_json = '{' . "'" . $value->image_title . "'" . ':' . str_replace( '"', "'", json_encode( $value ) ) . '}';
						?>
						<?php if ( isset( $value->image_sizes->thumbnail ) ) {
							$url = $value->image_sizes->thumbnail->url;
						} else {
							$url = $value->image_sizes->full->url;
						} ?>
                        <li data-name="<?php echo $value->image_title; ?>" data-json="<?php echo $image_json; ?>">
                            <div class="ac-image-metabox-header">
							<span class="ac-image-metabox-header-name">
								<?php echo $value->image_title; ?>
							</span>
                                <i class="dashicons-before dashicons-no-alt"></i>
                            </div>
                            <div class="ac-image-metabox-thumb-box">
                                <img src="<?php echo $url; ?>" alt="" class="ac-images-metabox-thumbnail">
                            </div>
                        </li>
					<?php endforeach; ?>
				<?php endif; ?>
            </ul>
            <div class="images-metabox-footer">
                <input type="hidden" name="ac-main-product-image" value="<?php echo $main_image_correct_quote; ?>">
                <input type="hidden" name="ac-additional-product-images"
                       value="<?php echo $secondary_images_correct_quote; ?>">
                <button class="ac-images-metabox-btn js-ac-images-metabox-btn button button-primary button-large">
                    <i class="dashicons-before dashicons-format-gallery"></i>
                    Add images
                </button>
            </div>
        </div>
		<?php
	}
