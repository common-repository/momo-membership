<?php
/**
 * Admin Ajax Class for Redirection
 *
 * @package momo-membership
 * @author MoMo Thems
 * @since v1.0.0
 */
class Mmtms_Admin_Ajax_Redirection {
	/**
	 * Constructor
	 */
	public function __construct() {
		$ajax_events = array(
			'mmtms_save_redirection_settings' => 'mmtms_save_redirection_settings',
		);
		foreach ( $ajax_events as $ajax_event => $class ) {
			add_action( 'wp_ajax_' . $ajax_event, array( $this, $class ) );
			add_action( 'wp_ajax_nopriv_' . $ajax_event, array( $this, $class ) );
		}
	}

	/**
	 * Save Invoice Settings
	 */
	public function mmtms_save_redirection_settings() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_save_redirection_settings' !== $_POST['action'] ) {
			return;
		}
		$settings                          = array();
		$settings['mmtms_redirect_login']  = isset( $_POST['login_redirect'] ) ? sanitize_text_field( wp_unslash( $_POST['login_redirect'] ) ) : '';
		$settings['mmtms_redirect_logout'] = isset( $_POST['logout_redirect'] ) ? sanitize_text_field( wp_unslash( $_POST['logout_redirect'] ) ) : '';
		update_option( 'mmtms_redirection_options', $settings );
		echo wp_json_encode(
			array(
				'status' => 'good',
				'msg'    => esc_html__( 'Settings successfully updated.', 'momo-membership' ),
			)
		);
		exit;
	}
}
new Mmtms_Admin_Ajax_Redirection();
