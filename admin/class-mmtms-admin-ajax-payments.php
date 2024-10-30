<?php
/**
 * Admin Ajax Class for Payments
 *
 * @package momo-membership
 * @author MoMo Thems
 * @since v1.0.0
 */
class Mmtms_Admin_Ajax_Payments {
	/**
	 * Constructor
	 */
	public function __construct() {
		$ajax_events = array(
			'mmtms_save_paypal_settings' => 'mmtms_save_paypal_settings',

		);
		foreach ( $ajax_events as $ajax_event => $class ) {
			add_action( 'wp_ajax_' . $ajax_event, array( $this, $class ) );
			add_action( 'wp_ajax_nopriv_' . $ajax_event, array( $this, $class ) );
		}
	}

	/**
	 * Save Paypal Settings
	 */
	public function mmtms_save_paypal_settings() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_save_paypal_settings' !== $_POST['action'] ) {
			return;
		}
		$settings                      = array();
		$settings['enable_pp']         = isset( $_POST['enable_pp'] ) ? sanitize_text_field( wp_unslash( $_POST['enable_pp'] ) ) : '';
		$settings['pp_title']          = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
		$settings['pp_desc']           = isset( $_POST['desc'] ) ? sanitize_text_field( wp_unslash( $_POST['desc'] ) ) : '';
		$settings['pp_email']          = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
		$settings['pp_enable_sandbox'] = isset( $_POST['enable_sandbox'] ) ? sanitize_text_field( wp_unslash( $_POST['enable_sandbox'] ) ) : '';
		$settings['pp_live_id']        = isset( $_POST['live_id'] ) ? sanitize_text_field( wp_unslash( $_POST['live_id'] ) ) : '';
		$settings['pp_live_secret']    = isset( $_POST['live_secret'] ) ? sanitize_text_field( wp_unslash( $_POST['live_secret'] ) ) : '';
		$settings['pp_sandbox_id']     = isset( $_POST['sandbox_id'] ) ? sanitize_text_field( wp_unslash( $_POST['sandbox_id'] ) ) : '';
		$settings['pp_sandbox_secret'] = isset( $_POST['sandbox_secret'] ) ? sanitize_text_field( wp_unslash( $_POST['sandbox_secret'] ) ) : '';
		update_option( 'mmtms_payment_options', $settings );
		echo wp_json_encode(
			array(
				'status' => 'good',
				'msg'    => esc_html__( 'Settings successfully updated.', 'momo-membership' ),
			)
		);
		exit;
	}
}
new Mmtms_Admin_Ajax_Payments();
