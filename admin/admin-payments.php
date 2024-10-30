<?php
/**
 * Settings Page for Payments Settings
 *
 * @package momo-membership
 * @author MoMo Themes
 */

?>
<?php
$mmtms_payment_options = get_option( 'mmtms_payment_options' );
$mcurrent_user         = wp_get_current_user();
?>
<div class="mmtms_payment_form mmtms_admin_tab_page">
	<div class="mmtms-admin-tab-loading"></div>
	<div class="mmtms-admin-content-box">
		<div class="mmtms-admin-content-header">
			<h3><i class="mmtms-icon-wallet"></i><?php esc_html_e( 'Payments', 'momo-membership' ); ?></h3>
		</div>
	</div>
	<?php $enable_pp = isset( $mmtms_payment_options['enable_pp'] ) ? $mmtms_payment_options['enable_pp'] : ''; ?>
	<?php $checked = ( 'on' === $enable_pp ) ? 'checked' : ''; ?>
	<div class="mmtms-admin-yesno">
		<span class="mmtms-admin-toggle-container" afteryes="paypal_payment_gateway">
			<label class="switch">
				<input type="checkbox" class="switch-input" name="mmtms_payment_paypal_yn" <?php echo esc_attr( $checked ); ?>>
				<span class="switch-label" data-on="Yes" data-off="No"></span>
				<span class="switch-handle"></span>
			</label>
		</span>
		<span class="mmtms-toggle-container-label"><?php esc_html_e( 'Enable Paypal payment gateway', 'momo-membership' ); ?></span>
		<div class="afteryes" id="paypal_payment_gateway">
			<h3><?php esc_html_e( 'Paypal API Settings', 'momo-membership' ); ?></h3>
			<p><?php esc_html_e( 'Title', 'momo-membership' ); ?></p>
			<p>
				<input name="mmtms_paypal_title" placeholder="<?php esc_html_e( 'Paypal', 'momo-membership' ); ?>" type="text" value="<?php echo esc_html( isset( $mmtms_payment_options['pp_title'] ) ? $mmtms_payment_options['pp_title'] : '' ); ?>">
			</p>
			<p>
				<?php esc_html_e( 'Description', 'momo-membership' ); ?>
			</p>
				<textarea name="mmtms_paypal_description"><?php echo esc_html( isset( $mmtms_payment_options['pp_desc'] ) ? $mmtms_payment_options['pp_desc'] : '' ); ?></textarea>
			</p>
			<p>
				<?php esc_html_e( 'Paypal Email Address', 'momo-membership' ); ?>
			</p>
			<p>
				<input name="mmtms_paypal_email" placeholder="<?php echo esc_html( $mcurrent_user->user_email ); ?>" type="text" value="<?php echo esc_html( isset( $mmtms_payment_options['pp_email'] ) ? $mmtms_payment_options['pp_email'] : '' ); ?>">
			</p>
			<h3><?php esc_html_e( 'Paypal Live Settings', 'momo-membership' ); ?></h3>
			<p><?php esc_html_e( 'Live Paypal Client ID', 'momo-membership' ); ?></p>
			<p>
				<input name="mmtms_paypal_live_id" type="text" value="<?php echo esc_html( isset( $mmtms_payment_options['pp_live_id'] ) ? $mmtms_payment_options['pp_live_id'] : '' ); ?>">
			</p>
			<p><?php esc_html_e( 'Live Paypal Secret', 'momo-membership' ); ?></p>
			<p>
				<input name="mmtms_paypal_live_secret" type="text" value="<?php echo esc_html( isset( $mmtms_payment_options['pp_live_secret'] ) ? $mmtms_payment_options['pp_live_secret'] : '' ); ?>">
			</p>
		</div>
	</div>
	<?php $pp_enable_sandbox = isset( $mmtms_payment_options['pp_enable_sandbox'] ) ? $mmtms_payment_options['pp_enable_sandbox'] : ''; ?>
	<?php $checked = ( 'on' === $pp_enable_sandbox ) ? 'checked' : ''; ?>
	<div class="mmtms-admin-yesno">
		<span class="mmtms-admin-toggle-container" afteryes="paypal_sb_gateway">
			<label class="switch">
				<input type="checkbox" class="switch-input" name="mmtms_payment_paypal_sb_yn" <?php echo esc_attr( $checked ); ?>>
				<span class="switch-label" data-on="Yes" data-off="No"></span>
				<span class="switch-handle"></span>
			</label>
		</span>
		<span class="mmtms-toggle-container-label"><?php esc_html_e( 'Enable Paypal Sandbox', 'momo-membership' ); ?></span>
		<em>(<?php esc_html_e( 'If enabled, live payment will be disabled.', 'momo-membership' ); ?>)</em>
		<div class="afteryes" id="paypal_sb_gateway">
			<h3><?php esc_html_e( 'Paypal Sandbox Settings', 'momo-membership' ); ?></h3>
			<p><?php esc_html_e( 'Sandbox ID', 'momo-membership' ); ?></p>
			<p>
				<input name="mmtms_paypal_sb_id" type="text" value="<?php echo esc_html( isset( $mmtms_payment_options['pp_sandbox_id'] ) ? $mmtms_payment_options['pp_sandbox_id'] : '' ); ?>">
			</p>
			<p><?php esc_html_e( 'Sandbox Secret', 'momo-membership' ); ?></p>
			<p>
				<input name="mmtms_paypal_sb_secret" type="text" value="<?php echo esc_html( isset( $mmtms_payment_options['pp_sandbox_secret'] ) ? $mmtms_payment_options['pp_sandbox_secret'] : '' ); ?>">
			</p>
		</div>
		<div class="mmtms-admin-tab-bottom">
			<a class="mmtms-admin-btn save-paypal-settings" href="#"><?php esc_html_e( 'Save Settings', 'momo-membership' ); ?></a>
		</div>
	</div>
</div>
