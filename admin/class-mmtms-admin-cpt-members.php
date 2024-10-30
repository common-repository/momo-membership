<?php
/**
 * Admin Custom Post Type Members
 *
 * @author MoMo Themes
 * @package momo-membership
 * @since v1.0.0
 */
class Mmtms_Admin_CPT_Members {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_cpt_members' ) );
		add_action( 'add_meta_boxes_mmtms-members', array( $this, 'adding_custom_meta_boxes' ) );
	}

	/**
	 * Register CPT Members (mmtms-members)
	 */
	public function register_cpt_members() {
		$labels = array(
			'name'               => esc_html__( 'Members', 'momo-membership' ),
			'singular_name'      => esc_html__( 'Member', 'momo-membership' ),
			'menu_name'          => esc_html__( 'Members', 'momo-membership' ),
			'name_admin_bar'     => esc_html__( 'Member', 'momo-membership' ),
			'add_new'            => esc_html__( 'Add New Member', 'momo-membership' ),
			'add_new_item'       => esc_html__( 'Add New Member', 'momo-membership' ),
			'new_item'           => esc_html__( 'New Member', 'momo-membership' ),
			'edit_item'          => esc_html__( 'Edit Member', 'momo-membership' ),
			'view_item'          => esc_html__( 'View Member', 'momo-membership' ),
			'all_items'          => esc_html__( 'All Members', 'momo-membership' ),
			'search_items'       => esc_html__( 'Search Members', 'momo-membership' ),
			'parent_item_colon'  => esc_html__( 'Parent Members:', 'momo-membership' ),
			'not_found'          => esc_html__( 'No Members found.', 'momo-membership' ),
			'not_found_in_trash' => esc_html__( 'No Members found in Trash.', 'momo-membership' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'MMT Membershiship Member.', 'momo-membership' ),
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => false,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'mmtms-members' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'custom-fields' ),
		);

		register_post_type( 'mmtms-members', $args );
	}

	/**
	 * Adds custom meta boxes
	 *
	 * @param post $post The post object.
	 */
	public function adding_custom_meta_boxes( $post ) {
		add_meta_box(
			'mmtms-members_user-id',
			esc_html__( 'User ID', 'momo-membership' ),
			array( $this, 'render_mmtms_members_user_id' ),
			'mmtms-members',
			'normal',
			'default'
		);
		add_meta_box(
			'mmtms-members_user-level',
			esc_html__( 'User Level', 'momo-membership' ),
			array( $this, 'render_mmtms_members_user_level' ),
			'mmtms-members',
			'normal',
			'default'
		);
	}

	/**
	 * Render Meta Box User ID
	 */
	public function render_mmtms_members_user_id() {
		global $post;
		$pmv = get_post_custom( $post->ID );
		wp_nonce_field( 'mmtms_members_post', 'mmtms_members_post' );
		?>
		<input type="text" value="<?php echo esc_html( isset( $pmv['mmtms-members_user-id'] ) ? sanitize_text_field( $pmv['mmtms-members_user-id'][0] ) : '' ); ?>">
		<?php
	}
	/**
	 * Render Meta Box User Level
	 */
	public function render_mmtms_members_user_level() {
		global $post;
		$pmv = get_post_custom( $post->ID );
		?>
		<input type="text" value="<?php echo esc_html( isset( $pmv['mmtms-members_user-level'] ) ? sanitize_text_field( $pmv['mmtms-members_user-level'][0] ) : '' ); ?>">
		<?php
	}
}
new Mmtms_Admin_CPT_Members();
