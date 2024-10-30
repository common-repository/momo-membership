<?php
/**
 * Settings Page for Redirection Settings
 *
 * @package momo-membership
 * @author MoMo Themes
 */

?>
<?php $mmtms_redirection_options = get_option( 'mmtms_redirection_options' ); ?>
<div class="mmtms-admin-content-box mmtms-admin-redirection-form">
	<div class="mmtms-admin-tab-loading"></div>
	<div class="mmtms-admin-content-header">
		<h3><i class="mmtms-icon-loop-alt"></i><?php esc_html_e( 'Redirection Settings', 'momo-membership' ); ?></h3>
	</div>
	<div class="mmtms-admin-content-content">
		<div class="general-settings" style="display: block;">
			<label class="regular"><?php esc_html_e( 'Redirection after Login', 'momo-membership' ); ?></label>
			<?php
			$args = array(
				'depth'    => 0,
				'child_of' => 0,
				'selected' => 0,
				'echo'     => 1,
				'name'     => 'login_redirect_page_id',
				'class'    => 'mmtms-admin-select',
				'selected' => isset( $mmtms_redirection_options['mmtms_redirect_login'] ) ? $mmtms_redirection_options['mmtms_redirect_login'] : '',
			);
			wp_dropdown_pages( array_map( 'esc_html', $args ) );
			?>
			<label class="regular"><?php esc_html_e( 'Redirection after Logout', 'momo-membership' ); ?></label>
			<?php
			$args = array(
				'depth'    => 0,
				'child_of' => 0,
				'selected' => 0,
				'echo'     => 1,
				'name'     => 'logout_redirect_page_id',
				'class'    => 'mmtms-admin-select',
				'selected' => isset( $mmtms_redirection_options['mmtms_redirect_logout'] ) ? $mmtms_redirection_options['mmtms_redirect_logout'] : '',
			);
			wp_dropdown_pages( array_map( 'esc_html', $args ) );
			?>
		</div>
	</div>
	<div class="mmtms-admin-tab-bottom">
		<a class="mmtms-admin-btn save-redirection-settings" href="#"><?php esc_html_e( 'Save Settings', 'momo-membership' ); ?></a>
	</div>
</div>
