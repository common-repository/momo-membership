<?php
/**
 * Admin Ajax Class for Level
 *
 * @package momo-membership
 * @author MoMo Thems
 * @since v1.0.0
 */
class Mmtms_Admin_Ajax_Level {
	/**
	 * Constructor
	 */
	public function __construct() {
		$ajax_events = array(
			'mmtms_ajax_add_new_level'         => 'mmtms_ajax_add_new_level',
			'mmtms_ajax_edit_level'            => 'mmtms_ajax_edit_level',
			'mmtms_get_levels_by_slug'         => 'mmtms_get_levels_by_slug',
			'mmtms_delete_level_by_slug'       => 'mmtms_delete_level_by_slug',
			'mmtms_generate_admin_level_table' => 'mmtms_generate_admin_level_table',

		);
		foreach ( $ajax_events as $ajax_event => $class ) {
			add_action( 'wp_ajax_' . $ajax_event, array( $this, $class ) );
			add_action( 'wp_ajax_nopriv_' . $ajax_event, array( $this, $class ) );
		}
	}

	/**
	 * Delete Level by Slug
	 */
	public function mmtms_delete_level_by_slug() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_delete_level_by_slug' !== $_POST['action'] ) {
			return;
		}
		$mmtms_level_options = get_option( 'mmtms_level_options' );
		if ( ! isset( $_POST['slug'] ) && ! empty( $_POST['slug'] ) ) {
			return;
		}
		$slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
		foreach ( $mmtms_level_options as $i => $mmtms_level_option ) {
			if ( $mmtms_level_option['level_slug'] === $slug ) {
				unset( $mmtms_level_options[ $i ] );
				update_option( 'mmtms_level_options', $mmtms_level_options );
				echo wp_json_encode(
					array(
						'status' => 'good',
						'msg'    => esc_html__( 'Level deleted successfully.', 'momo-membership' ),
					)
				);
				exit;
			}
		}
	}
	/**
	 * Edit Level by Slug
	 */
	public function mmtms_ajax_edit_level() {
		global $mmtms;
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_ajax_edit_level' !== $_POST['action'] ) {
			return;
		}
		$mmtms_level_options  = get_option( 'mmtms_level_options' );
		$mmtms_new_level_name = isset( $_POST['mmtms_new_level_name'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_level_name'] ) ) : '';
		$mmtms_billing_type   = isset( $_POST['mmtms_billing_type'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_billing_type'] ) ) : '';
		$mmtms_wp_level_role  = isset( $_POST['mmtms_wp_level_role'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_wp_level_role'] ) ) : '';
		$mmtms_level_price    = isset( $_POST['mmtms_new_level_price'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_level_price'] ) ) : '';
		$mmtms_description    = isset( $_POST['description'] ) ? sanitize_text_field( wp_unslash( $_POST['description'] ) ) : '';
		$mmtms_hidden_slug    = isset( $_POST['level_slug_hidden'] ) ? sanitize_text_field( wp_unslash( $_POST['level_slug_hidden'] ) ) : '';
		$exist                = $mmtms->admin_helper->check_user_level_exists( $mmtms_new_level_name );

		if ( $exist && $exist !== $mmtms_new_level_name ) {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => esc_html__( 'Level name alreay exist, please use another level name.', 'momo-membership' ),
				)
			);
			exit;
		}
		if ( 'payment' === $mmtms_billing_type ) {
			if ( empty( $mmtms_level_price ) ) {
				echo wp_json_encode(
					array(
						'status' => 'bad',
						'msg'    => esc_html__( 'Billing Type is Payment, but price field is empty.', 'momo-membership' ),
					)
				);
				exit;
			}
			if ( ! is_numeric( $mmtms_level_price ) ) {
				echo wp_json_encode(
					array(
						'status' => 'bad',
						'msg'    => esc_html__( 'Billing Type is Payment, but price field is non numeric.', 'momo-membership' ),
					)
				);
				exit;
			}
		}
		foreach ( $mmtms_level_options as $i => $mmtms_level_option ) {
			if ( $mmtms_level_option['level_slug'] === $mmtms_hidden_slug ) {
				$mmtms_level_options[ $i ]['level_name']    = $mmtms_new_level_name;
				$mmtms_level_options[ $i ]['level_slug']    = $mmtms->admin_helper->mmtms_slugify( $mmtms_new_level_name );
				$mmtms_level_options[ $i ]['billing_type']  = $mmtms_billing_type;
				$mmtms_level_options[ $i ]['wp_capability'] = $mmtms_wp_level_role;
				$mmtms_level_options[ $i ]['level_price']   = $mmtms_level_price;
				$mmtms_level_options[ $i ]['description']   = $mmtms_description;
				update_option( 'mmtms_level_options', $mmtms_level_options );
				echo wp_json_encode(
					array(
						'status' => 'good',
						'msg'    => esc_html__( 'New Level Successfully added.', 'momo-membership' ),
						'level'  => $mmtms_level_options[ $i ],
					)
				);
				exit;
			}
		}
		echo wp_json_encode(
			array(
				'status' => 'bad',
				'msg'    => esc_html__( 'Something went wrong while editing level.', 'momo-membership' ),
			)
		);
		exit;
	}
	/**
	 * Get Level By Level slug
	 */
	public function mmtms_get_levels_by_slug() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_get_levels_by_slug' !== $_POST['action'] ) {
			return;
		}
		$slug                = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
		$mmtms_level_options = get_option( 'mmtms_level_options' );
		foreach ( $mmtms_level_options as $mmtms_level_option ) {
			if ( $mmtms_level_option['level_slug'] === $slug ) {
				echo wp_json_encode(
					array(
						'status' => 'good',
						'msg'    => esc_html__( 'Successfully found the required level.', 'momo-membership' ),
						'level'  => $mmtms_level_option,
					)
				);
				exit;
			}
		}
		echo wp_json_encode(
			array(
				'status' => 'bad',
				'msg'    => esc_html__( 'Something went wrong while generating data.', 'momo-membership' ),
			)
		);
	}
	/**
	 * Add New Level
	 */
	public function mmtms_ajax_add_new_level() {
		global $mmtms;
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_ajax_add_new_level' !== $_POST['action'] ) {
			return;
		}
		$mmtms_level_options  = get_option( 'mmtms_level_options' );
		$mmtms_new_level_name = isset( $_POST['mmtms_new_level_name'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_level_name'] ) ) : '';
		$mmtms_billing_type   = isset( $_POST['mmtms_billing_type'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_billing_type'] ) ) : ''; // payment.
		$mmtms_wp_level_role  = isset( $_POST['mmtms_wp_level_role'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_wp_level_role'] ) ) : ''; // suspended.
		$mmtms_level_price    = isset( $_POST['mmtms_new_level_price'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_level_price'] ) ) : 0;
		$mmtms_description    = isset( $_POST['description'] ) ? sanitize_text_field( wp_unslash( $_POST['description'] ) ) : '';
		$exist                = $mmtms->admin_helper->check_user_level_exists( $mmtms_new_level_name );

		if ( $exist ) {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => esc_html__( 'Level name alreay exist, please use another level name.', 'momo-membership' ),
				)
			);
			exit;
		}
		if ( 'payment' === $mmtms_billing_type ) {// Check if price is set and numeric.
			if ( empty( $mmtms_level_price ) ) {
				echo wp_json_encode(
					array(
						'status' => 'bad',
						'msg'    => esc_html__( 'Billing Type is Payment, but price field is empty.', 'momo-membership' ),
					)
				);
				exit;
			}
			if ( ! is_numeric( $mmtms_level_price ) ) {
				echo wp_json_encode(
					array(
						'status' => 'bad',
						'msg'    => esc_html__( 'Billing Type is Payment, but price field is non numeric.', 'momo-membership' ),
					)
				);
				exit;
			}
		}
		$mmtms_new_level_option['level_name']    = $mmtms_new_level_name;
		$mmtms_new_level_option['level_slug']    = $mmtms->admin_helper->mmtms_slugify( $mmtms_new_level_name );
		$mmtms_new_level_option['billing_type']  = $mmtms_billing_type;
		$mmtms_new_level_option['wp_capability'] = $mmtms_wp_level_role;
		$mmtms_new_level_option['level_price']   = $mmtms_level_price;
		$mmtms_new_level_option['description']   = $mmtms_description;
		$mmtms_level_options[]                   = $mmtms_new_level_option;
		update_option( 'mmtms_level_options', $mmtms_level_options );
		echo wp_json_encode(
			array(
				'status' => 'good',
				'msg'    => esc_html__( 'New Level Successfully added.', 'momo-membership' ),
				'level'  => $mmtms_new_level_option,
			)
		);
		exit;
	}

	/**
	 * Generate Admin Level Table
	 */
	public function mmtms_generate_admin_level_table() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		global $mmtms;
		if ( isset( $_POST['action'] ) && 'mmtms_generate_admin_level_table' !== $_POST['action'] ) {
			return;
		}
		echo wp_json_encode(
			array(
				'status'      => 'good',
				'level_table' => $mmtms->admin_helper->mmtms_generate_level_table(),
			)
		);
		exit;
	}
}
new Mmtms_Admin_Ajax_Level();
