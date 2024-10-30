<?php
/**
 * Admin Ajax Class for WP Capabilities
 *
 * @package momo-membership
 * @author MoMo Thems
 * @since v1.0.0
 */
class Mmtms_Admin_Ajax_WP_Capabilities {
	/**
	 * Constructor
	 */
	public function __construct() {
		$ajax_events = array(
			'mmtms_admin_add_new_role'                 => 'mmtms_admin_add_new_role',
			'mmtms_generate_admin_roles_table'         => 'mmtms_generate_admin_roles_table',
			'mmtms_delete_role_by_id'                  => 'mmtms_delete_role_by_id',
			'mmtms_get_role_name_by_id'                => 'mmtms_get_role_name_by_id',
			'mmtms_update_role_name'                   => 'mmtms_update_role_name',
			'mmtms_ajax_generate_capabilities_by_role' => 'mmtms_ajax_generate_capabilities_by_role',
			'mmtms_update_role_capabilities'           => 'mmtms_update_role_capabilities',

		);
		foreach ( $ajax_events as $ajax_event => $class ) {
			add_action( 'wp_ajax_' . $ajax_event, array( $this, $class ) );
			add_action( 'wp_ajax_nopriv_' . $ajax_event, array( $this, $class ) );
		}
	}

	/**
	 * Add New WP Role
	 */
	public function mmtms_admin_add_new_role() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_admin_add_new_role' !== $_POST['action'] ) {
			return;
		}
		$role_id   = isset( $_POST['role_id'] ) ? sanitize_text_field( wp_unslash( $_POST['role_id'] ) ) : '';
		$role_name = isset( $_POST['role_name'] ) ? sanitize_text_field( wp_unslash( $_POST['role_name'] ) ) : '';
		$result    = add_role(
			$role_id,
			$role_name,
			array(
				'read'         => true,
				'edit_posts'   => false,
				'delete_posts' => false,
			)
		);

		if ( null !== $result ) {
			echo wp_json_encode(
				array(
					'status' => 'good',
					'msg'    => esc_html__( 'New Role added.', 'momo-membership' ),
				)
			);
			exit;
		} else {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => esc_html__( 'Role Already Exist.', 'momo-membership' ),
				)
			);
			exit;
		}
	}

	/**
	 * Generate Admin roles table
	 */
	public function mmtms_generate_admin_roles_table() {
		global $mmtms;
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_generate_admin_roles_table' !== $_POST['action'] ) {
			return;
		}
		echo wp_json_encode(
			array(
				'status'     => 'good',
				'role_table' => $mmtms->admin_helper->mmtms_generate_role_table(),
			)
		);
		exit;
	}

	/**
	 * Delete Role by ID
	 */
	public function mmtms_delete_role_by_id() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_delete_role_by_id' !== $_POST['action'] ) {
			return;
		}
		$slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
		if ( get_role( $slug ) ) {
			remove_role( $slug );
			echo wp_json_encode(
				array(
					'status' => 'good',
					'msg'    => esc_html__( 'Role deleted successfully.', 'momo-membership' ),
				)
			);
			exit;
		} else {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => esc_html__( 'Role doesnot Exist.', 'momo-membership' ),
				)
			);
			exit;
		}
	}

	/**
	 * Get Role Name by Role ID
	 */
	public function mmtms_get_role_name_by_id() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_get_role_name_by_id' !== $_POST['action'] ) {
			return;
		}
		$slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
		if ( get_role( $slug ) ) {
			global $wp_roles;
			echo wp_json_encode(
				array(
					'status'    => 'good',
					'role_name' => $wp_roles->roles[ $slug ]['name'],
				)
			);
			exit;
		}
		echo wp_json_encode(
			array(
				'status' => 'bad',
				'msg'    => $slug,
			)
		);
		exit;
	}

	/**
	 * Update Role Name
	 */
	public function mmtms_update_role_name() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_update_role_name' !== $_POST['action'] ) {
			return;
		}
		$role_id   = isset( $_POST['role_id'] ) ? sanitize_text_field( wp_unslash( $_POST['role_id'] ) ) : '';
		$role_name = isset( $_POST['role_name'] ) ? sanitize_text_field( wp_unslash( $_POST['role_name'] ) ) : '';
		global $wp_roles;
		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
		$role_name                           = sanitize_text_field( $role_name );
		$wp_roles->roles[ $role_id ]['name'] = $role_name;
		$wp_roles->role_names[ $role_id ]    = $role_name;
		update_option( $wp_roles->role_key, $wp_roles->roles );
		echo wp_json_encode(
			array(
				'status' => 'good',
				'msg'    => esc_html__( 'Role display name changed', 'momo-membership' ),
				'role'   => $wp_roles->roles[ $role_id ],
			)
		);
		exit;
	}
	/**
	 * Generate Capabilities table by role
	 */
	public function mmtms_ajax_generate_capabilities_by_role() {
		global $mmtms;
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_ajax_generate_capabilities_by_role' !== $_POST['action'] ) {
			return;
		}
		$role_id = isset( $_POST['role_id'] ) ? sanitize_text_field( wp_unslash( $_POST['role_id'] ) ) : '';
		$content = $mmtms->admin_helper->mmtms_generate_capabilities_by_role( $role_id );

		echo wp_json_encode(
			array(
				'status'  => 'good',
				'content' => $content,
			)
		);
		exit;
	}
	/**
	 * Update Capabilities by Role
	 */
	public function mmtms_update_role_capabilities() {
		global $mmtms;
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_update_role_capabilities' !== $_POST['action'] ) {
			return;
		}
		$role                  = isset( $_POST['role'] ) ? sanitize_text_field( wp_unslash( $_POST['role'] ) ) : '';
		$capabilities          = isset( $_POST['cap'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['cap'] ) ) : array();
		$role_                 = get_role( $role );
		$existing_capabilities = array();
		foreach ( $role_->capabilities as $cap => $state ) {
			if ( 1 === (int) $state ) {
				$existing_capabilities[] = $cap;
			}
		}
		foreach ( $capabilities as $capability ) {
			if ( ! in_array( $capability, $existing_capabilities, true ) ) {
				$role_->add_cap( $capability );
			}
		}
		foreach ( $existing_capabilities as $ec ) {
			if ( ! in_array( $ec, $capabilities, true ) ) {
				$role_->remove_cap( $ec );
			}
		}
		echo wp_json_encode(
			array(
				'status'       => 'good',
				'capabilities' => $capabilities,
			)
		);
		exit;
	}
}
new Mmtms_Admin_Ajax_WP_Capabilities();
