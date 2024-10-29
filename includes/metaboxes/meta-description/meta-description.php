<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	function accp_set_metadescription() {
		global $post;
		$ac_product_details = get_post_meta( $post->ID, 'ac-product-details', true );
		?>
        <div class="product-details-metabox-container">
            <table>
                <tr>
                    <th>
                        <label for="acatFieldMetaDescription">Product Short Description</label>
                    </th>
                    <td>
                        <input id="acatFieldMetaDescription" type="text" name="ac-product-details[meta-description]"
                               value="<?php echo isset( $ac_product_details['meta-description'] ) ? $ac_product_details['meta-description'] : ''; ?>">
                    </td>
                </tr>
            </table>
        </div>
        <p>The Product Meta-Description is used in Search Engine Optimization and will be displayed in search results as
            a summary of your web page. <i>If no content is entered in this field a portion of the main product
                description may be used.</i></p>
		<?php
	}
