<?php
/**
 * Admin Ajax Class for WP Users
 *
 * @package momo-membership
 * @author MoMo Thems
 * @since v1.0.0
 */
class Mmtms_Admin_Ajax_User {
	/**
	 * Constructor
	 */
	public function __construct() {
		$ajax_events = array(
			'mmtms_admin_add_new_user'        => 'mmtms_admin_add_new_user',
			'mmtms_generate_admin_user_table' => 'mmtms_generate_admin_user_table',
			'mmtms_user_detail_by_user_id'    => 'mmtms_user_detail_by_user_id',
			'mmtms_admin_edit_user_by_id'     => 'mmtms_admin_edit_user_by_id',

		);
		foreach ( $ajax_events as $ajax_event => $class ) {
			add_action( 'wp_ajax_' . $ajax_event, array( $this, $class ) );
			add_action( 'wp_ajax_nopriv_' . $ajax_event, array( $this, $class ) );
		}
	}

	/**
	 * Adds New User
	 */
	public function mmtms_admin_add_new_user() {
		global $mmtms;
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_admin_add_new_user' !== $_POST['action'] ) {
			return;
		}
		$username  = isset( $_POST['mmtms_new_user_name'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_user_name'] ) ) : '';
		$email     = isset( $_POST['mmtms_new_user_email'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_user_email'] ) ) : '';
		$fname     = isset( $_POST['mmtms_new_user_fname'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_user_fname'] ) ) : '';
		$lname     = isset( $_POST['mmtms_new_user_lname'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_user_lname'] ) ) : '';
		$password  = isset( $_POST['mmtms_new_user_password'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_user_password'] ) ) : '';
		$cpassword = isset( $_POST['mmtms_new_user_cpassword'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_user_cpassword'] ) ) : '';
		$role      = isset( $_POST['mmtms_wp_new_user_role'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_wp_new_user_role'] ) ) : '';
		$level     = isset( $_POST['mmtms_wp_new_user_level'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_wp_new_user_level'] ) ) : '';
		$user_id   = username_exists( $username );
		$_ee       = email_exists( $email );
		if ( $user_id ) {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => esc_html__( 'Username already exist.', 'momo-membership' ),
					'mid'    => 1,
				)
			);
			exit;
		}
		if ( $_ee ) {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => esc_html__( 'Email Address already exist.', 'momo-membership' ),
					'mid'    => 2,
				)
			);
			exit;
		}
		$user_id = wp_create_user( $username, $password, $email );
		if ( is_wp_error( $user_id ) ) {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => $user_id->get_error_message(),
				)
			);
			exit;
		} else {
			$user = new WP_User( $user_id );
			$user->set_role( $role );
			wp_update_user(
				array(
					'ID'           => $user_id,
					'first_name'   => $fname,
					'last_name'    => $lname,
					'display_name' => $fname . ' ' . $lname,
				)
			);
			$id = $mmtms->admin_helper->mmtms_create_user( $user_id, $level, $username );
			if ( is_wp_error( $id ) ) {
				echo wp_json_encode(
					array(
						'status' => 'bad',
						'msg'    => $user_id->get_error_message(),
					)
				);
				exit;
			} else {
				echo wp_json_encode(
					array(
						'status' => 'good',
						'msg'    => esc_html__( 'Member registered successfully.', 'momo-membership' ),
					)
				);
				exit;
			}
		}
	}

	/**
	 * Generate Members Table
	 */
	public function mmtms_generate_admin_user_table() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_generate_admin_user_table' !== $_POST['action'] ) {
			return;
		}
		global $mmtms;
		echo wp_json_encode(
			array(
				'status'     => 'good',
				'user_table' => $mmtms->admin_helper->mmtms_generate_user_table(),
			)
		);
		exit;
	}

	/**
	 * Get User detail by user id
	 */
	public function mmtms_user_detail_by_user_id() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_user_detail_by_user_id' !== $_POST['action'] ) {
			return;
		}
		$user_id    = isset( $_POST['user_id'] ) ? sanitize_text_field( wp_unslash( $_POST['user_id'] ) ) : 0;
		$user_meta  = get_userdata( $user_id );
		$user_roles = $user_meta->roles;
		$roles      = $user_roles[0];
		$post       = get_posts(
			array(
				'numberposts' => 1,
				'post_type'   => 'mmtms-members',
				'post_status' => 'publish',
				'meta_key'    => 'mmtms-members_user-id',
				'meta_value'  => $user_id,
			)
		);
		$level      = '';
		if ( ! empty( $post ) ) {
			$post_id = $post[0]->ID;
			$mpmv    = get_post_custom( $post_id );
			$level   = isset( $mpmv['mmtms-members_user-level'] ) ? $mpmv['mmtms-members_user-level'][0] : '';
		}
		$user             = array();
		$user['id']       = $user_id;
		$user['username'] = $user_meta->user_login;
		$user['role']     = $roles;
		$user['level']    = $level;
		$user['fname']    = $user_meta->first_name;
		$user['lname']    = $user_meta->last_name;
		$user['email']    = $user_meta->user_email;
		echo wp_json_encode(
			array(
				'status' => 'good',
				'user'   => $user,
			)
		);
		exit;
	}

	/**
	 * Edit User by User ID
	 */
	public function mmtms_admin_edit_user_by_id() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_admin_edit_user_by_id' !== $_POST['action'] ) {
			return;
		}
		global $mmtms;
		$user_id  = isset( $_POST['user_id_hidden'] ) ? sanitize_text_field( wp_unslash( $_POST['user_id_hidden'] ) ) : '';
		$username = isset( $_POST['mmtms_new_user_name'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_user_name'] ) ) : '';
		$email    = isset( $_POST['mmtms_new_user_email'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_user_email'] ) ) : '';
		$fname    = isset( $_POST['mmtms_new_user_fname'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_user_fname'] ) ) : '';
		$lname    = isset( $_POST['mmtms_new_user_lname'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_new_user_lname'] ) ) : '';
		$role     = isset( $_POST['mmtms_wp_new_user_role'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_wp_new_user_role'] ) ) : '';
		$level    = isset( $_POST['mmtms_wp_new_user_level'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_wp_new_user_level'] ) ) : '';
		$user_id  = username_exists( $username );
		$user     = get_user_by( 'ID', $user_id );
		$_ee      = email_exists( $email );
		if ( $_ee && $email !== $user->user_email ) {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => esc_html__( 'Email Address already exist.', 'momo-membership' ),
					'mid'    => 2,
				)
			);
			exit;
		}
		wp_update_user(
			array(
				'ID'           => $user_id,
				'first_name'   => $fname,
				'last_name'    => $lname,
				'display_name' => $fname . ' ' . $lname,
			)
		);
		$user->set_role( $role );
		$post_id = $mmtms->admin_helper->mmtms_update_user( $user_id, $level, $username );
		if ( $post_id ) {
			echo wp_json_encode(
				array(
					'status' => 'good',
					'msg'    => esc_html__( 'Member updated successfully.', 'momo-membership' ),
				)
			);
			exit;
		} else {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => esc_html__( 'Unable to find User.', 'momo-membership' ),
				)
			);
			exit;
		}
	}
}
new Mmtms_Admin_Ajax_User();
