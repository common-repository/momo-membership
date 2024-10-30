<?php
/**
 * Settings Page for Members Settings
 *
 * @package momo-membership
 * @author MoMo Themes
 */
class Mmtms_Admin_Menu_Settings {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_head-nav-menus.php', array( $this, 'mmtms_add_metabox_to_menu_settings' ) );

		add_filter( 'wp_setup_nav_menu_item', array( $this, 'mmtms_setup_nav_menu_item' ) );
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'mmtms_nav_menu_type_label' ) );
	}
	/**
	 * Add metaboxes to menu settings
	 */
	public function mmtms_add_metabox_to_menu_settings() {
		add_meta_box(
			'mmtms-nav-menus',
			esc_html__( 'MMTMS Menu Item', 'momo-membership' ),
			array( $this, 'mmtms_add_metabox_to_menu_settings_content' ),
			'nav-menus',
			'side',
			'default'
		);
	}
	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function mmtms_add_metabox_to_menu_settings_content( $post ) {
		global $wp_meta_boxes, $nav_menu_selected_id, $menu_locations;
		$mmtms_menu = array(
			(object) array(
				'ID'               => 1,
				'db_id'            => 0,
				'menu_item_parent' => 0,
				'object_id'        => 1,
				'post_parent'      => 0,
				'type'             => 'custom',
				'object'           => 'mmtms-custom-menu',
				'type_label'       => esc_html__( 'Dynamic Login Logout', 'momo-membership' ),
				'title'            => esc_html__( 'Log in | Log out', 'momo-membership' ),
				'url'              => '#mmtms-custom-loginout#',
				'target'           => '',
				'attr_title'       => '',
				'description'      => '',
				'classes'          => array(),
				'xfn'              => '',
			),
		);

		$db_fields = false;
		if ( false === $db_fields ) {
			$db_fields = array(
				'parent' => 'parent',
				'id'     => 'post_parent',
			);
		}
		$walker = new Walker_Nav_Menu_Checklist( $db_fields );

		$removed_args = array(
			'action',
			'customlink-tab',
			'edit-menu-item',
			'menu-item',
			'page-tab',
			'_wpnonce',
		);
		?>
		<div id="mmtms-menu-item-container">
			<div id="tabs-panel-mmtms-menu-item-all" class="tabs-panel tabs-panel-active">
				<ul id="mmtms-loginout-menu-item" class="categorychecklist form-no-clear" >
					<?php
					echo walk_nav_menu_tree(
						array_map(
							'wp_setup_nav_menu_item',
							$mmtms_menu
						),
						0,
						(object) array(
							'walker' => $walker,
						)
					);
					?>
				</ul>

				<p class="button-controls">
					<span class="add-to-menu">
						<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu', 'momo-membership' ); ?>" name="mmtms-add-menu-item" id="submit-mmtms-menu-item-container" />
						<span class="spinner"></span>
					</span>
				</p>
			</div>
		</div>
		<?php
	}
	/**
	 * Modify menu item label
	 *
	 * @param object $menu_item The menu item to modify.
	 */
	public function mmtms_nav_menu_type_label( $menu_item ) {
		$custom_menu = array( '#mmtms-custom-loginout#' );
		if ( isset( $menu_item->object, $menu_item->url ) && 'custom' === $menu_item->object && in_array( $menu_item->url, $custom_menu, true ) ) {
			$menu_item->type_label = esc_html__( 'MMTMS Dynamic menu', 'momo-membership' );
		}
		return $menu_item;
	}
	/**
	 * Modify menu item
	 *
	 * @param object $item The menu item to modify.
	 */
	public function mmtms_setup_nav_menu_item( $item ) {
		global $pagenow;
		if ( 'nav-menus.php' !== $pagenow && ! defined( 'DOING_AJAX' ) && isset( $item->url ) && '#mmtms-custom-loginout#' === $item->url ) {
			$login_page_url      = get_option( 'mmtms_login_page_url', wp_login_url() );
			$logout_redirect_url = get_option( 'mmtms_logout_redirect_url', home_url() );

			$item->url   = ( is_user_logged_in() ) ? wp_logout_url( $logout_redirect_url ) : $login_page_url;
			$item->title = $this->mmtms_loginout_title( $item->title );
		}
		return $item;
	}
	/**
	 * Menu dynamic title
	 *
	 * @param string $title Title.
	 */
	public function mmtms_loginout_title( $title ) {
		$titles = explode( '|', $title );

		if ( ! is_user_logged_in() ) {
			return esc_html( isset( $titles[0] ) ? $titles[0] : esc_html__( 'Log In', 'momo-membership' ) );
		} else {
			return esc_html( isset( $titles[1] ) ? $titles[1] : esc_html__( 'Log Out', 'momo-membership' ) );
		}
	}
}
new Mmtms_Admin_Menu_Settings();
