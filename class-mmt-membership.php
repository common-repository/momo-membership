<?php
/**
 * Plugin Name: MoMo Membership
 * Plugin URI: http://www.momothemes.com/
 * Description: Add Membership feature to WordPress.
 * Author: MoMo Themes
 * Version: 1.1.2
 * Domain Path: /languages
 * Author URI: http://www.momothemes.com/
 * Requires at least: 5.0.1
 * Tested up to: 6.1
 * Text Domain: momo-membership
 */
/**
 * MoMo Membership main plugin
 */
class MMT_Membership {
	/**
	 * Plugin Version.
	 *
	 * @var string
	 */
	public $version = '1.1.2';
	/**
	 * Plugin Name
	 *
	 * @var string
	 */
	public $name = 'MMT-Membership';
	/**
	 * Plugin Slug
	 *
	 * @var string
	 */
	public $plugin_slug = 'momo-membership';

	/**
	 * Constructor
	 */
	public function __construct() {
		load_plugin_textdomain( 'momo-membership', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		define( 'MMTMS_FILE', __FILE__ );
		$this->mmtms_super_init();
		add_action( 'init', array( $this, 'mmtms_plugin_init' ) );
		add_action( 'widgets_init', array( $this, 'mmtms_register_login_widget' ) );
	}

	/**
	 * Super INIT
	 */
	public function mmtms_super_init() {
		include_once 'includes/class-mmtms-frontend.php';
		include_once 'includes/class-mmtms-shortcodes.php';
		include_once 'includes/class-mmtms-frontend-helper.php';
		include_once 'includes/class-mmtms-helper-functions.php';
		$this->frontend = new Mmtms_Frontend();
		$this->fhelper  = new Mmtms_Frontend_Helper();
		$this->helper   = new Mmtms_Helper_Functions();

		include_once 'includes/class-mmtms-user-profile.php';
		if ( is_admin() ) {
			include_once 'admin/class-mmtms-admin-init.php';
			include_once 'admin/class-mmtms-admin-helper.php';
			include_once 'admin/class-mmtms-admin-ajax-level.php';
			include_once 'admin/class-mmtms-admin-ajax-wp-capabilities.php';
			include_once 'admin/class-mmtms-admin-ajax-user.php';
			include_once 'admin/class-mmtms-admin-ajax-payments.php';
			include_once 'admin/class-mmtms-admin-ajax-invoice.php';
			include_once 'admin/class-mmtms-admin-ajax-email.php';
			include_once 'admin/class-mmtms-admin-ajax-pages.php';
			include_once 'admin/class-mmtms-admin-metaboxes.php';
			include_once 'admin/class-mmtms-user-profile-meta.php';
			include_once 'admin/class-mmtms-admin-ajax-redirection.php';
			include_once 'admin/class-mmtms-admin-ajax-uninstall.php';

			$this->mmtms_admin  = new Mmtms_Admin_Init();
			$this->admin_helper = new Mmtms_Admin_Helper();

			include_once 'members-activity/class-mmt-members-activity.php';
			include_once 'members-activity/class-mmt-members-activity-ajax.php';
		}
		include_once 'admin/class-mmtms-admin-menu-settings.php';
		include_once 'admin/class-mmtms-admin-cpt-members.php';
		include_once 'admin/class-mmtms-admin-cpt-invoices.php';

		include_once 'members-activity/class-mmt-members-database.php';
		include_once 'members-activity/class-mmt-members-data.php';
		include_once 'members-activity/class-mmt-members-generate-data.php';

		include_once 'email/class-mmtms-email-helper.php';
		$this->email = new Mmtms_Email_Helper();

		add_action( 'wp_loaded', array( $this, 'mmtms_email_response_page_loaded' ) );
	}

	/**
	 * Load Email Response Page
	 */
	public function mmtms_email_response_page_loaded() {
		load_plugin_textdomain( 'momo-membership', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		$page_id = $this->email->mmtms_email_response_page();
	}
	/**
	 * Plugin Initialization
	 */
	public function mmtms_plugin_init() {
		$this->mmtms_url    = path_join( plugins_url(), basename( dirname( __FILE__ ) ) );
		$this->mmtms_path   = dirname( __FILE__ );
		$this->plugin_path  = dirname( __FILE__ );
		$this->mmtms_assets = str_replace( array( 'http:', 'https:' ), '', $this->mmtms_url ) . '/assets/';
		$this->mmtms_plugin = str_replace( array( 'http:', 'https:' ), '', $this->mmtms_url ) . '/';
	}

	/**
	 * Register Widget
	 */
	public function mmtms_register_login_widget() {
		include_once 'includes/class-mmtms-login-widget.php';
		register_widget( 'Mmtms_Login_Widget' );
	}
}
$GLOBALS['mmtms'] = new MMT_Membership();
