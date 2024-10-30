<?php
/**
 * MoMo Themes Membership Admin Settings Page
 *
 * @since 1.0.0
 * @author MoMo Themes
 * @package momo-membership
 */
class Mmtms_Admin_Settings {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->mmtms_settings_page();
	}

	/**
	 * Display Admin Settings Page
	 */
	private function mmtms_settings_page() {
		?>
			<form method="post" action="" id="mmtms-admin-form">
				<?php wp_nonce_field( 'mmtms_nonce_field', 'mmtms_nonce_name' ); ?>
				<div class="mmtms-admin-wrapper">
					<h2 class="nav-tab-wrapper">  
						<div class="nav-tab nav-tab-active">
							<?php esc_html_e( 'MMT Membership', 'momo-membership' ); ?>
						</div>  
					</h2>

					<table class="mmtms-admin-table">
						<tbody>
							<tr>
								<td valign="top">
									<ul class="mmtms-admin-tab">
										<li><a class="mmtms-tablinks active" href="#dashboard"><i class="mmtms-icon-gauge"></i><span><?php esc_html_e( 'Dashboard', 'momo-membership' ); ?></span></a></li>
										<li><a class="mmtms-tablinks" href="#wp-capabilities"><i class="mmtms-icon-wordpress"></i><span><?php esc_html_e( 'WP Capabilities', 'momo-membership' ); ?></span></a></li>
										<li><a class="mmtms-tablinks" href="#levels"><i class="mmtms-icon-universal-access"></i><span><?php esc_html_e( 'Levels', 'momo-membership' ); ?></span></a></li>
										<li><a class="mmtms-tablinks" href="#payments"><i class="mmtms-icon-wallet"></i><span><?php esc_html_e( 'Payments', 'momo-membership' ); ?></span></a></li>
										<li><a class="mmtms-tablinks" href="#members"><i class="mmtms-icon-users"></i><span><?php esc_html_e( 'Members', 'momo-membership' ); ?></span></a></li>
										<li><a class="mmtms-tablinks" href="#invoice"><i class="mmtms-icon-doc-text-inv"></i><span><?php esc_html_e( 'Invoice', 'momo-membership' ); ?></span></a></li>
										<li><a class="mmtms-tablinks" href="#pages"><i class="mmtms-icon-website"></i><span><?php esc_html_e( 'Default Pages', 'momo-membership' ); ?></span></a></li>
										<li><a class="mmtms-tablinks" href="#redirection"><i class="mmtms-icon-loop-alt"></i><span><?php esc_html_e( 'Redirection', 'momo-membership' ); ?></span></a></li>
										<li><a class="mmtms-tablinks" href="#email"><i class="mmtms-icon-mail"></i><span><?php esc_html_e( 'Email Settings', 'momo-membership' ); ?></span></a></li>
										<li><a class="mmtms-tablinks" href="#uninstall"><i class="mmtms-icon-trash-empty"></i><span><?php esc_html_e( 'Uninstall', 'momo-membership' ); ?></span></a></li>
										<?php do_action( 'tecsb_more_tab' ); ?>
									</ul>
								</td>
								<td class="mmtms-admin-tabcontent" width="100%" valign="top">
									<div id="dashboard" class="active mmtms-admin-content">
										<?php include_once 'admin-dashboard.php'; ?>
									</div>
									<div id="wp-capabilities" class="mmtms-admin-content">
										<?php include_once 'admin-wp-capabilities.php'; ?>
									</div>
									<div id="levels" class="mmtms-admin-content">
										<?php include_once 'admin-levels.php'; ?>
									</div>
									<div id="payments" class="mmtms-admin-content">
										<?php include_once 'admin-payments.php'; ?>
									</div>
									<div id="members" class="mmtms-admin-content">
										<?php include_once 'admin-members.php'; ?>
									</div>
									<div id="invoice" class="mmtms-admin-content">
										<?php include_once 'admin-invoice.php'; ?>
									</div>
									<div id="pages" class="mmtms-admin-content">
										<?php include_once 'admin-pages.php'; ?>
									</div>
									<div id="redirection" class="mmtms-admin-content">
										<?php include_once 'admin-redirection.php'; ?>
									</div>
									<div id="email" class="mmtms-admin-content">
										<?php include_once 'admin-email.php'; ?>
									</div>
									<div id="uninstall" class="mmtms-admin-content">
										<?php include_once 'admin-uninstall.php'; ?>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
		<?php
	}
}
new Mmtms_Admin_Settings();
