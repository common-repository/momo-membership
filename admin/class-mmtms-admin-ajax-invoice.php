<?php
/**
 * Admin Ajax Class for Invoice
 *
 * @package momo-membership
 * @author MoMo Thems
 * @since v1.0.0
 */
class Mmtms_Admin_Ajax_Invoice {
	/**
	 * Constructor
	 */
	public function __construct() {
		$ajax_events = array(
			'mmtms_save_invoice_settings' => 'mmtms_save_invoice_settings',
		);
		foreach ( $ajax_events as $ajax_event => $class ) {
			add_action( 'wp_ajax_' . $ajax_event, array( $this, $class ) );
			add_action( 'wp_ajax_nopriv_' . $ajax_event, array( $this, $class ) );
		}
	}

	/**
	 * Save Invoice Settings
	 */
	public function mmtms_save_invoice_settings() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_save_invoice_settings' !== $_POST['action'] ) {
			return;
		}
		$settings                         = array();
		$settings['mmtms_enable_invoice'] = isset( $_POST['enable_invoice'] ) ? sanitize_text_field( wp_unslash( $_POST['enable_invoice'] ) ) : '';
		$settings['mmtms_inv_bname']      = isset( $_POST['business_name'] ) ? sanitize_text_field( wp_unslash( $_POST['business_name'] ) ) : '';
		$settings['mmtms_inv_email']      = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
		$settings['mmtms_inv_address']    = isset( $_POST['address'] ) ? sanitize_text_field( wp_unslash( $_POST['address'] ) ) : '';
		$settings['mmtms_inv_phone']      = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
		$settings['mmtms_business_logo']  = isset( $_POST['business_logo'] ) ? sanitize_text_field( wp_unslash( $_POST['business_logo'] ) ) : '';
		$settings['mmtms_inv_email_sub']  = isset( $_POST['mmtms_inv_email_sub'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_inv_email_sub'] ) ) : '';
		$mmtms_invoice_options            = get_option( 'mmtms_invoice_options' );
		$settings['invoice_no']           = isset( $mmtms_invoice_options['invoice_no'] ) ? $mmtms_invoice_options['invoice_no'] : '1000';
		update_option( 'mmtms_invoice_options', $settings );
		echo wp_json_encode(
			array(
				'status' => 'good',
				'msg'    => esc_html__( 'Settings successfully updated.', 'momo-membership' ),
			)
		);
		exit;
	}
}
new Mmtms_Admin_Ajax_Invoice();
