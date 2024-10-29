<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	/**
	 * Add term page
	 */
	function accp_add_new_meta_field() {
		// this will add the custom meta field to the add new term page
		?>
        <div class="form-field ac-form-field">
            <label for="ac-file-name-display"><?php _e( 'Attachment', 'ac' ); ?></label>
            <button class="tax-default-add-file button action">Upload</button>
            <input type="text" disabled id="ac-file-name-display" class="ac-tax-file-value">
            <input type="hidden" name="ac-file-name-display" class="ac-tax-file-value">
        </div>
		<?php
	}

	function accp_add_image_field() {
		// this will add the custom meta field to the add new term page
		?>
        <div class="form-field ac-form-field">
            <label for="ac-file-name-display"><?php _e( 'Image thumbnail', 'ac' ); ?></label>
            <button class="tax-default-add-file button action">Add Image</button>
            <input type="text" disabled id="ac-file-name-display" class="ac-tax-file-value">
            <input type="hidden" name="ac-file-name-display" class="ac-tax-file-value">
        </div>
		<?php
	}

	// add_action( 'accp_product_cat_add_form_fields', 'accp_add_image_field', 10, 2 );

	/**
	 * Edit term page
	 */
	function accp_edit_new_meta_field( $term ) {
		$term_id    = $term->term_id;
		$url        = get_term_meta( $term_id, 'ac_attachment_file', true );
		$tax_name   = sanitize_text_field($_GET['taxonomy']);
		$term_id    = sanitize_text_field($_GET['tag_ID']);
		$delete_url = wp_nonce_url( "edit-tags.php?action=delete&amp;taxonomy=$tax_name&amp;tag_ID=$term_id", 'delete-tag_' . $term_id );
		?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="ac-file-name-display"><?php _e( 'Attachment', 'ac' ); ?></label>
            </th>
            <td>
                <button class="tax-default-add-file button action">Upload</button>
                <input type="text" disabled id="ac-file-name-display" class="ac-tax-file-value"
                       value="<?php echo $url; ?>">
                <input type="hidden" name="ac-file-name-display" class="ac-tax-file-value" value="<?php echo $url; ?>">
                <p class="description"><?php _e( 'Attachment URL', 'ac' ); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row" valign="top">
                <a href="<?php echo $delete_url; ?>" class="delete-tag aria-button-if-js" aria-label="">Delete</a>
            </th>
        </tr>
		<?php
	}

	// Taxonomy Product categories
	function accp_edit_image_field( $term ) {
		$term_id = $term->term_id;
		$url     = get_term_meta( $term_id, 'ac_attachment_file', true );
		?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="ac_attachment_file"><?php _e( 'Attachment', 'ac' ); ?></label>
            </th>
            <td>
                <button class="tax-default-add-file button action">Select Image</button>
                <input type="text" disabled id="ac_attachment_file" class="ac-tax-file-value"
                       value="<?php echo $url; ?>">
                <input type="hidden" name="ac_attachment_file" class="ac-tax-file-value" value="<?php echo $url; ?>">
                <p class="description"><?php _e( 'Image URL', 'ac' ); ?></p>
                <img class="ac-edit-category-image-thumb" src="<?php echo $url; ?>" alt="">
            </td>
        </tr>
		<?php
	}
// Product categories
// add_action( 'accp_product_cat_edit_form_fields', 'accp_edit_image_field', 10, 2 );