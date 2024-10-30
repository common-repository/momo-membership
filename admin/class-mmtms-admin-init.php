<?php
/**
 * MoMo Themes Membership Admin
 *
 * @since 1.0.0
 * @author MoMo Themes
 * @package momo-membership
 */
class Mmtms_Admin_Init {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'mmtms_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'mmtms_admin_init_function' ) );
		register_activation_hook( MMTMS_FILE, array( $this, 'mmtms_activation' ) );
		add_filter( 'manage_posts_columns', array( $this, 'add_user_access_column' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'custom_columns_for_access' ), 10, 2 );
		add_filter( 'display_post_states', array( $this, 'add_state_to_pages' ), 999, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'mmtms_admin_script_style' ), 99 );
	}

	/**
	 * Add Page State
	 *
	 * @param array $states States array.
	 * @param post  $post Post Object.
	 */
	public function add_state_to_pages( $states, $post ) {
		$class_s = '<div class="mmtms_admin_p_status">';
		$class_e = '</div>';
		$output  = '';
		global $mmtms;
		if ( isset( $post->ID ) ) {
			if ( get_post_type( $post->ID ) === 'page' ) {
				switch ( $post->post_name ) {
					case 'mmtms-register':
						$output = esc_html__( 'MMTMS - Registration', 'momo-membership' );
						break;
					case 'mmtms-login':
						$output = esc_html__( 'MMTMS - Login', 'momo-membership' );
						break;
					case 'mmtms-logout':
						$output = esc_html__( 'MMTMS - logout', 'momo-membership' );
						break;
					case 'mmtms-subscription':
						$output = esc_html__( 'MMTMS - subscription', 'momo-membership' );
						break;
					case 'mmtms-reset-password':
						$output = esc_html__( 'MMTMS - reset password', 'momo-membership' );
						break;
					case 'mmtms-profile':
						$output = esc_html__( 'MMTMS - user profile', 'momo-membership' );
						break;
				}
				if ( ! empty( $output ) ) {
					$states[] = $class_s . $output . $class_e;
				}
			}
		}
		return $states;
	}
	/**
	 * Admin Initialization Function
	 */
	public function mmtms_admin_init_function() {
		global $wp_roles;
		$wp_roles->add_cap( 'administrator', 'mmtms_add_new_role' );
	}
	/**
	 * Plugin Activation Setup
	 */
	public function mmtms_activation() {
		if ( is_multisite() ) {
			$blogs = get_sites();
			if ( ! empty( $blogs ) ) {
				foreach ( $blogs as $blog ) {
					switch_to_blog( $blog->blog_id );
					$this->mmtms_site_activation();
					restore_current_blog();
				}
			}
		} else {
			$this->mmtms_site_activation();
		}
	}

	/**
	 * Site Activation Function
	 */
	public function mmtms_site_activation() {
		if ( is_admin() ) {
			include_once 'class-mmtms-admin-setup.php';
			new Mmtms_Admin_Setup();
		}
	}
	/**
	 * Admin Menu
	 */
	public function mmtms_admin_menu() {
		global $menu, $pagenow, $mmtms;
		// Create admin menu page .
		add_menu_page(
			esc_html__( 'MMT - Membership', 'momo-membership' ),
			'MMT Membership',
			'manage_options',
			'mmtms',
			array( $this, 'mmtms_settings_page' ),
			'dashicons-groups'
		);
		add_submenu_page(
			'mmtms', // Parent slug.
			esc_html__( 'MoMo Membership Settings', 'momo-membership' ), // Page title.
			esc_html__( 'Settings', 'momo-membership' ), // Menu title.
			'manage_options', // Capability.
			'mmtms',  // Slug.
			false,
			2
		);
	}

	/**
	 * Settings page
	 */
	public function mmtms_settings_page() {
		include_once 'class-mmtms-admin-settings.php';
	}

	/**
	 * Admin Page Scripts and Styles
	 */
	public function mmtms_admin_script_style() {
		global $mmtms;
		wp_enqueue_style( 'mmtms-admin-style', $mmtms->mmtms_assets . 'css/mmtms-admin-style.css', array(), $mmtms->version );
		wp_enqueue_style( 'mmtms-font-icon', $mmtms->mmtms_assets . 'css/mmtms.css', array(), $mmtms->version );
		wp_enqueue_script( 'mmtms-admin-script', $mmtms->mmtms_assets . 'js/mmtms-admin-script.js', array( 'jquery', 'wp-tinymce' ), $mmtms->version, true );
		$params = array(
			'ajaxurl'               => admin_url( 'admin-ajax.php' ),
			'ajax_nonce'            => wp_create_nonce( 'mmtms_admin_ajax_nonce' ),
			'requierd_field_msg'    => esc_html__( 'Requierd Fields are missing.', 'momo-membership' ),
			'role_id_msg'           => esc_html__( 'Only characters, digits, hyphens or underscore are allowed in Role ID.', 'momo-membership' ),
			'password_mismatch_msg' => esc_html__( 'Password and Confirm password mismatch', 'momo-membership' ),
			'user_invalid_email'    => esc_html__( 'Email address is invalid', 'momo-membership' ),
		);
		wp_localize_script( 'mmtms-admin-script', 'mmtms_admin_ajax', $params );
	}
	/**
	 *  Add custom column to post list
	 *
	 * @param array $columns Columns Array.
	 * */
	public function add_user_access_column( $columns ) {
		$new_cols = array();
		foreach ( $columns as $key => $value ) {
			if ( 'title' === $key ) {
				$new_cols['mmtms_access'] = esc_html__( 'Access', 'momo-membership' );  // put the tags column before it.
			}
			$new_cols[ $key ] = $value;
		}
		return $new_cols;
	}

	/**
	 * Custom Columns for user access for that post
	 *
	 * @param string  $column Column Name.
	 * @param integer $post_id Post ID.
	 */
	public function custom_columns_for_access( $column, $post_id ) {
		global $mmtms;
		$mb_levels_arr_show  = get_post_meta( $post_id, 'mb_levels_arr_show', true );
		$mb_levels_arr_block = get_post_meta( $post_id, 'mb_levels_arr_block', true );
		switch ( $column ) {
			case 'mmtms_access':
				if ( empty( $mb_levels_arr_show ) && empty( $mb_levels_arr_block ) ) {
					$text = esc_html__( 'Public', 'momo-membership' );
					echo "<span class='mmtms-column-published'>" . esc_html( $text ) . '</span>';
				} else {
					if ( ! empty( $mb_levels_arr_show ) ) {
						$show_arr = explode( ',', $mb_levels_arr_show );
						foreach ( $show_arr as $show ) {
							$level_name = $mmtms->admin_helper->mmtms_level_name_by_slug( $show );
							echo '<span class="mmtms-column-show">' . esc_html( $level_name ) . '<i class="mmtms-icon-ok"></i></span>';
						}
					}
					if ( ! empty( $mb_levels_arr_block ) ) {
						$block_arr = explode( ',', $mb_levels_arr_block );
						foreach ( $block_arr as $block ) {
							$level_name = $mmtms->admin_helper->mmtms_level_name_by_slug( $block );
							echo '<span class="mmtms-column-block">' . esc_html( $level_name ) . '<i class="mmtms-icon-cancel-circled"></i></span>';
						}
					}
				}
				break;
		}
	}
}
