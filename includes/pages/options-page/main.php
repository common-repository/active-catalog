<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	$options = get_option( 'ac_catalog_options', '' );
	foreach (
		array(
			'is_activated_key',
			'ac_exists_ac_code_installed',
			'ac_install_new_ac_code',
			'ac_plugin_key',
			'ac_pid',
		)
		as $name
	) {
		$exists_option = ( get_option( $name, null ) != null );
		if ( $exists_option && ! array_key_exists( $name, $options ) ) {
			$options[ $name ] = get_option( $name );
			//delete_option($name);
		}
	}

?>
<form method="POST" action="">
    <table class="form-table" width="80%">
        <tbody>
            <tr valign="top">
                <th style="height: 37.217px;" scope="row"><label for="num_elements">Product currency symbol:</label>
                </th>
                <td><input name="ac_catalog_options[currency_symbol]"
                           value="<?php echo isset( $options['currency_symbol'] ) ? $options['currency_symbol'] : ''; ?>"
                           type="text"></td>
            </tr>
            <tr>
                <th valign="top"><input name="saved" value="ok" type="hidden">
                    <button class="button button-primary button-large"><?php _e( 'Save', 'ac' ); ?></button>
                </th>
                <td><br>
                </td>
            </tr>
        </tbody>
    </table>
</form>
