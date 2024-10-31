<?php
/**
 * OVISTO WooCommerce Uninstall
 *
 * Uninstalling OVISTO WooCommerce deletes options.
 *
 * @author      OVISTO
 * @category    Core
 * @package     Ovisto/Uninstaller
 * @version     1.0.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$status_options = get_option( 'ovisto_status_options_uninstall' );

if ( ! empty( $status_options ) ) {

	// Options.
	wp_trash_post( get_option( 'ovisto_status_options_uninstall' ) );

	wp_trash_post( get_option( 'ovisto_active', 0 ) );
	wp_trash_post( get_option( 'ovisto_partner_id', '' ) );
	wp_trash_post( get_option( 'ovisto_target_div', '' ) );
	wp_trash_post( get_option( 'ovisto_banner_hide', 0 ) );
	wp_trash_post( get_option( 'ovisto_banner_heading', 'Ein Gutschein wartet auf Sie...' ) );
	wp_trash_post( get_option( 'ovisto_banner_heading_css', '' ) );
	wp_trash_post( get_option( 'ovisto_banner_css', 'text-align: center; border: 3px solid #ededed; margin-top: 20px;' ) );
	wp_trash_post( get_option( 'ovisto_country', 'de' ) );

}