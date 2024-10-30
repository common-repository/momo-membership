<?php
/**
 * Admin Custom Post Type Invoices
 *
 * @author MoMo Themes
 * @package momo-membership
 * @since v1.0.0
 */
class Mmtms_Admin_CPT_Invoices {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_cpt_invoices' ) );
		add_action( 'add_meta_boxes_mmtms-invoices', array( $this, 'adding_custom_meta_boxes' ) );
		add_action( 'admin_menu', array( $this, 'add_submenu_to_mmtms' ) );
		add_action( 'save_post_mmtms-invoices', array( $this, 'save_meta_data' ) );
		add_filter( 'manage_mmtms-invoices_posts_columns', array( $this, 'filter_cpt_invoice_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'mmtms_invoices_custom_columns' ), 10, 2 );
		add_action( 'save_post_mmtms-invoices', array( $this, 'send_email_on_invoice_created' ), 10, 3 );
	}

	/**
	 * Register CPT Invoices (mmtms-Invoices)
	 */
	public function register_cpt_invoices() {
		$labels = array(
			'name'               => esc_html__( 'Invoices', 'momo-membership' ),
			'singular_name'      => esc_html__( 'Invoice', 'momo-membership' ),
			'menu_name'          => esc_html__( 'Invoices', 'momo-membership' ),
			'name_admin_bar'     => esc_html__( 'Invoice', 'momo-membership' ),
			'add_new'            => esc_html__( 'Add New Invoice', 'momo-membership' ),
			'add_new_item'       => esc_html__( 'Add New Invoice', 'momo-membership' ),
			'new_item'           => esc_html__( 'New Invoice', 'momo-membership' ),
			'edit_item'          => esc_html__( 'Edit Invoice', 'momo-membership' ),
			'view_item'          => esc_html__( 'View Invoice', 'momo-membership' ),
			'all_items'          => esc_html__( 'All Invoices', 'momo-membership' ),
			'search_items'       => esc_html__( 'Search Invoices', 'momo-membership' ),
			'parent_item_colon'  => esc_html__( 'Parent Invoices:', 'momo-membership' ),
			'not_found'          => esc_html__( 'No Invoices found.', 'momo-membership' ),
			'not_found_in_trash' => esc_html__( 'No Invoices found in Trash.', 'momo-membership' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'MMT Members Invoice.', 'momo-membership' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'mmtms-invoices' ),
			'capability_type'    => 'post',
			'capabilities'       => array(
				'create_posts' => 'do_not_allow',
			),
			'map_meta_cap'       => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'custom-fields' ),
		);

		register_post_type( 'mmtms-invoices', $args );
	}

	/**
	 * Add Invoices as submneu of MMTMS
	 */
	public function add_submenu_to_mmtms() {
		add_submenu_page(
			'mmtms', // Parent slug.
			esc_html__( 'MMTMS Invoices', 'momo-membership' ), // Page title.
			esc_html__( 'Invoices', 'momo-membership' ), // Menu title.
			'manage_options', // Capability.
			'edit.php?post_type=mmtms-invoices',  // Slug.
			false // Function.
		);
	}

	/**
	 * Add Custom Column to Invoices Table
	 *
	 * @param array $columns Invoice Table Columns.
	 */
	public function filter_cpt_invoice_columns( $columns ) {
		unset( $columns['mmtms_access'] );
		$columns['mmtms_title']     = esc_html__( 'Invoice No.', 'momo-membership' );
		$columns['mmtms_user_name'] = esc_html__( 'User Name', 'momo-membership' );
		unset( $columns['title'] );
		unset( $columns['date'] );
		return $columns;
	}

	/**
	 * Replace Column Title
	 *
	 * @param array $columns Invoice Table Columns.
	 */
	public function replace_column_title_method( $columns ) {
		$old_title        = $columns['title'];
		$columns['title'] = esc_html__( 'Invoice #', 'momo-membership' ) . $old_title;
		return $columns;
	}
	/**
	 * Render Custom Column
	 *
	 * @param string  $column Column Name.
	 * @param integer $post_id Post ID.
	 */
	public function mmtms_invoices_custom_columns( $column, $post_id ) {
		global $mmtms;
		switch ( $column ) {
			case 'mmtms_user_name':
				$user_id = get_post_meta( $post_id, 'mmtms-invoices_user-id' );
				$user    = get_user_by( 'id', $user_id[0] );
				echo esc_html( $user->display_name );
				break;
			case 'mmtms_title':
				$old_title = get_the_title();
				$new_title = '<b style="font-size:16px"><a href="' . get_permalink() . '"># ' . $old_title . '</a></b>';
				echo esc_html( $new_title );
				break;
		}
	}
	/**
	 * Send Email when email is created
	 *
	 * @param int  $post_id The post ID.
	 * @param post $post The post object.
	 * @param bool $update Whether this is an existing post being updated or not.
	 */
	public function send_email_on_invoice_created( $post_id, $post, $update ) {
		global $mmtms;
		$date_format           = get_option( 'date_format' );
		$time_format           = get_option( 'time_format' );
		$mmtms_invoice_options = get_option( 'mmtms_invoice_options' );
		$business_logo         = isset( $mmtms_invoice_options['mmtms_business_logo'] ) ? $mmtms_invoice_options['mmtms_business_logo'] : '';
		$business_name         = isset( $mmtms_invoice_options['mmtms_inv_bname'] ) ? $mmtms_invoice_options['mmtms_inv_bname'] : '';
		$business_address      = isset( $mmtms_invoice_options['mmtms_inv_address'] ) ? $mmtms_invoice_options['mmtms_inv_address'] : '';
		$business_email        = isset( $mmtms_invoice_options['mmtms_inv_email'] ) ? $mmtms_invoice_options['mmtms_inv_email'] : '';
		$business_phone        = isset( $mmtms_invoice_options['mmtms_inv_phone'] ) ? $mmtms_invoice_options['mmtms_inv_phone'] : '';
		$email_subject         = isset( $mmtms_invoice_options['mmtms_inv_email_sub'] ) ? $mmtms_invoice_options['mmtms_inv_email_sub'] : esc_html__( 'Invoice', 'momo-membership' );
		$meta                  = get_post_custom( $post_id );
		$user_id               = isset( $meta['mmtms-invoices_user-id'] ) ? $meta['mmtms-invoices_user-id'][0] : '';
		$date                  = isset( $meta['mmtms-invoices_invoice-date'] ) ? $meta['mmtms-invoices_invoice-date'][0] : '';
		$level_slug            = isset( $meta['mmtms-invoices_user-level'] ) ? $meta['mmtms-invoices_user-level'][0] : '';
		$price                 = isset( $meta['mmtms-invoices_invoice-price'] ) ? $meta['mmtms-invoices_invoice-price'][0] : '';
		$level                 = $mmtms->fhelper->mmtms_get_level_by_slug( $level_slug );
		$user                  = get_user_by( 'id', $user_id );
		$date_                 = gmdate( $date_format, strtotime( $date ) );
		$time                  = gmdate( $time_format, strtotime( $date ) );
		$date                  = $date_ . ' ' . $time;
		ob_start();
			include $mmtms->mmtms_path . '/includes/invoice-email-template.php';
			$message = ob_get_contents();
		ob_end_clean();
		$user_email = $user->user_email;
		$headers    = array( 'Content-Type: text/html; charset=UTF-8' );
		if ( ! empty( $business_email ) ) {
			$headers[] = esc_html__( 'From: ', 'momo-membership' ) . $business_name . ' <' . $business_email . '>';
		}
		wp_mail( $user_email, $email_subject, $message, $headers );
	}
	/**
	 * Adds custom meta boxes
	 *
	 * @param post $post The post object.
	 */
	public function adding_custom_meta_boxes( $post ) {
		add_meta_box(
			'mmtms-invoices_user-id',
			esc_html__( 'User ID', 'momo-membership' ),
			array( $this, 'render_mmtms_invoices_user_id' ),
			'mmtms-invoices',
			'normal',
			'default'
		);
		add_meta_box(
			'mmtms-invoices_user-level',
			esc_html__( 'User Level', 'momo-membership' ),
			array( $this, 'render_mmtms_invoices_user_level' ),
			'mmtms-invoices',
			'normal',
			'default'
		);
		add_meta_box(
			'mmtms-invoices_invoice-date',
			esc_html__( 'Invoice Date', 'momo-membership' ),
			array( $this, 'render_mmtms_invoices_invoice_date' ),
			'mmtms-invoices',
			'normal',
			'default'
		);
		add_meta_box(
			'mmtms-invoices_invoice-price',
			esc_html__( 'Invoice Price', 'momo-membership' ),
			array( $this, 'render_mmtms_invoices_invoice_price' ),
			'mmtms-invoices',
			'normal',
			'default'
		);
	}
	/**
	 * Save Meta Box
	 *
	 * @param integer $post_id Post ID.
	 */
	public function save_meta_data( $post_id ) {
		if ( ! isset( $_POST['mmtms_invoices_post'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mmtms_invoices_post'] ) ), 'mmtms_invoices_post' ) ) {
			return;
		}
		if ( array_key_exists( 'mmtms-invoices_user-id', $_POST ) ) {
			update_post_meta(
				$post_id,
				'mmtms-invoices_user-id',
				sanitize_text_field( wp_unslash( $_POST['mmtms-invoices_user-id'] ) )
			);
		}
		if ( array_key_exists( 'mmtms-invoices_user-level', $_POST ) ) {
			update_post_meta(
				$post_id,
				'mmtms-invoices_user-level',
				sanitize_text_field( wp_unslash( $_POST['mmtms-invoices_user-level'] ) )
			);
		}
		if ( array_key_exists( 'mmtms-invoices_invoice-date', $_POST ) ) {
			update_post_meta(
				$post_id,
				'mmtms-invoices_invoice-date',
				sanitize_text_field( wp_unslash( $_POST['mmtms-invoices_invoice-date'] ) )
			);
		}
		if ( array_key_exists( 'mmtms-invoices_invoice-price', $_POST ) ) {
			update_post_meta(
				$post_id,
				'mmtms-invoices_invoice-price',
				sanitize_text_field( wp_unslash( $_POST['mmtms-invoices_invoice-price'] ) )
			);
		}
	}

	/**
	 * Render Meta Box User ID
	 */
	public function render_mmtms_invoices_user_id() {
		global $post;
		$pmv = get_post_custom( $post->ID );
		wp_nonce_field( 'mmtms_invoices_post', 'mmtms_invoices_post' );
		?>
		<input type="text" name="mmtms-invoices_user-id" value="<?php echo esc_html( isset( $pmv['mmtms-invoices_user-id'] ) ? sanitize_text_field( $pmv['mmtms-invoices_user-id'][0] ) : '' ); ?>">
		<?php
	}
	/**
	 * Render Meta Box User Level
	 */
	public function render_mmtms_invoices_user_level() {
		global $post;
		$pmv = get_post_custom( $post->ID );
		?>
		<input type="text" name="mmtms-invoices_user-level" value="<?php echo esc_html( isset( $pmv['mmtms-invoices_user-level'] ) ? sanitize_text_field( $pmv['mmtms-invoices_user-level'][0] ) : '' ); ?>">
		<?php
	}
	/**
	 * Render Meta Box Invoice Date
	 */
	public function render_mmtms_invoices_invoice_date() {
		global $post;
		$pmv = get_post_custom( $post->ID );
		?>
		<input type="text" name="mmtms-invoices_invoice-date" value="<?php echo esc_html( isset( $pmv['mmtms-invoices_invoice-date'] ) ? sanitize_text_field( $pmv['mmtms-invoices_invoice-date'][0] ) : '' ); ?>">
		<?php
	}

	/**
	 * Render Meta Box Invoice Price
	 */
	public function render_mmtms_invoices_invoice_price() {
		global $post;
		$pmv = get_post_custom( $post->ID );
		?>
		<input type="text" name="mmtms-invoices_invoice-price" value="<?php echo esc_html( isset( $pmv['mmtms-invoices_invoice-price'] ) ? sanitize_text_field( $pmv['mmtms-invoices_invoice-price'][0] ) : '' ); ?>">
		<?php
	}
}
new Mmtms_Admin_CPT_Invoices();
