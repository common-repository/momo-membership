<?php
/**
 * Settings Page for Members Settings
 *
 * @package momo-membership
 * @author MoMo Themes
 */
class Mmtms_Admin_Metaboxes {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post_meta' ) );
	}

	/** Add Metabox */
	public function add_meta_boxes() {
		add_meta_box(
			'mmtms_mb',
			esc_html__( 'MMT Membership', 'momo-membership' ),
			array( $this, 'mmtms_metaboxes' ),
			null,
			'side',
			'high'
		);
	}

	/**
	 * Metabox function
	 */
	public function mmtms_metaboxes() {
		global $post;
		wp_nonce_field( 'mmtms_mb_nonce_mpage', 'mmtms_mb_nonce' );
		$mb_levels_arr_show  = get_post_meta( $post->ID, 'mb_levels_arr_show', true );
		$mb_levels_arr_block = get_post_meta( $post->ID, 'mb_levels_arr_block', true );
		echo '<div class="mb_select_container_mmtms">';
			echo '<select name="mb_show_or_block_mmtms" id="mb_show_or_block_mmtms" autocomplete="off">';
				echo '<option value="s">' . esc_html__( 'Show Post only for', 'momo-membership' ) . '</option>';
				echo '<option value="b">' . esc_html__( 'Block Post only for', 'momo-membership' ) . '</option>';
			echo '</select>';

			$levels = get_option( 'mmtms_level_options' );
			echo '<select name="mb_levels_select_mmtms" id="mb_levels_select_mmtms" autocomplete="off">';
				echo '<option value="0">' . esc_html__( 'Select User Levels', 'momo-membership' ) . '</option>';
				echo '<option value="all">' . esc_html__( 'All', 'momo-membership' ) . '</option>';
				echo '<option value="mmtms-ru">' . esc_html__( 'Registered User', 'momo-membership' ) . '</option>';
				echo '<option value="mmtms-uu">' . esc_html__( 'Unregistered User', 'momo-membership' ) . '</option>';
		foreach ( $levels as $level ) {
			echo '<option value="' . esc_attr( $level['level_slug'] ) . '">' . esc_html( $level['level_name'] ) . '</option>';
		}
			echo '</select>';
		echo '</div>';
		echo '<div class="mmtms_admin_tags_list_mb_show">';
			global $mmtms;
			echo '<input type="hidden" name="mb_levels_arr_show" value="' . esc_attr( $mb_levels_arr_show ) . '" autocomplete="off">';
		if ( ! empty( $mb_levels_arr_show ) ) {
			$show_arr = explode( ',', $mb_levels_arr_show );
			foreach ( $show_arr as $show ) {
				$level_name = $mmtms->admin_helper->mmtms_level_name_by_slug( $show );
				echo '<span class="mmtms_mb_tag" data-mval="' . esc_attr( $show ) . '">' . esc_html( $level_name ) . '<i class="mmtms-icon-cancel-circled remove_mb_mmts_tag show"></i></span>';
			}
		}
		echo '</div>';
		echo '<div class="mmtms_admin_tags_list_mb_block">';
			echo '<input type="hidden" name="mb_levels_arr_block" value="' . esc_attr( $mb_levels_arr_block ) . '" autocomplete="off">';
		if ( ! empty( $mb_levels_arr_block ) ) {
			$block_arr = explode( ',', $mb_levels_arr_block );
			foreach ( $block_arr as $block ) {
				$level_name = $mmtms->admin_helper->mmtms_level_name_by_slug( $block );
				echo '<span class="mmtms_mb_tag" data-mval="' . esc_attr( $block ) . '">' . esc_html( $level_name ) . '<i class="mmtms-icon-cancel-circled remove_mb_mmts_tag block"></i></span>';
			}
		}
		echo '</div>';
	}

	/**
	 * Save Post Meta
	 *
	 * @param integer $post_id Post ID.
	 */
	public function save_post_meta( $post_id ) {
		if ( ! isset( $_POST['mmtms_mb_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mmtms_mb_nonce'] ) ), 'mmtms_mb_nonce_mpage' ) ) {
			return;
		}
		// Validate Post Type.
		if ( ! isset( $_POST['post_type'] ) ) {
			return;
		}
		if ( isset( $_POST['mb_levels_arr_show'] ) ) {
			$show = sanitize_text_field( wp_unslash( $_POST['mb_levels_arr_show'] ) );
			update_post_meta( $post_id, 'mb_levels_arr_show', $show );
		}
		if ( isset( $_POST['mb_levels_arr_block'] ) ) {
			$block = sanitize_text_field( wp_unslash( $_POST['mb_levels_arr_block'] ) );
			update_post_meta( $post_id, 'mb_levels_arr_block', $block );
		}
	}
}
new Mmtms_Admin_Metaboxes();
