<?php
/**
 * MoMo Themes Membership Plugin Setup
 *
 * @since 1.0.0
 * @author MoMo Themes
 * @package momo-membership
 */
class Mmtms_Admin_Setup {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->mmtms_run_setup();
	}

	/**
	 * Runs Setup
	 */
	private function mmtms_run_setup() {
		$this->mmtms_install_pages();
	}

	/**
	 * Add needed pages to installation
	 */
	private function mmtms_install_pages() {
		global $mmtms;
		$page_definitions = $mmtms->admin_helper->get_pages_array();

		foreach ( $page_definitions as $slug => $page ) {
			$query = new WP_Query( 'pagename=' . $slug );
			if ( ! $query->have_posts() ) {
				// Add the page using the data from the array above.
				wp_insert_post(
					array(
						'post_content'   => $page['content'],
						'post_name'      => $slug,
						'post_title'     => $page['title'],
						'post_status'    => 'publish',
						'post_type'      => 'page',
						'ping_status'    => 'closed',
						'comment_status' => 'closed',
					)
				);
			}
		}
	}
}
