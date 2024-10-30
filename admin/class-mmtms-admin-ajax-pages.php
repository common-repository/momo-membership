<?php
/**
 * Admin Ajax Class for Level
 *
 * @package momo-membership
 * @author MoMo Thems
 * @since v1.0.0
 */
class Mmtms_Admin_Ajax_Pages {
	/**
	 * Constructor
	 */
	public function __construct() {
		$ajax_events = array(
			'mmtms_ajax_reinstall_page' => 'mmtms_ajax_reinstall_page',

		);
		foreach ( $ajax_events as $ajax_event => $class ) {
			add_action( 'wp_ajax_' . $ajax_event, array( $this, $class ) );
			add_action( 'wp_ajax_nopriv_' . $ajax_event, array( $this, $class ) );
		}
	}

	/**
	 * Reinstall Page
	 */
	public function mmtms_ajax_reinstall_page() {
		check_ajax_referer( 'mmtms_admin_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_ajax_reinstall_page' !== $_POST['action'] ) {
			return;
		}
		global $mmtms;
		$page_definitions = $mmtms->admin_helper->get_pages_array();
		if ( ! isset( $_POST['slug'] ) && ! isset( $page_definitions[ $_POST['slug'] ] ) ) {
			return;
		}
		$slug  = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
		$page  = $page_definitions[ $slug ];
		$query = new WP_Query( 'pagename=' . $slug );
		if ( ! $query->have_posts() ) {
			// Add the page using the data from the array above.
			$result = wp_insert_post(
				array(
					'post_content'   => $page['content'],
					'post_name'      => $slug,
					'post_title'     => $page['title'],
					'post_status'    => 'publish',
					'post_type'      => 'page',
					'ping_status'    => 'closed',
					'comment_status' => 'closed',
				),
				true
			);
			if ( ! is_wp_error( $result ) ) {
				$content = $this->get_page_tr( $result );
				echo wp_json_encode(
					array(
						'status'  => 'good',
						'content' => $content,
					)
				);
				exit;
			}
		}
	}
	/**
	 * Generate Row for page
	 *
	 * @param integer $post_id Post ID.
	 */
	public function get_page_tr( $post_id ) {
		$post     = get_post( $post_id );
		$content  = '';
		$content .= '<td>' . esc_html( $post->post_title ) . '</td>';
		$content .= '<td>' . esc_html( $post->post_name ) . '</td>';
		$content .= '<td>';
		$content .= '<span class="mmtms_admin_p_status">';
		$content .= $post->post_status;
		$content .= '</span>';
		$content .= '</td>';
		$content .= '<td>';
		$content .= '<a href="' . esc_url( $page_->guid ) . '" target="_blank">';
		$content .= esc_html__( 'View', 'momo-membership' );
		$content .= '</a>';
		$content .= '</td>';
		return $content;
	}
}
new Mmtms_Admin_Ajax_Pages();
