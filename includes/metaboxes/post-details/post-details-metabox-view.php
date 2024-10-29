<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	// Post details metabox
	function accp_productdetails() {
		global $post;
		$ac_product_details = get_post_meta( $post->ID, 'ac-product-details', true );
		?>
        <div class="product-details-metabox-container">
            <table>
                <tr>
                    <th>
                        <label for="acatFieldSku">SKU</label>
                    </th>
                    <td>
                        <input id="acatFieldSku" type="text" name="ac-product-details[sku]"
                               value="<?php echo isset( $ac_product_details['sku'] ) ? $ac_product_details['sku'] : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="acatFieldManufacturer">Manufacturer</label>
                    </th>
                    <td>
                        <input id="acatFieldManufacturer" type="text" name="ac-product-details[manufacturer]"
                               value="<?php echo isset( $ac_product_details['manufacturer'] ) ? $ac_product_details['manufacturer'] : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="acatFieldPrice">Price</label>
                    </th>
                    <td>
                        <input id="acatFieldPrice" type="text" name="ac-product-details[price]"
                               value="<?php echo isset( $ac_product_details['price'] ) ? $ac_product_details['price'] : ''; ?>">
                    </td>
                </tr>
            </table>
        </div>
		<?php
	}
