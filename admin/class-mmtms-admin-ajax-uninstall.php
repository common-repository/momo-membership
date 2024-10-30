<?php
/**
 * Admin Ajax Class for Uninstall
 *
 * @package momo-membership
 * @author MoMo Thems
 */
class Mmtms_Admin_Ajax_Uninstall {
	/**
	 * Constructor
	 */
	public function __construct() {
		$ajax_events = array(
			'mmtms_save_plugin_settings' => 'mmtms_save_plugin_settings',
		);
		foreach ( $ajax_events as $ajax_event => $class ) {
			add_action( 'wp_ajax_' . $ajax_event, array( $this, $class ) );
			add_action( 'wp_ajax_nopriv_' . $ajax_event, array( $this, $class ) );
		}
	}

	/**
	 * Save Plugin Settings
	 */
	public function mmtms_save_plugin_settings() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_save_plugin_settings' !== $_POST['action'] ) {
			return;
		}
		$settings                           = array();
		$settings['mmtms_delete_uninstall'] = isset( $_POST['uninstall'] ) ? sanitize_text_field( wp_unslash( $_POST['uninstall'] ) ) : '';
		update_option( 'mmtms_plugin_options', $settings );
		echo wp_json_encode(
			array(
				'status' => 'good',
				'msg'    => esc_html__( 'Settings successfully updated.', 'momo-membership' ),
			)
		);
		exit;
	}
}
new Mmtms_Admin_Ajax_Uninstall();
