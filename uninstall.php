<?php
/**
 * Uninstall Plugin for MMT Membership
 *
 * @package momo-membership
 * @author MoMo Themes
 */

if ( defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	$option = get_option( 'mmtms_plugin_options' );

	if ( 'on' === $option['mmtms_delete_uninstall'] ) {
		delete_option( 'mmtms_plugin_options' );
		delete_option( 'mmtms_redirection_options' );
		delete_option( 'mmtms_invoice_options' );
		delete_option( 'mmtms_level_options' );
		delete_option( 'mmtms_payment_options' );
	}
}
