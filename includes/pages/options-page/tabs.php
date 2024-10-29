<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	if ( isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ) {
		$active_tab = sanitize_text_field($_GET['tab']);
	} else {
		$active_tab = 'main';
	}
?>
<h2 class="nav-tab-wrapper" data-tab="<?php echo esc_html($tab); ?>">
    <a href="?post_type=ac_product&page=catalog-settings&tab=main"
       class="nav-tab <?php echo ( $active_tab == 'main' ) ? 'nav-tab-active' : ''; ?>">Main Settings</a>
</h2>